<?php


use Phinx\Migration\AbstractMigration;

class UpdateUserTable extends AbstractMigration
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
        $table->addColumn('use_identity_number', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Số chứng minh nhân dân',
            ColumnOptions::NULL => true
        ]);
        $table->addColumn('use_identity_front_image', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Ảnh chứng minh mặt trước',
            ColumnOptions::NULL => true
        ]);
        $table->addColumn('use_identity_back_image', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Ảnh chứng minh mặt sau',
            ColumnOptions::NULL => true
        ]);

        $table->update();
    }
}
