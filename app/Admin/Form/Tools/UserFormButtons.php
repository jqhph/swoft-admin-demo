<?php

namespace App\Admin\Form\Tools;

use Swoft\Admin\Admin;
use Swoft\Support\Contracts\Renderable;
use Swoft\Support\Url;

/**
 * form工具按钮demo
 *
 */
class UserFormButtons implements Renderable
{
    public function render()
    {
        // 预览代码
        $label = t('Code', 'admin');
        // 切换布局风格 switch_layout
        $url = Url::full(['switch_layout' => !http_get('switch_layout')]);

        $this->setupScript($label);

        return "
            <div class='btn-group default' style='margin-right:10px'>
                <a id='form-preview-code' class='btn btn-sm btn-default'>$label</a>
                <a href='$url' class='btn btn-sm btn-default'>切换布局风格</a>
            </div>
        ";
    }

    protected function setupScript($label)
    {
        Admin::script(
            <<<EOF
$('#form-preview-code').click(function () {
     layer.open({
        type: 2,
        title: '$label',
        shadeClose: true,
        shade: false,
        area: ['70%', '80%'],
        content: '/admin/users/preview-form-code'
    });
});
EOF
        );
    }
}
