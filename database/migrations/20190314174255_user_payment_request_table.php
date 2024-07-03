<?php


use Phinx\Migration\AbstractMigration;

class UserPaymentRequestTable extends AbstractMigration
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

        $table = $this->table('user_payment_request', ['id' => 'upr_id']);
        $table->addColumn('upr_user_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'User id',
            ColumnOptions::LIMIT => 11
        ]);
        $table->addColumn('upr_money', ColumnTypes::FLOAT, [
            ColumnOptions::COMMENT => 'Số tiền muốn rút'
        ]);
        $table->addColumn('upr_bank_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Tài khoản ngân hàng'
        ]);
        $table->addColumn('upr_is_paid', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Đã được thanh toán?',
            ColumnOptions::LIMIT => 1
        ]);
        $table->addColumn('upr_paod_time', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Thời gian trả tiền',
            ColumnOptions::LIMIT => 11,
            ColumnOptions::NULL => true,
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn('upr_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);

        $table->save();

    }
}
