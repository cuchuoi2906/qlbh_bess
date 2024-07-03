<?php


use Phinx\Migration\AbstractMigration;

class AddColumnIsOwnerTableOrderCommission extends AbstractMigration
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

        $table = $this->table('order_commissions');
        $table->addColumn('orc_is_owner', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Đơn hàng có phải do user mua hay không?',
            ColumnOptions::DEFAULT => 0
        ]);

        $table->addIndex('orc_is_owner');

        $table->update();

    }
}
