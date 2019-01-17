<?php

namespace App\Controllers\Components;

use Faker\Factory;
use Swoft\Admin\Admin;
use Swoft\Admin\Layout\Column;
use Swoft\Admin\Layout\Content;
use Swoft\Admin\Layout\Row;
use Swoft\Admin\Widgets\Alert;
use Swoft\Admin\Widgets\Box;
use Swoft\Admin\Widgets\Card;
use Swoft\Admin\Widgets\Code;
use Swoft\Admin\Widgets\InfoBox;
use Swoft\Admin\Widgets\Markdown;
use Swoft\Admin\Widgets\Paginator;
use Swoft\Admin\Widgets\Tab;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;

/**
 * @Controller("/admin/components")
 */
class WidgetsController
{
    /**
     * @RequestMapping("btn-paginator")
     */
    public function btnAndPaginator()
    {
        $content = Admin::content();

        // 按钮
        $content->row(new Card('Button', $this->buttons()));

        // 分页
        $paginator = new Paginator(9000, 10, 10);
        $content->row(new Card(
            'Paginator',
            "<ul class=\"pagination pagination-sm no-margin pull-left\">{$paginator->render()}</ul><div class='clearfix'></div>"
        ));

        $content->row(new Card('代码', new Code(__FILE__, 29, 53)));

        $header = 'Button & Paginator';
        // 添加面包屑导航
        $content->breadcrumb(
            ['text' => $header]
        );

        return $content->header($header)->response();
    }

    /**
     * @RequestMapping()
     */
    public function alert()
    {
        $content = Admin::content();
        // notice
        $faker = Factory::create();

        $content->row(new Alert($faker->text, 'Danger'));
        $content->row((new Alert($faker->text, 'Warning'))->warning());
        $content->row((new Alert($faker->text, 'Success'))->success());
        $content->row((new Alert($faker->text, 'Info'))->info());

        $content->row(new Card('代码', new Code(__FILE__, 57, 78)));

        $header = 'Alert';
        // 添加面包屑导航
        $content->breadcrumb(
            ['text' => $header]
        );

        return $content->header($header)->response();
    }

    /**
     * @RequestMapping("card-box")
     */
    public function cardAndBox()
    {
        $content = Admin::content();

        $faker = Factory::create();

        $content->row((new Card('Card', $faker->text(500)))->tool('<a class=\'btn btn-primary\'>primary</a>&nbsp;'));

        $content->row(function (Row $row) use ($faker) {
            $row->column(6, (new Box('Default', $faker->text(200))));
            $row->column(6, (new Box('Success', $faker->text(200)))->style('success'));
            $row->column(12, (new Box('Danger', $faker->text(200)))->style('danger')->collapsable());
            $row->column(12, (new Box('Info', $faker->text(800)))->style('info')->solid());
            $row->column(12, (new Box('Primary', new Code(__FILE__, 82, 104)))->style('primary')->solid()->collapsable());
        });

        $header = 'Card & Box';
        // 添加面包屑导航
        $content->breadcrumb($header);

        return $content->header($header)->response();
    }

    /**
     * @RequestMapping()
     */
    public function markdown(Content $content)
    {
        $content->row(new Card("Markdown", new Markdown($this->getMarkdownText())));

        $content->row(new Card('Code', new Code(__FILE__, 108, 122)));

        $header = 'Markdown';
        // 添加面包屑导航
        $content->breadcrumb(
            ['text' => $header]
        );

        return $content->header($header)->response();
    }

    /**
     * @RequestMapping("tab-infobox")
     * @param Content $content
     */
    public function tabAndinfobox(Content $content)
    {
        $content->row(function (Row $row) {
            $row->column(3, new InfoBox('New Users', 'users', 'aqua', '', '1024'));
            $row->column(3, new InfoBox('New Orders', 'shopping-cart', 'green', '', '150%'));
            $row->column(3, new InfoBox('Articles', 'book', 'yellow', '', '2786'));
            $row->column(3, new InfoBox('Documents', 'file', 'red', '', '698726'));
        });

        $faker = Factory::create();

        $tab = new Tab();

        $tab->add('Code', new Code(__FILE__, 127, 153));
        $tab->add('Test', $faker->text(500));

        $content->row($tab);

        $header = 'Tab & Infobox';
        // 添加面包屑导航
        $content->breadcrumb(
            $header
        );

        return $content->header($header)->response();
    }

    /**
     * @RequestMapping()
     */
    public function table(Content $content)
    {
        $faker = Factory::create();

        $headers = ['ID', 'Name', 'Email', 'Phone', 'Company'];

        $rows = [];
        for ($i = 0; $i < 10; $i++) {
            $rows[] = [
                $i++,
                $faker->name,
                $faker->email,
                $faker->phoneNumber,
                $faker->company
            ];
        }
        $content->row(new Card(null, new \Swoft\Admin\Widgets\Table($headers, $rows)));

        $rows = [
            'name'   => $faker->name,
            'age'    => 25,
            'gender' => 'Male',
            'birth'  => $faker->date(),
        ];
        $content->row(new Card(null, (new \Swoft\Admin\Widgets\Table(['Key', 'Value'], $rows))->class('table table-striped ')));

        $content->row(new Card('Code', new Code(__FILE__, 157, 193)));

        $header = 'Table';
        // 添加面包屑导航
        $content->breadcrumb(
            ['text' => $header]
        );

        return $content->header($header)->response();
    }

    /**
     * @RequestMapping()
     */
    public function layer(Content $content)
    {
        $content->row(<<<EOF
<p style="margin:20px">
<a class='btn btn-success' onclick='LA.success("success")'>成功</a>&nbsp;
<a class='btn btn-danger' onclick='LA.error("error")'>出错</a>&nbsp;
<a class='btn btn-warning' onclick='LA.warning("warning")'>警告</a>&nbsp;
<a class='btn btn-info' onclick='LA.info("info", "c")'>提示</a>&nbsp;&nbsp;&nbsp;
<a class='btn btn-custom' onclick='LA.confirm("确认?", function(){})'>确认?</a>&nbsp;&nbsp;&nbsp;

<a class='btn btn-purple' id="layeropen">弹窗</a>&nbsp;
</p>
EOF
);
        Admin::script(<<<EOF
$('#layeropen').click(function () {
    layer.open({
        type: 2,
        title: 'Iframe',
        shadeClose: true,
        shade: false,
        area: ['70%', '80%'],
        content: 'https://www.baidu.com'
    });
});
EOF
);
        $content->row(new Code(__FILE__, 197, 234));

        $header = 'Layer弹出层';
        // 添加面包屑导航
        $content->breadcrumb(
            ['text' => $header]
        );

        return $content->header($header)->response();
    }

    /**
     * @RequestMapping()
     */
    public function panel(Content $content)
    {
        $panel = new \Swoft\Admin\Widgets\Panel();

        $faker = Factory::create();

        $panel->add('DEFAULT', $faker->text);
        $panel->add('DEFAULT', $faker->text);
        $panel->add('PRIMARY', $faker->text, false, 'primary');
        $panel->add('SUCCESS', $faker->text, false, 'success');
        $panel->add('INFO', $faker->text, false, 'info');
        $panel->add('WARNING', $faker->text, false, 'warning');

        $panel->add('代码', new Code(__FILE__, 238, 261), true, 'warning');

        $content->row($panel);

        $header = 'Panel';
        // 添加面包屑导航
        $content->breadcrumb($header);

        return $content->header($header)->response();
    }



    /**
     * @RequestMapping("/preview-terminal-code")
     */
    public function previewTerminalCode(Content $content)
    {
        $content->row(new Code(__FILE__, 267, 300));

        return $content->simple()->response();
    }

    protected function getMarkdownText()
    {
        return "
[![Latest Version](https://img.shields.io/badge/beta-v1.0.0-green.svg?maxAge=2592000)](https://github.com/swoft-cloud/swoft/releases) [![Build Status](https://travis-ci.org/swoft-cloud/swoft.svg?branch=master)](https://travis-ci.org/swoft-cloud/swoft) [![Php Version](https://img.shields.io/badge/php-%3E=7.0-brightgreen.svg?maxAge=2592000)](https://secure.php.net/) [![Swoole Version](https://img.shields.io/badge/swoole-%3E=2.1.3-brightgreen.svg?maxAge=2592000)](https://github.com/swoole/swoole-src)
首个基于 Swoole 原生协程的新时代 PHP 高性能协程全栈组件化框架，内置协程网络服务器及常用的协程客户端，常驻内存，不依赖传统的 PHP-FPM，全异步非阻塞 IO 实现，以类似于同步客户端的写法实现异步客户端的使用，没有复杂的异步回调，没有繁琐的 yield，有类似 Go 语言的协程、灵活的注解、强大的全局依赖注入容器、完善的服务治理、灵活强大的 AOP、标准的 PSR 规范实现等等，可以用于构建高性能的Web系统、API、中间件、基础服务等等。
- 基于 Swoole 扩展
- 内置协程 HTTP, TCP, WebSocket 网络服务器
- 灵活完善的注解功能
- 全局的依赖注入容器
### HTTP Server启动
> 是否同时启动RPC服务器取决于.env文件配置

```bash
// 启动服务，根据 .env 配置决定是否是守护进程
php bin/swoft start

// 守护进程启动，覆盖 .env 守护进程(DAEMONIZE)的配置
php bin/swoft start -d

```

```java
char i = '我是java';
```
";
    }

    protected function buttons()
    {
        return "
                <p> <a class='btn btn-default'>btn-default</a></p>
                <p> 
                    <div class='btn-group default'>
                        <a class='btn btn-default'>btn-group</a>
                        <a class='btn btn-default'>default</a>
                    </div>
                </p>
                <br>
                <p>
                <a class='btn btn-primary'> btn-primary </a>&nbsp;&nbsp;
                <a class='btn btn-info'> btn-info </a>&nbsp;
                <a class='btn btn-custom'> btn-custom </a>&nbsp;&nbsp;
                 <a class='btn btn-success'> btn-success </a>&nbsp;&nbsp;
                  <a class='btn btn-warning'> btn-warning </a>&nbsp;&nbsp;
                <a class='btn btn-danger'> btn-danger </a>&nbsp;&nbsp;
                <a class='btn btn-purple'> btn-purple </a>&nbsp;&nbsp;
                  <a class='btn btn-tear'> btn-tear </a>&nbsp;&nbsp;
                <a class='btn btn-pink'> btn-pink </a>&nbsp;&nbsp;
                <a class='btn btn-inverse'> btn-inverse </a>&nbsp;&nbsp;
                </p><br><p>
                <a class='btn btn-primary btn-trans'>btn-primary</a>&nbsp;&nbsp;
                <a class='btn btn-info btn-trans'>btn-info</a>&nbsp;&nbsp;
                <a class='btn btn-custom btn-trans'>btn-custom</a>&nbsp;&nbsp;
                 <a class='btn btn-success btn-trans'>btn-success</a>&nbsp;&nbsp;
                  <a class='btn btn-warning btn-trans'>btn-warning</a>&nbsp;&nbsp;
                <a class='btn btn-danger btn-trans'>btn-danger</a>&nbsp;&nbsp;
                <a class='btn btn-purple btn-trans'>btn-purple</a>&nbsp;&nbsp;
                 <a class='btn btn-tear btn-trans'> btn-tear </a>&nbsp;&nbsp;
                <a class='btn btn-pink btn-trans'>btn-pink</a>&nbsp;&nbsp;
                <a class='btn btn-inverse btn-trans'>btn-inverse</a>&nbsp;&nbsp;
               
</p>
      ";
    }
}
