<?php


use Phinx\Migration\AbstractMigration;

class UserWalletTable extends AbstractMigration
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

        $table = $this->table('user_wallet', ['id' => 'usw_id']);
        $table->addColumn('usw_user_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'User id'
        ]);
        $table->addColumn('usw_commission', ColumnTypes::FLOAT, [
            ColumnOptions::COMMENT => 'Ví tiền hoa hồng',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn('usw_charge', ColumnTypes::FLOAT, [
            ColumnOptions::COMMENT => 'Ví tiền nạp',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn('usw_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);
        $table->addColumn('usw_updated_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::NULL => true,
            ColumnOptions::UPDATE => 'CURRENT_TIMESTAMP'
        ]);

        $table->addIndex('usw_user_id', ['unique' => true]);

        $table->save();


    }
}
