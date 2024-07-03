<?php


use Phinx\Migration\AbstractMigration;

class CreateUserWalletLogTable extends AbstractMigration
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
    public function up()
    {
        $table = $this->table('user_wallet_log', ['id' => 'uwl_id']);
        $table->addColumn('uwl_use_id', ColumnTypes::INTEGER)
            ->addColumn('uwl_wallet_id', ColumnTypes::INTEGER)
            ->addColumn('uwl_admin_id', ColumnTypes::INTEGER)
            ->addColumn('uwl_ip', ColumnTypes::VARCHAR, [
                ColumnOptions::COMMENT => 'Ip'
            ])
            ->addColumn('uwl_money_old', ColumnTypes::FLOAT, [
                ColumnOptions::COMMENT => 'Tien cu co trong vi'
            ])
            ->addColumn('uwl_money_new', ColumnTypes::FLOAT, [
                ColumnOptions::COMMENT => 'Tien sau khi them'
            ])
            ->addColumn('uwl_money_add', ColumnTypes::FLOAT, [
                ColumnOptions::COMMENT => 'Tien duoc them vao',
                ColumnOptions::DEFAULT => 0
            ])
            ->addColumn('uwl_money_reduction', ColumnTypes::FLOAT, [
                ColumnOptions::COMMENT => 'Tien giam di',
                ColumnOptions::DEFAULT => 0
            ])
            ->addColumn('uwl_type', ColumnTypes::INTEGER, [
                ColumnOptions::COMMENT => '0:admin | 1:user',
                ColumnOptions::DEFAULT => 0
            ])
            ->addColumn('uwl_wallet_type', ColumnTypes::INTEGER, [
                ColumnOptions::COMMENT => '0:tai khoan chinh | 1: tai khoan tam giu',
                ColumnOptions::DEFAULT => 0
            ])
            ->addColumn('uwl_note', ColumnTypes::VARCHAR, [
                ColumnOptions::COMMENT => 'Nguyen nhan',
                ColumnOptions::NULL => true,
            ])
            ->addColumn('uwl_created_at', ColumnTypes::TIMESTAMP, [
                ColumnOptions::NULL    => true,
                ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
            ])
            ->addColumn('uwl_updated_at', ColumnTypes::TIMESTAMP, [
                ColumnOptions::NULL => true,
            ])
            ->save();
    }

    public function down()
    {
        if ($this->hasTable('user_wallet_log')) {
            $this->dropTable('user_wallet_log');
        }
    }
}
