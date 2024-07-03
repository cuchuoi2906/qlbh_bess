<?php


use Phinx\Migration\AbstractMigration;

class CreateTableUserMoneyLogs extends AbstractMigration
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
        $table = $this->table('user_money_logs', ['id' => 'uml_id']);
        $table->addColumn('uml_user_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'ID trong bảng user',
            ColumnOptions::LIMIT => 11
        ]);

        $table->addColumn('uml_amount', ColumnTypes::FLOAT, [
            ColumnOptions::COMMENT => 'Số tiền được cộng',
        ]);

        $table->addColumn('uml_current', ColumnTypes::FLOAT, [
            ColumnOptions::COMMENT => 'Số tiền hiện tại sau giao dịch',
        ]);

        $table->addColumn('uml_note', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Ghi rõ note của giao dịch nào.',
            ColumnOptions::LIMIT => 254
        ]);

        $table->addColumn('uml_type', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Loại Ví : Hoa Hồng, Tiền Add',
            ColumnOptions::LIMIT => 50
        ]);

        $table->addColumn('uml_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::COMMENT => 'Thời điểm tạo log',
            ColumnOptions::NULL => true,
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP',
        ]);

        $table->save();

    }
}
