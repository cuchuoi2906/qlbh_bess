<?php


use Phinx\Migration\AbstractMigration;

class ChangePaymentTypeDefault extends AbstractMigration
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
        $table->changeColumn('ord_payment_type', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Loại thanh toán: COD, ATM nội địa(ATM), Visa (VISA), Shinhan bank (SHINHANBANK), ...',
            ColumnOptions::LIMIT => 100,
            ColumnOptions::DEFAULT => 'COD'
        ]);
        $table->changeColumn('ord_payment_status', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Đã thanh toán hoặc chưa. 0 = Chưa thánh toán | 1 = Đã thanh toán',
            ColumnOptions::LIMIT => 11,
            ColumnOptions::DEFAULT => 0
        ]);
        $table->update();
    }
}
