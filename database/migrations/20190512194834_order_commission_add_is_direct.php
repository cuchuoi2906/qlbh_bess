<?php


use Phinx\Migration\AbstractMigration;

class OrderCommissionAddIsDirect extends AbstractMigration
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

        $table = $this->table('order_commissions');
        $table->addColumn('orc_is_direct', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Có phải là hoa hồng trực tiếp không?',
            ColumnOptions::DEFAULT => 0,
            ColumnOptions::LIMIT => 1
        ]);

        $table->update();

    }
}
