<?php


use Phinx\Migration\AbstractMigration;

class CreateTableMoneyAddRequest extends AbstractMigration
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
        $table = $this->table('user_money_add_request', ['id' => 'umar_id']);
        $table->addColumn('umar_user_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'User id'
        ]);


        $table->addColumn('umar_amount', ColumnTypes::FLOAT, [
            ColumnOptions::COMMENT => 'Số tiền nạp',
            ColumnOptions::DEFAULT => 0
        ]);

        $table->addColumn('umar_order_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Order id cần thanh toán',
            ColumnOptions::DEFAULT => 0
        ]);

        $table->addColumn('umar_type', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Phân biệt loại thanh toán',
        ]);

        $table->addColumn('umar_transaction_id', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Mã giao dịch cổng thanh toán trả về',
            ColumnOptions::DEFAULT => 'NEW',
        ]);

        $table->addColumn('umar_status', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'trạng thái',
            ColumnOptions::DEFAULT => null,
            ColumnOptions::LENGTH => 30,
        ]);

        $table->addColumn('umar_note', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Ghi chú đầy đủ về giao dịch',
            ColumnOptions::DEFAULT => '',
            ColumnOptions::LENGTH => 254,
        ]);

        $table->addColumn('umar_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);

        $table->addColumn('umar_updated_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);

        $table->save();


    }
}
