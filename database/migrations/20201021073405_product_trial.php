<?php


use Phinx\Migration\AbstractMigration;

class ProductTrial extends AbstractMigration
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
        $table->addColumn('pro_is_trial', ColumnTypes::INTEGER, [
            ColumnOptions::LIMIT => 1,
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn('pro_price_cost', ColumnTypes::INTEGER, [
            ColumnOptions::LIMIT => 11,
            ColumnOptions::DEFAULT => 0,
            ColumnOptions::COMMENT => 'Giá nhập'
        ]);

        $table->update();
    }
}
