<?php


use Phinx\Migration\AbstractMigration;

class UserBanksTable extends AbstractMigration
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

        $table = $this->table('user_banks', ['id' => 'usb_id']);
        $table->addColumn('usb_user_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'User id'
        ]);
        $table->addColumn('usb_bank_name', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Tên ngân hàng',
            ColumnOptions::LIMIT => 255
        ]);
        $table->addColumn('usb_account_name', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Tên chủ tài khoản',
            ColumnOptions::LIMIT => 100
        ]);
        $table->addColumn('usb_account_number', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Số tài khoản',
            ColumnOptions::LIMIT => 50
        ]);
        $table->addColumn('usb_branch', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Chi nhánh',
            ColumnOptions::LIMIT => 255
        ]);
        $table->addColumn('usb_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);
        $table->addColumn('usb_updated_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::NULL => true,
            ColumnOptions::UPDATE => 'CURRENT_TIMESTAMP'
        ]);

        $table->save();

    }
}
