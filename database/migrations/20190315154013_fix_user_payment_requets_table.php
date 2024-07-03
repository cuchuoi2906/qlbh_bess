<?php


use Phinx\Migration\AbstractMigration;

class FixUserPaymentRequetsTable extends AbstractMigration
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

        $table = $this->table('user_payment_request');
        $table->renameColumn('upr_paod_time', 'upr_paid_time');
        $table->addColumn('upr_note', ColumnTypes::TEXT, [
            ColumnOptions::COMMENT => 'Lưu ý'
        ]);
        $table->addColumn('upr_admin_accept', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Admin accept payment request',
            ColumnOptions::NULL => true
        ]);
        $table->addColumn('upr_ip', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'IP admin',
            ColumnOptions::NULL => true,
            ColumnOptions::SIGNED => false,
        ]);

        $table->update();

    }
}
