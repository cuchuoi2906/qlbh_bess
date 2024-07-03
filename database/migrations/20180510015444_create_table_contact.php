<?php


use Phinx\Migration\AbstractMigration;

class CreateTableContact extends AbstractMigration
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
        $table = $this->table('contact', ['id' => 'con_id']);
        $table->addColumn('con_full_name', ColumnTypes::STRING)
            ->addColumn('con_phone', ColumnTypes::STRING)
            ->addColumn('con_email', ColumnTypes::STRING)
            ->addColumn('con_note', ColumnTypes::TEXT)
            ->addColumn('con_read', ColumnTypes::INTEGER)
            ->addColumn('con_created_at', ColumnTypes::DATETIME)
            ->save();
    }

    public function down()
    {
        if ($this->hasTable('contact')) {
            $this->dropTable('contact');
        }

    }
}
