<?php

namespace Swoft\Admin\Menu\Repositories;

use Swoft\Admin\Bean\Annotation\AdminRepository;
use Swoft\Admin\Repository\AbstractRepository;
use Swoft\Admin\Menu\Controllers\MenuController;
use Swoft\Admin\Menu\Models\AdminMenu;

/**
 * @AdminRepository(MenuController::class)
 */
class Menu extends AbstractRepository
{
    /**
     * @var string
     */
    protected $entityClass = AdminMenu::class;

}
