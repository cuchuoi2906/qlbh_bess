<?php


use Phinx\Migration\AbstractMigration;

class AlterProductImagesTable extends AbstractMigration
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

        $table = $this->table('products_images');
        $table->changeColumn('pri_created_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::DEFAULT => 'CURRENT_TIMESTAMP'
        ]);
        $table->changeColumn('pri_updated_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::NULL => true,
            ColumnOptions::UPDATE => 'CURRENT_TIMESTAMP'
        ]);
        $table->changeColumn('pri_deleted_at', ColumnTypes::TIMESTAMP, [
            ColumnOptions::NULL => true
        ]);
        $table->update();
    }
}
