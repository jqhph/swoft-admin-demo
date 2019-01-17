<?php

namespace App\Admin\Displayers;

use Swoft\Admin\Bean\Annotation\AdminDisplayer;
use Swoft\Admin\Grid\Displayers\AbstractDisplayer;

/**
 * 自定义displayer演示
 *
 * @AdminDisplayer()
 */
class Color extends AbstractDisplayer
{
    public function display(string $color = null)
    {
        $color = $color ?: $this->value;

        return "<span style='color:{$color}'>{$this->value}</span>";
    }
}
