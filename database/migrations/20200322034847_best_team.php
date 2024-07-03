<?php


use Phinx\Migration\AbstractMigration;

class BestTeam extends AbstractMigration
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

        $model = new \App\Models\BestTeam();
        $prefix = $model->prefix;
        $table = $this->table($model->table, ['id' => $prefix . '_id']);
        $table->addColumn($prefix . '_user_id', ColumnTypes::INTEGER, []);
        $table->addColumn($prefix . '_date', ColumnTypes::DATE, []);
        $table->addColumn($prefix . '_point', ColumnTypes::INTEGER, [
            ColumnOptions::DEFAULT => 0
        ]);
        $table->addColumn($prefix . '_type', ColumnTypes::INTEGER, [
            ColumnOptions::DEFAULT => 0,
            ColumnOptions::COMMENT => 'Loại xếp hạng. 0 => Team mạnh nhất'
        ]);

        $table->addIndex([
            $prefix . '_user_id',
            $prefix . '_date',
            $prefix . '_type'
        ], ['unique' => true]);

        $table->save();


    }
}
