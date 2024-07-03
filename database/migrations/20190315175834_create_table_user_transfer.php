<?php


use Phinx\Migration\AbstractMigration;

class CreateTableUserTransfer extends AbstractMigration
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

        $table = $this->table('user_transfer', ['id' => 'ust']);
        $table->addColumn('ust_user_id', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'User id'
        ]);
        $table->addColumn('ust_amount', ColumnTypes::FLOAT, [
            ColumnOptions::COMMENT => 'Số tiền chuyển sang ví nạp',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn('ust_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);

        $table->save();

    }
}
