<?php


use Phinx\Migration\AbstractMigration;

class AddStatusUserMoneyRequestNotify extends AbstractMigration
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

        $model = new \App\Models\MoneyAddRequestNotify();
        $table = $this->table($model->table);
        $table->addColumn($model->prefix . '_status', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Trạng thái 0 = Chờ nạp tiền| -1: Đã hủy| 1 = Đã nạp tiền',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn($model->prefix . '_admin_id', ColumnTypes::INTEGER, [
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn($model->prefix . '_note', ColumnTypes::TEXT, [
            ColumnOptions::NULL => true
        ]);

        $table->update();
    }
}
