<?php


use Phinx\Migration\AbstractMigration;

class ChangeColumnLevelUser extends AbstractMigration
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

        $table = $this->table('users');
        $table->changeColumn('use_level', ColumnTypes::INTEGER, [
            ColumnOptions::DEFAULT => 0
        ]);

        $table->addIndex('use_level');
        $table->addIndex('use_total_direct_refer');
        $table->addIndex('use_total_refer');

        $table->update();
    }

    public function down()
    {
    }
}
