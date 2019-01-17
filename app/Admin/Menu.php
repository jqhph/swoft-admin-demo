<?php

namespace App\Admin;

use Swoft\Admin\AbstractMenu;
use Swoft\Admin\Bean\Annotation\AdminMenu;
use Swoft\Admin\Menu\Models\AdminMenu as MenuEntity;
use Swoft\Db\Query;

/**
 * @AdminMenu()
 */
class Menu extends AbstractMenu
{
    /**
     * 返回菜单节点
     *
     * @return array
     */
    public function fetch(): array
    {
        return Query::table(MenuEntity::class)->get()->getResult();
    }

}
