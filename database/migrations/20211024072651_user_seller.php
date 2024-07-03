<?php


use Phinx\Migration\AbstractMigration;

class UserSeller extends AbstractMigration
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

        $table = $this->table('users');
        $table->addColumn('use_is_seller', ColumnTypes::INTEGER, [
            ColumnOptions::LIMIT => 1,
            ColumnOptions::DEFAULT => 0,
            ColumnOptions::COMMENT => 'User mua hàng với mức chiết khấu cao nhất'
        ]);

        $table->update();

    }
}
