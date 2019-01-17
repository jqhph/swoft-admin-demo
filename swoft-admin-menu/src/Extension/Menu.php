<?php

namespace Swoft\Admin\Menu\Extension;

use Swoft\Admin\Admin;
use Swoft\Admin\Extension;
use Swoft\Admin\Menu\Models\AdminMenu;

class Menu extends Extension
{
    /**
     * @var string
     */
    protected static $name = 'swoft-admin-menu';

    /**
     * 请求处理之前触发
     */
    public function onBeforeRequest()
    {
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

    /**
     * 请求结束之后触发
     */
    public function onAfterRequest()
    {
    }

    /**
     * 获取数据库迁移文件目录路径
     * 如不需要,返回空值即可
     *
     * @return string 请返回绝对路径
     */
    public function migrations()
    {
        return __DIR__.'/../../resources/migrations';
    }

    /**
     * 获取语言包目录路径
     * 如不需要,返回空值即可
     *
     * @return string 请返回绝对路径
     */
    public function langs()
    {
        return __DIR__.'/../../resources/lang';
    }

    /**
     * 获取扩展静态资源文件目录路径
     * 如不需要,返回空值即可
     *
     * @return string 请返回绝对路径
     */
    public function assets()
    {

    }

    /**
     * 获取扩展模板文件目录路径
     * 如不需要,返回空值即可
     *
     * @return string 请返回绝对路径
     */
    public function views()
    {

    }
}
