<?php


use Phinx\Migration\AbstractMigration;

class UserCartTable extends AbstractMigration
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

        $table = $this->table('user_cart', ['id' => 'usc_id']);
        $table->addColumn('usc_user_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'User id'
        ]);
        $table->addColumn('usc_product_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Product ID'
        ]);
        $table->addColumn('usc_quantity', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Số lượng'
        ]);
        $table->addColumn('usc_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);
        $table->addColumn('usc_updated_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::NULL => true,
            ColumnOptions::UPDATE => 'CURRENT_TIMESTAMP'
        ]);

        $table->save();
    }
}
