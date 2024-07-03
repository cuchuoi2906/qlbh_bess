<?php


use Phinx\Migration\AbstractMigration;

class AlterTableUsersAddMoney extends AbstractMigration
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
        $table = $this->table('users', ['id' => 'usm_id']);

        $table->addColumn('use_commission', ColumnTypes::FLOAT, [
            ColumnOptions::COMMENT => 'Hoa Hồng Hiện Tại của user',
            ColumnOptions::LIMIT => 11
        ]);

        $table->addColumn('use_money_add', ColumnTypes::FLOAT, [
            ColumnOptions::COMMENT => 'Tiềnphp Nạp hiện Tại của user',
            ColumnOptions::LIMIT => 11
        ]);

        $table->save();
    }
}
