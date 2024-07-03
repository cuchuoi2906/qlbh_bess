<?php


use Phinx\Migration\AbstractMigration;

class UserChangePhone extends AbstractMigration
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

        $model = new \App\Models\Users\UserChangePhone();
        $prefix = $model->prefix;

        $table = $this->table($model->table, ['id' => $prefix . '_id']);
        $table->addColumn($prefix . '_user_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'User id'
        ]);
        $table->addColumn($prefix . '_old_phone', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Số điện thoại cũ',
            ColumnOptions::LIMIT => 20,
            ColumnOptions::NULL => true
        ]);
        $table->addColumn($prefix . '_new_phone', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Số điện thoại mới',
            ColumnOptions::LIMIT => 20,
        ]);
        $table->addColumn($prefix . '_old_code', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Mã xác thực gửi về số điện thoại cũ',
            ColumnOptions::LIMIT => 6,
            ColumnOptions::NULL => true
        ]);
        $table->addColumn($prefix . '_new_code', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Mã xác thực gửi về số điện thoại mới',
            ColumnOptions::LIMIT => 6,
        ]);
        $table->addColumn($prefix . '_can_confirm_new_phone', ColumnTypes::INTEGER, [
            ColumnOptions::LIMIT => 1,
            ColumnOptions::DEFAULT => 1,
            ColumnOptions::COMMENT => 'Có thể xác nhận số đt mới chưa. Nếu là tk có số đt cũ rồi thì phải xác thực số đt cũ trước'
        ]);

        timestamp_fields($table, $prefix);

        $table->addIndex($prefix . '_user_id', [
            'unique' => true
        ]);

        $table->save();

    }
}
