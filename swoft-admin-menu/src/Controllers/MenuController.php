<?php

namespace Swoft\Admin\Menu\Controllers;

use Swoft\Admin\Admin;
use Swoft\Admin\Form;
use Swoft\Admin\Grid;
use Swoft\Admin\Layout\Content;
use Swoft\Admin\Menu\Models\AdminMenu;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * @Controller("/admin-menu")
 */
class MenuController
{
    /**
     * 初始化
     */
    protected function setup()
    {
        // 指定控制器名称, 用于指定翻译文件名称
        Admin::setControllerName('AdminMenu');
        // 指定url前缀
        Admin::setUrlPrefix('/admin-menu');
    }


    /**
     * @RequestMapping("/admin-menu")
     */
    public function index(Content $content)
    {
        $this->setup();

        $header = t('Menu', 'admin-menu.labels');
        $content->breadcrumb($header);

        $url = Admin::url()->create();

        Admin::script(<<<EOF
$('#create-menu-btn').click(function () {
    layer.open({
        type: 2, 
        content: '$url',
        title: '$header',
        shadeClose: true,
        shade: false,
        area: ['50%', '80%'],
        end: function () {
            $('.grid-refresh').click()
        },
    });

});
EOF
);

        return $content
            ->header($header)
            ->description(t('List', 'admin-menu.labels'))
            ->body($this->grid())
            ->response();
    }

    protected function grid()
    {
        $grid = new Grid();

        $grid->disableFilter();
        $grid->disableView();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableCreation();

        $grid->tools(function (Grid\Tools $tools) {
            $label = t('New', 'admin');
            $tools->append(
                "<div class='pull-right btn-group'>
                    <a id='create-menu-btn' class=\"btn btn-success\"> <i class='fa fa-save'></i> $label</a>
                </div>"
            );
        });

        $grid->id;
        $grid->icon->display(function ($value) {
            return "<i class='fa $value'></i>";
        });
        $grid->title->tree();
        $grid->path->editable();
        $grid->useprefix->switch();
        $grid->newpage->switch();
        $grid->priority->editable('select', range(0, 50));

        $grid->filter(function (Grid\Filter $filter) {
            $filter->equal('id');

        });

        return $grid;
    }

    /**
     * 创建表单
     *
     * @param mixed $id
     * @param bool $isUpdateOrDeleteRequest
     * @return Form
     */
    public function form($id = null, bool $isUpdateOrDeleteRequest = false)
    {
        $form = new Form();

        $form->disableViewButton();

        if ($id) {
            $form->display('id');
        }
        $form->text('title')->rules('required');
        $form->icon('icon');
        $form->text('path')
            ->prepend('<i class="fa fa-internet-explorer fa-fw"></i>')
            ->help('如果想跳转到第三方页面, 请填写完整url地址');

        $form->switch('useprefix')
            ->default(1);
        $form->switch('newpage');

        $form->select('priority')
            ->options(range(0, 50))
            ->help('可以通过此字段改变菜单排序, 值越小排序越靠前');

        // 获取所有菜单, 如果是修改或者是删除的请求就不需要查处菜单数据
        $menus = $isUpdateOrDeleteRequest ? [] : $this->getAllMenuExceptSelf($id);

        $form->tree('parent_id')->options($menus);

        $form->hidden('updated_at');
        if ($id) {
            $form->display('created_at');
            $form->display('updated_at');
        } else {
            // 新增记录的时候防止字段被过滤
            $form->hidden('created_at');
        }

        return $form;
    }

    /**
     * @param $id
     * @return array
     */
    protected function getAllMenuExceptSelf($id)
    {
        $q = AdminMenu::query();

        $id && $q->where('id', $id, '!=');

        $menus = $q->get(['id', 'title', 'parent_id'])->getResult();

        return $menus ? $menus->toArray() : [];
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
        $this->setup();

        $form = $this->form();

        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableListButton();

        return $content
            ->body($form)
            ->simple()
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
        $this->setup();

        $header = t('Menu', 'admin-menu.labels');
        $current = t('Edit', 'admin-menu.labels');
        $content->breadcrumb($header, Admin::url()->list());
        $content->breadcrumb($current);

        $form = $this->form($id);
        // 使用row布局风格
        $form->style(Form::STYLE_ROW);

        return $content
            ->header($header)
            ->description($current)
            ->body($form->edit($id))
            ->response();
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
        $this->setup();

        return $this->form($id, true)
            ->saving(function (Form $form) {
                $date = date('Y-m-d H:i:s');

                // 由于swoft实体没有自动更新created_at字段的功能,所以新增或编辑时需要手动加
                $form->input('updated_at', $date);
            })
            ->update($id)
            ->done();
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
        $this->setup();

        return $this->form()
            ->saving(function (Form $form) {
                $date = date('Y-m-d H:i:s');

                // 由于swoft实体没有自动更新created_at字段的功能,所以新增或编辑时需要手动加
                $form->input('created_at', $date);
                $form->input('updated_at', $date);
            })
            ->insert()
            ->done(Admin::url()->create());
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
        if ($this->form($id, true)->destroy($id)) {
            $data = [
                'status'  => true,
                'message' => t('Delete succeeded!', 'admin'),
            ];
        } else {
            $data = [
                'status'  => false,
                'message' => t('Delete failed!', 'admin'),
            ];
        }

        return response()->json($data);
    }
}
