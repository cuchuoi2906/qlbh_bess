<?php


use Phinx\Migration\AbstractMigration;

class CommissionPlan extends AbstractMigration
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

        $table = $this->table('commission_plan', ['id' => 'cpl_id']);

        $table->addColumn('cpl_plan_name', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Tên của chính sách hoa hồng'
        ]);

        $table->addColumn('cpl_commission_percent', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Phần trăm hoa hồng trực tiếp'
        ]);
        $table->addColumn('cpl_start_time', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Thời gian bắt đầu của chính sách hoa hồng',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn('cpl_end_time', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Thời gian kết thúc',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn('cpl_active', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Trạng thái',
            ColumnOptions::LIMIT => 1,
            ColumnOptions::DEFAULT => 1
        ]);
        $table->addColumn('cpl_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);
        $table->addColumn('cpl_updated_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::UPDATE => 'CURRENT_TIMESTAMP',
            ColumnOptions::NULL => true
        ]);

        $table->save();

    }
}
