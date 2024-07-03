<?php


use Phinx\Migration\AbstractMigration;

class CreateTableNotification extends AbstractMigration
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

        $table = $this->table('notification', ['id' => 'not_id']);
        $table->addColumn('not_title', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Tiêu đề notification',
            ColumnOptions::LIMIT => 255
        ]);
        $table->addColumn('not_content', ColumnTypes::TEXT, [
            ColumnOptions::COMMENT => 'Nội dung notification'
        ]);
        $table->addColumn('not_type', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Loại thông báo. SYSTEM | COMMISSION | ....',
            ColumnOptions::LIMIT => 50
        ]);
        $table->addColumn('not_is_send_all', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Có phải gửi cho tất cả user hay không?',
            ColumnOptions::LIMIT => 1,
            ColumnOptions::DEFAULT => 0,
        ]);
        $table->addColumn('not_admin_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Admin tạo thông báo. 0 = hệ thống',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn('not_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::COMMENT => 'Thời điểm tạo thông báo',
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);
        $table->addColumn('not_update_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::COMMENT => 'Thời điểm sửa thông báo',
            ColumnOptions::NULL => true,
            ColumnOptions::UPDATE => 'CURRENT_TIMESTAMP'
        ]);
        $table->addIndex('not_is_send_all');
        $table->save();
    }
}
