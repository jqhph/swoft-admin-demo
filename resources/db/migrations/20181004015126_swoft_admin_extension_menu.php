<?php


use Swoft\Migrations\Migrator;
use Swoft\Migrations\Database\TableProxy;
use Phinx\Db\Table as PhinxTable;

class SwoftAdminExtensionMenu extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->tableProxy('admin_menu', function (TableProxy $table) {
            $table->integer('id')->autoincrement()->unsigned();
            $table->string('title')->comment('菜单标题');
            $table->string('icon')->comment('菜单图标');
            $table->string('path')->comment('菜单链接');
            $table->integer('priority')->tiny()->default(0)->comment('值越小排序越靠前');
            $table->integer('parent_id')->unsigned()->default(0)->comment('父级id');
            $table->integer('useprefix')->tiny()->unsigned()->default(1)->comment('使用路由前缀');
            $table->integer('auth_id')->unsigned()->default(0)->comment('菜单权限id(预留)');
            $table->integer('newpage')->tiny()->unsigned()->default(0)->comment('是否强制跳转新的页面');
            $table->timestamp('created_at')->null()->default(null);
            $table->timestamp('updated_at')->null()->default(null)->comment('更新时间');

            $table->setPrimaryKey('id')
                ->innodb()
                ->create();
        });
    }
}
