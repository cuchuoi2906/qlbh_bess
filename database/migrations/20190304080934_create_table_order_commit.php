<?php


use Phinx\Migration\AbstractMigration;

class CreateTableOrderCommit extends AbstractMigration
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
        $table = $this->table('order_commissions',['id' => 'orc_id']);
        $table->addColumn('orc_order_id',ColumnTypes::INTEGER);
        $table->addColumn('orc_user_id',ColumnTypes::INTEGER)
            ->addColumn('orc_status_code', ColumnTypes::VARCHAR, [
                'comment' => 'Trạng thái commit: Mới tạo - Đã chuyển tiền thành công - Có lỗi - Thành công.'
            ])
            ->addColumn('orc_amount', ColumnTypes::FLOAT, [
                'comment' => 'Hoa hồng chuyển cho khách'
            ])
            ->addColumn('orc_created_at', ColumnTypes::TIMESTAMP, [
                ColumnOptions::COMMENT => 'Thời điểm tạo commission',
                ColumnOptions::NULL => true,
                ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('orc_updated_at', ColumnTypes::TIMESTAMP,[
                ColumnOptions::COMMENT => 'Thời điểm sủa update commission',
                ColumnOptions::NULL => true,
                ColumnOptions::UPDATE => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('orc_deleted_at', ColumnTypes::TIMESTAMP, [
                ColumnOptions::COMMENT => 'Thời điểm xóa commission',
                ColumnOptions::NULL => true,
            ]);
        $table->save();
    }
}
