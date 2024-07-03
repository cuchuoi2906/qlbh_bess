<?php


use Phinx\Migration\AbstractMigration;

class MoneyAddRequestNotify extends AbstractMigration
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
    public function up()
    {

        $model = new App\Models\MoneyAddRequestNotify;

        $table = $this->table($model->table, [
            'id' => $model->prefix . '_id'
        ]);

        $table->addColumn($model->prefix . '_account_name', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Tên chủ tài khoản',
            ColumnOptions::NULL => true
        ]);
        $table->addColumn($model->prefix . '_account_number', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Số tài khoản tài khoản',
            ColumnOptions::NULL => true
        ]);
        $table->addColumn($model->prefix . '_bank_name', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Tên ngân hàng',
            ColumnOptions::NULL => true
        ]);
        $table->addColumn($model->prefix . '_trade_code', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Mã giao dịch',
            ColumnOptions::NULL => true
        ]);
        $table->addColumn($model->prefix . '_money', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Số tiền',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn($model->prefix . '_order_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Mã đơn hàng',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn($model->prefix . '_image', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Ảnh giao dịch',
            ColumnOptions::NULL => true
        ]);
        $table->addColumn($model->prefix . '_type', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Loại yêu cầu. 0 = Yêu cầu nạp tiền, 1 = yêu cầu thanh toán cho đơn hàng',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn($model->prefix . '_user_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'User',
        ]);

        timestamp_fields($table, $model->prefix);

        $table->save();
    }

    public function down()
    {
        $model = new App\Models\MoneyAddRequestNotify;

        $table = $this->table($model->table);
        $table->drop();
    }
}
