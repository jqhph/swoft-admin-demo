<?php

namespace App\Controllers;

use Swoft\Admin\Admin;
use Swoft\Admin\Layout\Column;
use Swoft\Admin\Layout\Row;
use Swoft\Admin\Widgets\Card;
use Swoft\Admin\Widgets\Markdown;
use Swoft\Admin\Widgets\Tab;
use Swoft\Admin\Widgets\Table;
use App\Admin\Widgets\Terminal;
use Swoft\App;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Support\SessionHelper;

/**
 * @Controller()
 */
class IndexController
{
    /**
     * @RequestMapping("/")
     */
    public function index()
    {
        return blade('admin::login', [
            'errors' => get_flash_errors(),
        ])->toResponse();
    }

    /**
     * 首页
     *
     * @RequestMapping("/admin")
     */
    public function admin()
    {
        $content = Admin::content();

        $content->header(t('Dashboard', 'admin'));
        $content->description(t('Description...', 'admin'));

        $content->row(blade('admin::dashboard.title'));

        $content->row(function (Row $row) {
            $row->column(7, $this->terminal());

            $row->column(5, function (Column $column) {
                $tab = new Tab();

                $tab->add('环境和依赖', $this->getEnv()->render().$this->getDependencies()->render());
                $tab->add('扩展', $this->getExt());

                $laravelAdmin = '<a target="_blank" href="https://github.com/z-song/laravel-admin">Laravel-admin</a>';
                $tab->add('小小说明',
                    <<<EOF
<p>
  本项目后端部分是基于{$laravelAdmin}二次开发而成, 现阶段大部分api和功能与{$laravelAdmin}一致, 这些api在后续版本中也基本不会变动, 大家可以放心使用。
后续功能更新与{$laravelAdmin}可能会有较大差异, 欢迎大家提出建议和PR。最后祝大家早日出任CEO, 迎娶白富美, 走上人生巅峰!
 </p>

EOF
                );

                $column->append($tab);
            });

        });

        admin_debug("庭前花木满");
        admin_debug("院外小径芳");
        admin_debug("四时常相往");
        admin_debug("晴日共剪窗");

        admin_debug('Config', config('admin'));

        return $content->response();
    }

    protected function getExt()
    {
        $ext = [];

        return '<div style="margin:5px 0 0 10px;"><span class="help-block" style="margin-bottom:0"><i class="fa fa-info-circle"></i>&nbsp;什么都没有~</span></div>';
    }

    protected function getEnv()
    {
        $env = [
            'PHP' => PHP_VERSION,
            'Swoole' => SWOOLE_VERSION,
            'Swoft' => App::version(),
            'Locale' => current_lang(),
            'Session'  => SessionHelper::wrap() ? 'on' : 'off',
        ];

        foreach ($env as $k => &$version) {
            $version = "<span class='label label-primary'>$version</span>";
        }

        return (new Table(['环境'], $env))->class('table table-striped');
    }

    protected function getDependencies()
    {
        $dependencies = [
            'php' => '>=7.0',
            'ext-swoole' => '>=2.1',
            'lldca/swoft-blade' => '0.1.0',
            'lldca/swoft-migration' => '0.1.0',
            'league/flysystem'  => '^1.1',
            'symfony/debug' => '^4.1',
            'symfony/var-dumper' => '^4.1'
        ];


        foreach ($dependencies as $k => &$version) {
            $version = "<span class='label label-primary'>$version</span>";
        }


        return (new Table(['依赖'], $dependencies))->class('table table-striped');
    }

    /**
     * 命令窗构建
     *
     * @return Terminal
     */
    protected function terminal()
    {
        $terminal = new Terminal();

        $welcome = [
            ['content' => '欢迎使用 Swoft Admin (´•灬•‘)'],
        ];

        $terminal->message(array_merge($welcome, $this->getDescription()));

        $terminal->command(
            'composer',
            '项目依赖包',
            [
                ['content' => '我不相信命运？']
            ]
        );
        $terminal->command(
            'extension',
            'admin扩展',
            [
                ['content' => '暂无', 'style' => 'info']
            ]
        );

        return $terminal;
    }

    protected function getDescription()
    {
        return [
            ['content' => '本项目是一个后台系统构建工具, 只需要极少的代码即可实现一个功能完善的后台系统, 使用简单且灵活可扩展。', 'style' => 'success', 'label' => '简介'],
            ['content' => [
                'title' => '',
                'list' =>
                    [
                        '<span class="red">Admin::grid</span> 支持快速构建数据表格, 支持双表头数据表格',
                        '<span class="red">Admin::form</span> 支持快速构建数据表单, 支持表单自定义布局',
                        '支持<span class="red">代码生成器</span>快速生成<span class="red">CURD</span>代码、语言包、数据库迁移文件、SWOFT实体等',
                        '支持<code>Blade</code>模板引擎, 支持使用路径别名引入静态资源',
                        '内置丰富的页面元素组件',
                        '支持扩展组件, 支持插件机制',
                        '支持数据库版本迁移管理(<span class="red">phinx</span>)',
                        '支持<span class="red">pjax(按需加载)</span>以及<span class="red">RWD-Table-Patterns</span>',
                        '支持文件上传, 支持<span class="red">league/flysystem</span>',
                    ]
                ],
                'style' => 'primary',
                'label' => '特性'
            ],

        ];

    }

}
