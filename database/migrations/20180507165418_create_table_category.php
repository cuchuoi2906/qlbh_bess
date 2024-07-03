<?php


use Phinx\Migration\AbstractMigration;

class CreateTableCategory extends AbstractMigration
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
        $table = $this->table('categories', ['id' => 'cat_id']);
        $table->addColumn('cat_name_vn', ColumnTypes::STRING)
            ->addColumn('cat_name_en', ColumnTypes::STRING)
            ->addColumn('cat_rewrite', ColumnTypes::STRING)
            ->addColumn('cat_parent_id', ColumnTypes::INTEGER)
            ->addColumn('cat_active', ColumnTypes::INTEGER)
            ->addColumn('cat_order', ColumnTypes::INTEGER)
            ->addColumn('cat_description_vn', ColumnTypes::TEXT)
            ->addColumn('cat_description_en', ColumnTypes::TEXT)
            ->addColumn('cat_icon', ColumnTypes::STRING)
            ->addColumn('cat_seo_title', ColumnTypes::STRING)
            ->addColumn('cat_seo_keyword', ColumnTypes::TEXT)
            ->addColumn('cat_seo_description', ColumnTypes::TEXT)
            ->addColumn('cat_created_at', ColumnTypes::DATETIME)
            ->addColumn('cat_updated_at', ColumnTypes::DATETIME)
            ->save();
    }

    public function down()
    {
        if ($this->hasTable('categories')) {
            $this->dropTable('categories');
        }
    }
}
