<?php


use Phinx\Migration\AbstractMigration;

class UserMoneyLogFromColumn extends AbstractMigration
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

        $model = new \App\Models\UserMoneyLog;
        $table = $this->table($model->table);
        $table->addColumn($model->prefix . '_source', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Nguồn nạp tiền. 0: admin nạp | 1: Nạp từ ngân hàng | 2: Chuyển tiền từ ví hoa hồng',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn($model->prefix . '_source_type', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => '1: ĐỐi tượng đơn hàng | 2: Đối tượng người dùng | 0: Đối tượng mặc định',
            ColumnOptions::DEFAULT => 0
        ]);

        $table->update();
    }
}
