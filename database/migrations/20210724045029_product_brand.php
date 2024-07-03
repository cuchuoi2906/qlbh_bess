<?php


use Phinx\Migration\AbstractMigration;

class ProductBrand extends AbstractMigration
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

        $model = new \App\Models\Product();
        $prefix = $model->prefix;

        $table = $this->table($model->table);
        $table->addColumn($prefix . '_brand_id', ColumnTypes::INTEGER, [
            ColumnOptions::LIMIT => 11,
            ColumnOptions::DEFAULT => 0,
            ColumnOptions::COMMENT => 'Brand id chính là category id nhưng type = brand'
        ]);

        $table->update();
    }
}
