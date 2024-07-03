<?php


use Phinx\Migration\AbstractMigration;

class TopRacingCampaign extends AbstractMigration
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

        $model = new \App\Models\TopRacingCampaign();
        $prefix = $model->prefix;

        $table = $this->table($model->table, ['id' => $prefix . '_id']);
        $table->addColumn($prefix . '_title', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Tên chiến dịch',
            ColumnOptions::LIMIT => 255
        ]);
        $table->addColumn($prefix . '_description', ColumnTypes::TEXT, [
            ColumnOptions::COMMENT => 'Mô tả chiến dịch',
        ]);
        $table->addColumn($prefix . '_active', ColumnTypes::INTEGER, [
            ColumnOptions::DEFAULT => 1,
            ColumnOptions::LIMIT => 1
        ]);
        $table->addColumn($prefix . '_start', ColumnTypes::INTEGER, []);
        $table->addColumn($prefix . '_end', ColumnTypes::INTEGER, []);

        $table->addColumn($prefix . '_all_product', ColumnTypes::INTEGER, [
            ColumnOptions::LIMIT => 1
        ]);

        $table->addColumn($prefix . '_admin_id', ColumnTypes::INTEGER, [
            ColumnOptions::LIMIT => 11
        ]);

        $table->addColumn($prefix . '_type', ColumnTypes::VARCHAR, [
            ColumnOptions::LIMIT => 255,
            ColumnOptions::DEFAULT => 'TEAM' //TEAM | OWNER
        ]);

        timestamp_fields($table, $prefix);

        $table->create();

        $model = new \App\Models\TopRacingCampaignProduct;
        $prefix = $model->prefix;

        $table = $this->table($model->table, ['id' => $prefix . '_id']);
        $table->addColumn($prefix . '_campaign_id', ColumnTypes::INTEGER, [
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn($prefix . '_product_id', ColumnTypes::INTEGER, [
            ColumnOptions::DEFAULT => 0
        ]);

        $table->create();
    }
}
