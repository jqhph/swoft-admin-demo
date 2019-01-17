<?php

namespace App\Controllers\Admin;

use App\Admin\Form\Tools\UserFormButtons;
use App\Models\Entity\UserAddress;
use App\Models\Entity\UserProfiles;
use Psr\Http\Message\ResponseInterface;
use Swoft\Admin\Widgets\Card;
use Swoft\Admin\Widgets\Code;
use Swoft\Admin\Widgets\Tab;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use App\Models\Entity\Users;
use Swoft\Admin\Admin;
use Swoft\Admin\Form;
use Swoft\Admin\Grid;
use Swoft\Admin\Layout\Content;
use Swoft\Admin\Show;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Exception\RouteNotFoundException;
use Swoft\Support\Collection;
use Swoft\Support\Contracts\Renderable;
use Swoft\Support\Url;
use SwoftTest\Db\Testing\Entity\User;

/**
 * @Controller("/admin/users")
 */
class UsersController
{
   /**
    * 创建表格
    *
    * @return Grid
    */
    protected function grid()
    {
        $grid = Admin::grid();

        // 更改表格外层容器
        $grid->wrapper(function (Renderable $view) {
            $tab = new Tab();

            $tab->add(t('Example', 'admin'), $view);
            // 代码预览
            $tab->add(t('Code', 'admin'), new Code(__FILE__, 36, 105));

            return $tab;
        });

        $grid->id->sortable()->asc()->display(function ($value) {
            return "<code>$value</code>";
        });
        $grid->name->editable();
        $grid->email->display(function ($value) {
            return "<i class='glyphicon glyphicon-envelope'></i> $value";
        });
        $grid->avatar
            ->image(config('admin.upload.filesystem.faker.url'), 80, 80)
            ->responsive();
        $grid->password->setHeaderAttributes(['style' => 'color:#ff5b5b']);

        $grid->created_at;
        $grid->updated_at->sortable();

        $grid->filter(function (Grid\Filter $filter) {
            $filter->scope('status', function (Grid\Filter\Scopes $scopes) {
                $scopes->add(0, translate_field('normal'))->where('is_deleted', 0);
                $scopes->add(1, translate_field('trash'))->where('is_deleted', 1);
                // 默认选中第一个条件
                $scopes->select(0);
                // 设置按钮样式
                $scopes->style('custom');
                // 设置取消按钮名称
                $scopes->setCancelLabel(translate_field('All'));
            });

            $filter->scope('register', function (Grid\Filter\Scopes $scopes) {
                $scopes->add(0, translate_field('new'))->where('created_at', '2000-01-01 00:00:00', '>=');
                $scopes->add(1, translate_field('old'))->where('created_at', '2000-01-01 00:00:00', '<');
                // 设置按钮样式
                $scopes->style('custom');
            });

            $filter->group('id', function (Grid\Filter\Group $group) {
                $group->equal();
                $group->lt();
                $group->gt();
                $group->nlt();
                $group->ngt();
                $group->startWith();
            });
            $filter->like('name');
            // 由于swoft实体使用限制,搜索字段需要与数据表字段名一样,否则实体无法识别
            $filter->between('created_at')
                ->datetime()
                ->width(5);
        });

        return $grid;
    }

    /**
     * 创建表单
     *
     * @param $id
     * @return Form
     */
    protected function form($id = null)
    {
        $form = new Form();

        if (!http_get('switch_layout')) {
            // 更改布局风格
            $form->style(Form::STYLE_ROW);
        }
        $form->setId($id);
        // 设置默认列宽度
        $form->setDefaultColumnWidth(7);

        // 添加代码预览按钮
        $form->tools(function (Form\Tools $tools) {
            $tools->append(new UserFormButtons());
        });

        $form->tab(t('Basic', 'users.labels'), function (Form $form) use ($id) {
            if ($id) {
                $form->display('id');
            }
            $form->text('name')->rules('required');
            $form->email('email');
            $form->image('avatar')->disk('faker');

            // 新增时密码必填
            if (!$id) {
                $rules = 'required|min:6';
            } else {
                $rules = 'min:6';
            }
            $form->password('password')->rules($rules)->customFormat(function () {
                // 密码不显示
                return '';
            });
            // 密码确认
            $form->password('password_confirm')->rules('confirm:password');

            $form->hidden('updated_at');
            if ($id) {
                $form->display('created_at');
                $form->display('updated_at');
            } else {
                // 新增记录的时候防止字段被过滤
                $form->hidden('created_at');
            }
        });

        $form->tab(t('Address', 'users.labels'), function (Form $form) {
            $form->select('address.province_id');
            $form->select('address.city_id');
            $form->select('address.district_id');
            $form->text('address.address');
        });

        // 自定义布局演示
        // 增加一列
        $form->column(5, function (Form\MultipleForm $multipleForm) {
            // 此处布局风格会与前面orm定义的风格保持同步
            // 这里为了不受上面布局风格切换演示影响固写死
            $multipleForm->style(Form::STYLE_ROW);

            $multipleForm->url('profile.homepage');
            $multipleForm->mobile('profile.mobile');
            $multipleForm->text('profile.document');
            $multipleForm->radio('profile.gender')->options(t('gender', 'users.options'));
            $multipleForm->text('profile.birthday');
            $multipleForm->text('profile.address');
            $multipleForm->color('profile.color');
            $multipleForm->number('profile.age');
            $multipleForm->display('profile.last_login_at');
            $multipleForm->display('profile.last_login_ip');
            return new Card(t('自定义布局演示', 'users.labels'), $multipleForm);
        });

        // 忽略密码确认
        $form->ignore(['password_confirm']);

        return $form;
    }

    /**
     * 修改记录(包括修改单个字段)
     *
     * @RequestMapping(route="{id}", method=RequestMethod::POST)
     *
     * @param mixed $id
     * @return mixed
     */
    public function update($id)
    {
        return $this->form($id)
            ->update()
            ->done();
    }

    /**
     * 表单代码预览
     *
     * @RequestMapping("preview-form-code")
     * @return ResponseInterface
     */
    public function previewFormCode()
    {
        return Admin::content()
            ->body(new Code(__FILE__, 110, 205))
            ->simple()
            ->response();
    }

    /**
     * 创建视图
     *
     * @param mixed $id
     * @return Show
     */
    protected function show($id)
    {
        $show = new Show($id);

        $show->name;
        $show->email;
        // 换行不显示分割线
        $show->divider(false);

        $show->createdAt;
        $show->updatedAt;
        $show->divider();
        $show->avatar->image(config('admin.upload.filesystem.faker.url'))->width(12);

        return $show;
    }

    /**
     * 列表页
     *
     * @RequestMapping(route="/admin/users", method=RequestMethod::GET)
     *
     * @param Content $content
     * @return mixed
     */
    public function index(Content $content)
    {
        $grid = $this->grid();

        if ($response = $grid->export(function (Collection $records) {
            // 可以对待导出的内容进行过滤或加工
            return $records;
        })) {
            // 返回导出内容
            return $response;
        }

        // 添加面包屑导航
        $content->breadcrumb(t('Users', 'admin.menus'));

        return $content
            ->header(translate_label('Users'))
            ->description(translate_label('List'))
            ->body($grid)
            ->response();
    }

    /**
     * 详情页
     *
     * @RequestMapping(route="view/{id}", method=RequestMethod::GET)
     *
     * @param mixed $id
     * @param Content $content
     * @return mixed
     */
    public function view($id, Content $content)
    {
        $header = translate_label('Users');
        $current = translate_label('View');

        // 添加面包屑导航
        $content->breadcrumb($header, Admin::url()->list());
        $content->breadcrumb($current);

        return $content
            ->header($header)
            ->description($current)
            ->body($this->show($id))
            ->response();
    }

    /**
     * 新增页
     *
     * @RequestMapping(route="create", method=RequestMethod::GET)
     *
     * @param Content $content
     * @return mixed
     */
    public function create(Content $content)
    {
        $header = translate_label('Users');
        $current = translate_label('Create');

        // 添加面包屑导航
        $content->breadcrumb($header, Admin::url()->list());
        $content->breadcrumb($current);

        return $content
            ->header($header)
            ->description($current)
            ->body($this->form())
            ->response();
    }

    /**
     * 编辑页
     *
     * @RequestMapping(route="{id}", method=RequestMethod::GET)
     *
     * @param mixed $id
     * @param Content $content
     * @return mixed
     */
    public function edit($id, Content $content)
    {
        // 数据不存在抛出 RouteNotFoundException 异常
        if (!$id) {
            throw new RouteNotFoundException();
        }

        $header = translate_label('Users');
        $current = translate_label('Edit');

        // 添加面包屑导航
        $content->breadcrumb($header, Admin::url()->list());
        $content->breadcrumb($current);

        return $content
            ->header($header)
            ->description($current)
            ->body($this->form($id)->edit())
            ->response();
    }

    /**
     * 新增记录
     *
     * @RequestMapping(route="create", method=RequestMethod::POST)
     *
     * @return mixed
     */
    public function insert()
    {
        return $this->form()
            ->insert()
            ->done(Admin::url()->list());
    }

    /**
     * 删除(批量)记录
     *
     * @RequestMapping(route="{id}", method=RequestMethod::DELETE)
     *
     * @param int $id
     * @return mixed
     */
    public function destroy($id)
    {
        // 删除操作如下:
//        return $this->form($id)->destroyAndResponse();

        $data = [
            'status'  => false,
            'message' => t('Delete failed!', 'admin'),
        ];

        return response()->json($data);
    }

}
