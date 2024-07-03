<?php


use Phinx\Migration\AbstractMigration;

class CommissionTypeOrderTable extends AbstractMigration
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
        $table->addColumn('ord_commission_type', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Cách nhận hoa hồng trực tiếp 1: Trừ vào giá | 2: Cộng vào ví hoa hồng',
            ColumnOptions::DEFAULT => 1
        ]);

        $table->update();

    }
}
