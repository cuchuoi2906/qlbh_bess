<?php


use Phinx\Migration\AbstractMigration;

class CreateTableProducts extends AbstractMigration
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
//    public function change()
//    {
//
//    }

    public function up()
    {
        $table = $this->table('products', ['id' => 'pro_id']);
        $table->addColumn('pro_name_vn', ColumnTypes::STRING)
            ->addColumn('pro_name_en', ColumnTypes::STRING)
            ->addColumn('pro_teaser_vn', ColumnTypes::TEXT)
            ->addColumn('pro_teaser_en', ColumnTypes::TEXT)
            ->addColumn('pro_price', ColumnTypes::INTEGER)
            ->addColumn('pro_category_id', ColumnTypes::INTEGER)
            ->addColumn('pro_functions_vn', ColumnTypes::TEXT)
            ->addColumn('pro_functions_en', ColumnTypes::TEXT)
            ->addColumn('pro_specifications_vn', ColumnTypes::TEXT)
            ->addColumn('pro_specifications_en', ColumnTypes::TEXT)
            ->addColumn('pro_active', ColumnTypes::INTEGER)
            ->addColumn('pro_created_at', ColumnTypes::DATETIME)
            ->addColumn('pro_updated_at', ColumnTypes::DATETIME)
            ->addColumn('pro_deleted_at', ColumnTypes::DATETIME)
            ->save();

        $table = $this->table('products_images', ['id' => 'pri_id']);
        $table->addColumn('pri_product_id', ColumnTypes::INTEGER)
            ->addColumn('pri_file_name', ColumnTypes::STRING)
            ->addColumn('pri_is_avatar', ColumnTypes::INTEGER)
            ->addColumn('pri_created_at', ColumnTypes::DATETIME)
            ->addColumn('pri_updated_at', ColumnTypes::DATETIME)
            ->addColumn('pri_deleted_at', ColumnTypes::DATETIME)
            ->save();
    }

    public function down()
    {
        if ($this->hasTable('products')) {
            $this->dropTable('products');
        }
        if ($this->hasTable('products_images')) {
            $this->dropTable('products_images');
        }
    }
}
