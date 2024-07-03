<?php


use Phinx\Migration\AbstractMigration;

class OrderCommissionType extends AbstractMigration
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
        $table->addColumn('orc_type', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Loại hoa hồng. 0: Tính tiền 1: Không tính tiền',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->update();
    }
}
