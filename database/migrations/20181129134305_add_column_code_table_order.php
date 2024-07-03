<?php


use Phinx\Migration\AbstractMigration;

class AddColumnCodeTableOrder extends AbstractMigration
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
        $table = $this->table('orders');
        $table->addColumn('ord_code', ColumnTypes::VARCHAR, [
            ColumnOptions::LIMIT => 255,
            ColumnOptions::AFTER => 'ord_id',
        ]);
        $table->changeColumn('ord_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP',
        ]);
        $table->changeColumn('ord_updated_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::NULL => true,
            ColumnOptions::UPDATE => 'CURRENT_TIMESTAMP',
        ]);
        $table->changeColumn('ord_deleted_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::NULL => true
        ]);
        $table->update();
    }
}
