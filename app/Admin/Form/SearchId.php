<?php

namespace App\Admin\Form;

use Swoft\Admin\Bean\Annotation\AdminForm;
use Swoft\Admin\Form\Field;

/**
 * 自定义form字段示例
 *
 * @AdminForm()
 */
class SearchId extends Field\Text
{
    protected $view = 'app.admin.form.search-id';

    public function render()
    {
        $this->prepend('<i class="fa fa-long-arrow-up"></i>');
//        $this->attributes['style'] = 'border:1px solid #d2d6de;padding-left:5px';

        return parent::render();
    }
}
