<?php

namespace App\Controllers\Components;

use Faker\Factory;
use Swoft\Admin\Admin;
use Swoft\Admin\Form\Field\Html;
use Swoft\Admin\Layout\Row;
use Swoft\Admin\Menu\Models\AdminMenu;
use Swoft\Admin\Widgets\Card;
use Swoft\Admin\Widgets\Code;
use Swoft\Admin\Widgets\DebugDump;
use Swoft\Admin\Widgets\Form;
use Swoft\Admin\Widgets\Tab;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Support\Input;
use Swoft\Support\Url;

/**
 * @Controller("/admin/components")
 */
class FormController
{
    /**
     * @RequestMapping("form")
     */
    public function index()
    {
        $content = Admin::content();

        // 添加面包屑导航
        $content->breadcrumb(
            ['text' => 'Form']
        );

        if (request()->getMethod() == 'POST') {
            $content->row(new Card('POST', new DebugDump(Input::all())));
        }

        // 切换布局风格 switch_layout
        $url = Url::full(['switch_layout' => !http_get('switch_layout')]);
        $content->row("<p>
<div class='btn-group'>
<a class='btn btn-custom ' href='$url'>切换布局风格</a>
</div>
</p>");

        $content->row(function (Row $row) {
            $tab = new Tab();
            $tab->add('Form-1', $this->form1());
            $tab->add('Form-2', $this->form2());
            $tab->add('代码', new Code(__FILE__, 69, 122));

            $row->column(6, $tab);

            $tab = new Tab();
            $tab->add('Form-3', $this->form3());
            $tab->add('代码', new Code(__FILE__, 123, 150));
            $row->column(6, $tab);
        });

        return $content
            ->header('Form')
            ->response();
    }


    protected function form1()
    {
        $form = new Form();

        if (!http_get('switch_layout')) {
            $form->style('row');
        }

        $form->text('form1.text', 'text');
        $form->password('form1.password', 'password');
        $form->email('form1.email', 'email');
        $form->mobile('form1.mobile', 'mobile');
        $form->url('form1.url', 'url');
        $form->ip('form1.ip', 'ip');
        $form->searchId('form1.custom', 'custom')->help('自定义表单字段');
        $form->color('form1.color', 'color');
        $form->rate('form1.rate', 'rate');
        $form->decimal('form1.decimal', 'decimal');
        $form->number('form1.number', 'number');
        $form->currency('form1.currency', 'currency');
        $form->switch('form1.switch', 'switch');
        $form->textarea('form1.textarea', 'textarea');

        if (http_get('switch_layout')) {
            $form->setWidth(9, 2);
        }
        return "<div style='padding:10px 8px'>{$form->render()}</div>";
    }

    protected function form2()
    {
        $form = new Form();

        if (!http_get('switch_layout')) {
            $form->style('row');
        }

        $form->date('form2.date', 'date');
        $form->time('form2.time', 'time');
        $form->datetime('form2.datetime', 'datetime');
        $form->divide();
        $form->dateRange('form2.date-start', 'form2.date-end', 'date range');
        $form->timeRange('form2.time-start', 'form2.time-end', 'time range');
        $form->dateTimeRange('form2.datetime-start', 'form2.datetime-end', 'datetime range');

        $form->html(function (Html $html) {
            return $html->value().'~~~~~~~~~';
        }, 'html')->help('自定义内容');

        if (http_get('switch_layout')) {
            $form->setWidth(9, 2);
        }
        return "<div style='padding:9px 8px'>{$form->render()}</div>";
    }

    protected function form3()
    {
        $form = new Form();

        if (!http_get('switch_layout')) {
            $form->style('row');
        }

        $names = $this->createNames();

        $form->tree('form3.tree', 'tree')->options($this->getMenuNodes());
        $form->select('form3.select', 'select')->options($names);
        $form->multipleSelect('form3.multiple-select', 'multiple select')->options($names);
        $form->image('form3.image', 'image');
        $form->multipleFile('form3.multiple-file', 'multiple file');
        $form->checkbox('form3.checkbox', 'checkbox')->options(['GET', 'POST', 'PUT', 'DELETE']);
        $form->radio('form3.radio', 'radio')->options(['GET', 'POST', 'PUT', 'DELETE']);
        $form->listbox('form3.listbox', 'listbox')->options($names);

        $form->editor('editor', 'editor');

        if (http_get('switch_layout')) {
            $form->setWidth(10, 2);
        }

        return "<div style='padding:9px 8px'>{$form->render()}</div>";
    }


    /**
     * 生成随机数据
     *
     * @return array
     */
    protected function createNames()
    {
        if (isset($this->names)) {
            return $this->names;
        }
        $faker = Factory::create();
        $this->names = [];

        for ($i = 0; $i < 15; $i ++) {
            $name = $faker->name;
            $this->names[$name] = $name;
        }

        return $this->names;
    }

    /**
     * @return array
     */
    protected function getMenuNodes()
    {
        $menus = AdminMenu::query()->get()->getResult();

        if (!$menus) return [];

        $new = [];
        foreach ($menus->toArray() as &$menu) {
            $tmp = [];
            foreach ($menu as $k => $v) {
                $tmp[str__slug($k, '_')] = $v;
            }
            $new[] = $tmp;
        }
        return $new;

    }
}
