<?php

namespace App\Controllers\Admin;

use Swoft\Admin\Layout\Content;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;

/**
 * @Controller("/admin/exception")
 */
class ExceptionController
{
    /**
     * @RequestMapping("/admin/exception")
     */
    public function index(Content $content)
    {
        $text = '
            Swoole项目排查错误比普通php程序要不便一些
            为此系统系统集成了 filp/whoops 
            以便用户查看错误追踪信息更简单
         ';
        echo $text1;

    }
}
