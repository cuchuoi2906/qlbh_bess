<?php


use Phinx\Migration\AbstractMigration;

class ProductPricePolicy extends AbstractMigration
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

        $table = $this->table('product_price_policies', ['id' => 'ppp_id']);
        $table->addColumn('ppp_product_id', ColumnTypes::INTEGER);
        $table->addColumn('ppp_quantity', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Ngưỡng số lượng sản phẩm',
            ColumnOptions::DEFAULT => 1
        ]);
        $table->addColumn('ppp_price', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Giá sản phẩm khi đạt ngưỡng',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn('ppp_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);
        $table->addColumn('ppp_updated_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::NULL => true,
            ColumnOptions::UPDATE => 'CURRENT_TIMESTAMP'
        ]);

        $table->save();
    }
}
