<?php


use Phinx\Migration\AbstractMigration;

class AddColumnCommissionTableProduct extends AbstractMigration
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

        $table = $this->table('products');
        $table->addColumn('pro_commission', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Tổng hoa hồng sẽ trả khi bán 1 sản phẩm, Tính theo %',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->update();
    }
}
