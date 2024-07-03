<?php


use Phinx\Migration\AbstractMigration;

class SoftDelete extends AbstractMigration
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

    public $tables = [
        'categories' => 'cat',
        'posts' => 'pot',
        'products' => 'pro',
        'users' => 'use'
    ];

    public function up()
    {


        foreach ($this->tables as $table => $prefix) {
            $table = $this->table($table);
            if (!$table->hasColumn($prefix . '_deleted_at')) {
                $table->addColumn($prefix . '_deleted_at', ColumnTypes::TIMESTAMP, [
                    ColumnOptions::NULL => true
                ]);
                $table->update();
            }

        }

    }

    public function down()
    {
        foreach ($this->tables as $table => $prefix) {
            $table = $this->table($table);
            if ($table->hasColumn($prefix . '_deleted_at')) {
                $table->removeColumn($prefix . '_deleted_at');
                $table->update();
            }

        }
    }
}
