<?php


use Phinx\Migration\AbstractMigration;

class UserStatus extends AbstractMigration
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
        if (!$table->hasColumn('use_status')) {
            $table->addColumn('use_status', ColumnTypes::INTEGER, [
                ColumnOptions::DEFAULT => 1,
                ColumnOptions::LIMIT => 1
            ]);
        }

        timestamp_fields($table, 'use');

        $table->update();

    }
}
