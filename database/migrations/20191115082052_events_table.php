<?php


use Phinx\Migration\AbstractMigration;

class EventsTable extends AbstractMigration
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

        $table = $this->table('events', ['id' => 'evt_id']);
        $table->addColumn('evt_name', ColumnTypes::STRING, [
            ColumnOptions::COMMENT => 'Tên sự kiện'
        ]);
        $table->addColumn('evt_active', ColumnTypes::INTEGER, [
            ColumnOptions::LIMIT => 1,
            ColumnOptions::DEFAULT => 1
        ]);
        $table->addColumn('evt_note', ColumnTypes::TEXT, [
            ColumnOptions::COMMENT => 'Ghi chú cho sự kiện'
        ]);
        $table->addColumn('evt_start_time', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Thời gian bắt đầu',
            ColumnOptions::LIMIT => 11,
        ]);
        $table->addColumn('evt_end_time', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Thời gian kết thúc',
            ColumnOptions::LIMIT => 11,
            ColumnOptions::NULL => true
        ]);
        $table->addColumn('evt_direct_commission_ratio', ColumnTypes::FLOAT, [
            ColumnOptions::DEFAULT => 1,
            ColumnOptions::COMMENT => 'Tỷ lệ hoa hồng trực tiếp'
        ]);
        $table->addColumn('evt_parent_commission_ratio', ColumnTypes::FLOAT, [
            ColumnOptions::DEFAULT => 1,
            ColumnOptions::COMMENT => 'Tỷ lệ hoa hồng cho cấp trên'
        ]);

        $table->addColumn('evt_admin_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Admin tạo chiến dịch',
            ColumnOptions::DEFAULT => 0
        ]);

        $table->addColumn('evt_created_at', ColumnTypes::TIMESTAMP, ColumnOptions::CREATED_AT_OPTIONS);
        $table->addColumn('evt_updated_at', ColumnTypes::TIMESTAMP, ColumnOptions::UPDATED_AT_OPTIONS);
        $table->addColumn('evt_deleted_at', ColumnTypes::TIMESTAMP, ColumnOptions::DELETED_AT_OPTIONS);

        $table->save();

    }
}
