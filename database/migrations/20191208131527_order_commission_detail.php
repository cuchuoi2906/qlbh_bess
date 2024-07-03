<?php


use Phinx\Migration\AbstractMigration;

class OrderCommissionDetail extends AbstractMigration
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

        $table = $this->table('order_product_commission', ['id' => 'opc_id']);
        $table->addColumn('opc_order_id', ColumnTypes::INTEGER);
        $table->addColumn('opc_product_id', ColumnTypes::INTEGER);
        $table->addColumn('opc_user_id', ColumnTypes::INTEGER);
        $table->addColumn('opc_quantity', ColumnTypes::INTEGER);
        $table->addColumn('opc_commission', ColumnTypes::INTEGER);
        $table->addColumn('opc_is_owner', ColumnTypes::INTEGER, [
            ColumnOptions::DEFAULT => 0,
            ColumnOptions::COMMENT => 'Hoa hồng người đặt hàng hưởng'
        ]);
        $table->addColumn('opc_is_direct', ColumnTypes::INTEGER, [
            ColumnOptions::DEFAULT => 0,
            ColumnOptions::COMMENT => 'Hoa hồng trực tiếp từ chiết khấu sản phẩm'
        ]);
        $table->addColumn('opc_type', ColumnTypes::INTEGER, [
            ColumnOptions::DEFAULT => 0,
            ColumnOptions::COMMENT => 'Loại hoa hồng. > 0 thì chỉ dùng để lên cấp'
        ]);

        $table->save();
    }
}
