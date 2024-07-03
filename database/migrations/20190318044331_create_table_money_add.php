<?php


use Phinx\Migration\AbstractMigration;

class CreateTableMoneyAdd extends AbstractMigration
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

        $table = $this->table('user_money_add', ['id' => 'uma_id']);
        $table->addColumn('uma_user_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'User id'
        ]);

        $table->addColumn('uma_amount', ColumnTypes::FLOAT, [
            ColumnOptions::COMMENT => 'Số tiền nạp',
            ColumnOptions::DEFAULT => 0
        ]);

        $table->addColumn('uma_type', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Loại giao dịch',
            ColumnOptions::DEFAULT => \App\Models\Users\UserMoneyAdd::TYPE_METHOD_ADMIN,
            ColumnOptions::LENGTH => 30,
        ]);

        $table->addColumn('uma_note', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Ghi chú đầy đủ về giao dịch',
            ColumnOptions::DEFAULT => 0,
            ColumnOptions::LENGTH => 254,
        ]);

        $table->addColumn('uma_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);

        $table->addColumn('uma_updated_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);

        $table->save();


    }
}
