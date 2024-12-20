<?php


use Phinx\Migration\AbstractMigration;

class AlterOrderLog extends AbstractMigration
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

        $table = $this->table('order_logs');
        $table->changeColumn('orl_old_status_code', ColumnTypes::VARCHAR, [
            ColumnOptions::DEFAULT => ''
        ]);
        $table->changeColumn('orl_new_status_code', ColumnTypes::VARCHAR, [
            ColumnOptions::DEFAULT => ''
        ]);
        $table->changeColumn('orl_old_payment_status', ColumnTypes::VARCHAR, [
            ColumnOptions::DEFAULT => ''
        ]);
        $table->changeColumn('orl_new_payment_status', ColumnTypes::VARCHAR, [
            ColumnOptions::DEFAULT => ''
        ]);
        $table->changeColumn('orl_new_payment_status', ColumnTypes::VARCHAR, [
            ColumnOptions::DEFAULT => ''
        ]);
        $table->changeColumn('orl_updated_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::NULL => true
        ]);
        $table->changeColumn('orl_updated_by', ColumnTypes::INTEGER, [
            ColumnOptions::DEFAULT => 0
        ]);
        $table->update();

    }

    public function down()
    {
        parent::down(); // TODO: Change the autogenerated stub
    }
}
