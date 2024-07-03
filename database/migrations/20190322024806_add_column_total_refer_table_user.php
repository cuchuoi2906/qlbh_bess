<?php


use Phinx\Migration\AbstractMigration;

class AddColumnTotalReferTableUser extends AbstractMigration
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

        $table = $this->table('users');
        $table->addColumn('use_total_direct_refer', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Tổng số người trực tiếp giới thiệu được',
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn('use_total_refer', ColumnTypes::INTEGER, [
            ColumnOptions::COMMENT => 'Tổng số người giới thiệu được',
            ColumnOptions::DEFAULT => 0
        ]);

        $table->update();

    }
}
