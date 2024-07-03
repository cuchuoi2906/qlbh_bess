<?php


use Phinx\Migration\AbstractMigration;

class CreateTableNotificationStatus extends AbstractMigration
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

        $table = $this->table('notification_status', ['id' => 'nts_id']);
        $table->addColumn('nts_notification_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'ID trong bảng notification',
            ColumnOptions::LIMIT => 11
        ]);
        $table->addColumn('nts_user_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'ID trong bảng user',
            ColumnOptions::LIMIT => 11
        ]);
        $table->addColumn('nts_status', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Trạng thái thông báo. 0 = chưa đọc | 1 = đã đọc | -1 = đã xóa',
            ColumnOptions::LIMIT => 1,
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn('nts_read_at', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Thời điểm đọc thông báo',
            ColumnOptions::NULL => true,
        ]);
        $table->addIndex(['nts_notification_id', 'nts_user_id'], ['unique' => true]);
        $table->save();
    }
}
