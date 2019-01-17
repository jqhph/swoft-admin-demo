<?php

namespace App\Admin\Widgets;

use Swoft\Admin\Admin;
use Swoft\Admin\Widgets\Widget;
use Swoft\Support\Contracts\Renderable;

/**
 * @see https://github.com/jqhph/jquery-terminal-emulator
 *
 * @method $this info(string $content, string $label = '')
 * @method $this primary(string $content, string $label = '')
 * @method $this success(string $content, string $label = '')
 * @method $this purple(string $content, string $label = '')
 * @method $this warning(string $content, string $label = '')
 * @method $this error(string $content, string $label = '')
 * @method $this system(string $content, string $label = '')
 */
class Terminal extends Widget implements Renderable
{
    const PRIMARY = 'primary';
    const INFO = 'info';
    const SUCCESS = 'success';
    const PURPLE = 'purple';
    const WARNING = 'warning';
    const ERROR = 'error';
    const SYSTEM = 'system';

    /**
     * @var string
     */
    protected $selector = 'terminal-container';

    /**
     * @var array
     */
    protected $scripts = [];

    /**
     * @var array
     */
    protected $options = [
        'title' => 'Swoft admin terminal',
        'height' => '550px',
        'messages' => [],
        'commands' => [],
    ];

    public function __construct(string $title = '')
    {
        if ($title) $this->title($title);

        Admin::js('@admin/jquery-terminal-emulator/src/lxh-terminal.min.js');
        Admin::css('@admin/jquery-terminal-emulator/src/lxh-terminal.min.css');
    }

    /**
     * 增加自定义命令
     *
     * @param string $cmd
     * @param string $description
     * @param $handler
     * @return $this
     */
    public function command(string $cmd, string $description, $handler)
    {
        $this->options['commands'][$cmd] = [
            'description' => $description,
            'handle' => $handler
        ];

        return $this;
    }

    /**
     * 增加自定义命令
     *
     * @param string $cmd
     * @param string $description
     * @param string $handler
     * @return Terminal
     */
    public function commandScript(string $cmd, string $description, string $handler)
    {
        $this->options['commands'][$cmd] = [
            'description' => $description,
            'eval' => $handler
        ];
        $description = str_replace("'", "\\'", $description);

        $this->scripts[] = "_terminal.command('$cmd', '$description', $handler)";
        return $this;
    }

    /**
     * 设置需要输出的内容
     *
     * [
     *      ['content' => '啦啦啦', 'style' => 'primary', 'label' => '测试'],
     *      ['content' => ['title' => 'language', 'list' => ['Python', 'Java', 'PHP', 'C']], 'style' => 'primary', 'label' => 'info'],
     * ]
     *
     * @param string|array $content
     * @param string $style
     * @param string $label
     * @return $this
     */
    public function message($content, string $style = '', string $label = null)
    {
        if (is_array($content)) {
            $this->options['messages'] = array_merge($this->options['messages'], $content);
            return $this;
        }
        $this->options['messages'][] = [
            'content' => $content, 'style' => $style, 'label' => $label ?: $style
        ];
        return $this;
    }

    /**
     * 命令窗标题
     *
     * @param string $title
     * @return $this
     */
    public function title(string $title)
    {
        $this->options['title'] = $title;
        return $this;
    }

    /**
     * 设置命令窗宽度
     *
     * @param string $width 百分比或px
     * @return $this
     */
    public function width(string $width)
    {
        $this->options['width'] = $width;
        return $this;
    }

    /**
     * 设置命令窗高度
     *
     * @param int $height
     * @return $this
     */
    public function height(int $height)
    {
        if ($height > 800) $height = 800;

        $this->options['height'] = $height.'px';
        return $this;
    }

    /**
     * 设置欢迎语之后的内容
     *
     * @param string $content
     * @param string $style
     * @return $this
     */
    public function printAfterWelcome(string $content, string $style = 'system')
    {
        if (!isset($this->options['end'])) {
            $this->options['end'] = [];
        }
        $this->options['end'][] = ['content' => &$content, 'style' => $style];

        return $this;
    }

    public function render()
    {
        $unique = uniqid();

        $this->class($this->selector.$unique);
        $this->defaultAttribute('style', 'margin-bottom:35px');

        $opt = json_encode($this->options);

        $scripts = join(';', $this->scripts);

        Admin::script("var _terminal = $('.{$this->selector}{$unique}').lxhTerminal($opt);$scripts");

        return <<<EOF
<div {$this->formatAttributes()}></div>
EOF;
    }

    public function __call($method, $parameters)
    {
        if (!in_array($method, [static::INFO, static::SUCCESS,static::ERROR,static::PURPLE,static::PRIMARY,static::SYSTEM,static::WARNING,])) {
            return parent::__call($method, $parameters);
        }
        $content = array_get($parameters, 0);

        return $this->message($content, $method, (string)array_get($parameters, 1));
    }
}