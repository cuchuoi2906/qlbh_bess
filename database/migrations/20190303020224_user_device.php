<?php


use Phinx\Migration\AbstractMigration;

class UserDevice extends AbstractMigration
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

        $table = $this->table('user_device', ['id' => 'usd_id']);
        $table->addColumn('usd_user_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'User id'
        ]);
        $table->addColumn('usd_registration_id', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Device ID',
            ColumnOptions::LIMIT => 255
        ]);
        $table->addColumn('usd_device_info', ColumnTypes::TEXT, [
            ColumnOptions::COMMENT => 'Chứa thông tin thiết bị. Lưu dạng json',
            ColumnOptions::NULL => true
        ]);
        $table->addColumn('usd_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::COMMENT => 'Thời điểm tạo bản ghi',
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);
        $table->addColumn('usd_updated_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::COMMENT => 'Thời điểm sửa bản ghi',
            ColumnOptions::UPDATE => 'CURRENT_TIMESTAMP'
        ]);

        $table->addIndex(['usd_user_id', 'usd_registration_id'], ['unique' => true]);

        $table->save();
    }
}
