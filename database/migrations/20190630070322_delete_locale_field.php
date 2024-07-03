<?php


use Phinx\Migration\AbstractMigration;

class DeleteLocaleField extends AbstractMigration
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

        $table = $this->table('products');
        $table->removeColumn('pro_name_en');
        $table->removeColumn('pro_teaser_en');
        $table->removeColumn('pro_functions_en');
        $table->removeColumn('pro_specifications_en');

        $table->update();

        $table = $this->table('posts');
        $table->removeColumn('pos_title_en');
        $table->removeColumn('pos_teaser_en');
        $table->removeColumn('pos_content_en');

        $table->update();

        $table = $this->table('categories');
        $table->removeColumn('cat_name_en');
        $table->removeColumn('cat_description_en');

        $table->update();

    }
}
