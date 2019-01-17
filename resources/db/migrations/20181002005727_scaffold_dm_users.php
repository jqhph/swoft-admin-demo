<?php


use Swoft\Migrations\Migrator;
use Swoft\Migrations\Database\TableProxy;
use Phinx\Db\Table as PhinxTable;

class ScaffoldDmUsers extends Migrator
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
        $this->tableProxy('dm_users', function (TableProxy $table) {
            $table->integer('id')->autoincrement()->comment('主键');
            $table->string('name');
            $table->string('email');
            $table->string('avatar');
            $table->string('password');
            $table->timestamp('created_at')->null()->default(null);
            $table->timestamp('updated_at')->null()->default(null)->comment('更新时间');



            $table->unsigned()
                ->setPrimaryKey('id')
                ->innodb()
                ->create();
        });
    }
}
