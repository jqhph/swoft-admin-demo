<?php

namespace App\Controllers\Admin;

use Swoft\Admin\Admin;
use Swoft\Admin\Layout\Content;
use Swoft\Admin\Layout\Row;
use Swoft\Admin\Menu\Controllers\MenuController;
use Swoft\Admin\Widgets\Alert;
use Swoft\Admin\Widgets\Card;
use Swoft\Admin\Widgets\Code;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * @Controller("/admin/simple")
 */
class SimpleController
{
    /**
     * @RequestMapping("/admin/simple")
     */
    public function index(Content $content)
    {
        $content->body((new Alert('
            很多时候, 我们想要构建一个不需要菜单栏和导航栏的单独页面, 
            但是又需要用到内置的Admin组件, 如果通过替换模板的方式去实现一个没有菜单的页面会比较繁琐。<br>
            因此系统提供了一个<code>Content::simple</code>接口让用户可以直接构建一个"简单页面",
            调用此接口后系统会自动过滤菜单, 顶部导航栏以及一些不必要的静态资源。
        ', '场景说明'))->info());

        $content->body(function (Row $row) {
            $row->column(12, <<<EOF
<div style="padding:30px">
<a id="simple-create" class="btn btn-success">使用simple接口构建弹窗表单, 并在关闭弹窗后自动刷新页面</a>

<br><br>
<a id="simple-code" class="btn btn-primary">使用simple接口构建预览代码页面</a>
</div>
EOF
);

            Admin::script(<<<EOF
$('#simple-create').click(function () {
  layer.open({
        type: 2, 
        content: '/admin/simple/create',
        title: 'Create Menu',
        shadeClose: true,
        shade: false,
        area: ['50%', '80%'],
        end: function () {
            $.pjax.reload({container:'#pjax-container', url: '/admin/simple'});
            setTimeout(function () {LA.success('刷新成功');},100);
        },
    });
});

$('#simple-code').click(function () {
    layer.open({
        type: 2,
        title: '代码预览',
        shadeClose: true,
        shade: false,
        area: ['70%', '80%'],
        content: '/admin/simple/code'
    });
});
EOF
);

        });

        return $content
            ->breadcrumb('Simple Page')
            ->header('Simple Page')
            ->response();
    }

    /**
     * @RequestMapping()
     */
    public function create(Content $content)
    {
        // 这里只是演示, 直接使用菜单的form对象
        $form = (new MenuController())->form();

        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableListButton();

        return $content
            ->body($form)
            ->simple() // 调用simple方法, 可以过滤菜单及导航栏
            ->response();
    }

    /**
     * @RequestMapping()
     */
    public function code(Content $content)
    {
        return $content
            ->body(new Code(__FILE__, 15, 200))
            ->simple() // 调用simple方法, 可以过滤菜单及导航栏
            ->response();
    }

    /**
     * 新增记录
     *
     * @RequestMapping(route="create", method=RequestMethod::POST)
     * @return mixed
     */
    public function insert()
    {
        admin_notice('演示弹窗功能, 并没有进行真正的新增操作', 'info');

        return redirect_to('/admin/simple/create');
    }

}
