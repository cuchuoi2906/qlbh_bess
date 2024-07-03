<?php


use Phinx\Migration\AbstractMigration;

class CreateOrderTable extends AbstractMigration
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
        $table = $this->table('orders', ['id' => 'ord_id']);
        $table->addColumn('ord_user_id', ColumnTypes::INTEGER)
            ->addColumn('ord_status_code', ColumnTypes::VARCHAR, [
                'comment' => 'Trạng thái đơn hàng bảo gồm: Mới tạo - Đang vận chuyển - Đã nhận hàng - Thành công. Có thể 
                làm 1 bảng order_status để lưu các trạng thái.'
            ])
            ->addColumn('ord_payment_type', ColumnTypes::VARCHAR, [
                'comment' => 'Loại thanh toán: COD, ATM nội địa, Visa, Shinhan bank, ...'
            ])
            ->addColumn('ord_payment_status', ColumnTypes::VARCHAR, [
                'comment' => 'Trạng thái thanh toán: Chưa thanh toán - Đã thanh toán - Tạm giữ - Yêu cầu hoàn tiền - ...'
            ])
            ->addColumn('ord_amount', ColumnTypes::FLOAT, [
                'comment' => 'Tổng tiền đơn hàng khách hàng phải trả'
            ])
            /**
             * Thông tin người nhận hàng
             */
            ->addColumn('ord_ship_name', ColumnTypes::VARCHAR)
            ->addColumn('ord_ship_address', ColumnTypes::VARCHAR)
            ->addColumn('ord_ship_phone', ColumnTypes::VARCHAR)
            ->addColumn('ord_ship_email', ColumnTypes::VARCHAR)
            ->addColumn('ord_note', ColumnTypes::TEXT)
            ->addColumn('ord_created_at', ColumnTypes::DATETIME)
            ->addColumn('ord_updated_at', ColumnTypes::DATETIME)
            ->addColumn('ord_deleted_at', ColumnTypes::DATETIME)
            ->save();

        $table = $this->table('order_logs', ['id' => 'orl_id']);
        $table->addColumn('orl_ord_id', ColumnTypes::INTEGER)
            ->addColumn('orl_old_status_code', ColumnTypes::VARCHAR)
            ->addColumn('orl_new_status_code', ColumnTypes::VARCHAR)
            ->addColumn('orl_old_payment_status', ColumnTypes::VARCHAR)
            ->addColumn('orl_new_payment_status', ColumnTypes::VARCHAR)
            ->addColumn('orl_updated_at', ColumnTypes::DATETIME)
            ->addColumn('orl_updated_by', ColumnTypes::INTEGER, [
                'comment' => 'Lưu admin_id đã thay đổi trạng thái đơn hàng'
            ])
            ->save();

        $table = $this->table('order_products', ['id' => 'orp_id']);
        $table->addColumn('orp_ord_id', ColumnTypes::INTEGER)
            ->addColumn('orp_product_id', ColumnTypes::INTEGER)
            ->addColumn('orp_quantity', ColumnTypes::INTEGER)
            ->addColumn('orp_price', ColumnTypes::FLOAT)
            ->addColumn('orp_sale_price', ColumnTypes::FLOAT)
            ->save();
    }

    public function down()
    {
        if ($this->hasTable('orders')) {
            $this->dropTable('orders');
        }

        if ($this->hasTable('order_logs')) {
            $this->dropTable('order_logs');
        }

        if ($this->hasTable('order_products')) {
            $this->dropTable('order_products');
        }

    }

}
