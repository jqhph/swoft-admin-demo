<?php

namespace App\Controllers\Admin;

use Swoft\Admin\Layout\Column;
use Swoft\Admin\Layout\Content;
use Swoft\Admin\Layout\Row;
use Swoft\Admin\Widgets\Alert;
use Swoft\Admin\Widgets\Card;
use Swoft\Admin\Widgets\Code;
use Swoft\Admin\Widgets\Dump;
use Swoft\Admin\Widgets\Tab;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;

/**
 * @Controller("/admin/layout")
 */
class LayoutController
{
    /**
     * @RequestMapping("/admin/layout")
     */
    public function layout(Content $content)
    {
        $header = '布局';
        // 添加面包屑导航
        $content->breadcrumb(
            ['text' => $header]
        );
        $tab = new Tab();

        $tab->add('说明',  $this->desc());
        $tab->add('代码', new Code(__FILE__, 24, 86));

        $content->row($tab);

        // 布局示例
        $this->build($content);

        return $content->header($header)->response();
    }

    /**
     * 布局示例
     *
     * @param Content $content
     */
    protected function build(Content $content)
    {
        // 单行单列
        $content->row($this->card('col-md-12', '#81C784'));

        $content->row($this->br());
        // 一行多列
        $content->row(function (Row $row) {
            $row->column(4, $this->card('col-md-4', '#7986CB'));
            $row->column(4, $this->card('col-md-4', '#7986CB'));
            $row->column(4, $this->card('col-md-4', '#7986CB'));
        });

        $content->row($this->br());
        // 行里面有多个列,列里面再嵌套行
        $content->row(function (Row $row) {
            $row->column(9, function (Column $column) {
                // 一列多行
                $column->row($this->card(['col-md-12', 20], '#4DB6AC'));
                // 行里面再嵌套列
                $column->row(function (Row $row) {
                    $row->column(4, $this->card(['col-md-4', 30], '#80CBC4'));
                    $row->column(4, $this->card(['col-md-4', 30], '#4DB6AC'));
                    $row->column(4, function (Column $column) {
                        $column->row(function (Row $row) {
                            $row->column(6, $this->card(['col-md-6', 30], '#26A69A'));
                            $row->column(6, $this->card(['col-md-6', 30], '#26A69A'));
                        });
                    });
                });
            });

            $row->column(3, $this->card(['col-md-3', 95], '#00897B'));
        });

        $content->row('<hr>');
    }

    /**
     * @param $text
     * @param int $height
     * @return string
     */
    protected function p($text, $height = 80)
    {
        return "<p style='height:{$height}px;color:#fff'><span>$text</span></p>";
    }

    /**
     * @return string
     */
    protected function br()
    {
        return "<hr>";
    }

    /**
     * @param $text
     * @param string $color
     * @return string
     */
    protected function card($text, $color = '#fff')
    {
        $text = $this->p(...(is_string($text) ? [$text] : $text));

        return <<<EOF
<div style="background:$color;padding:10px 22px 16px;box-shadow:0 1px 3px 1px rgba(34, 25, 25, 0.1);margin-bottom:8px;">
$text
</div>
EOF;

    }

    /**
     * @return string
     */
    protected function desc()
    {
        $dump = new Dump(
            <<<EOF
<code>swoft-admin</code>使用<code>bootstrap</code>的栅格系统进行布局，每行分可以为<code>12</code>个栅格(列), 每个栅格(列)也可以分为多个行

单行单列对应下面绿色区块内容
----------------------------------
| col-md-12                      |
|                                |
----------------------------------

单行三列对应下面蓝紫色区块内容
-------------------------------------
| col-md-4  | col-md-4  | col-md-4  |
|           |           |           |
-------------------------------------

最复杂这个对应最下面水鸭色区块内容
---------------------------------------------------------------------------
| col-md-12                                               | col-md-3      |
|                                                         |               |
|---------------------------------------------------------|               |
| col-md-4        | col-md-4      | col-md-6  | col-md-6  |               |
|                 |               |           |           |               |
---------------------------------------------------------------------------
{                ------> col-md-9 <------                }{--> col-md-3 <--}
 
EOF
        );

        return $dump;

    }

}
