<?php


use Phinx\Migration\AbstractMigration;

class AlterTableUsersUpdateBaum extends AbstractMigration
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

        return;
        $table = $this->table('users');
        $table->addColumn('use_lft',ColumnTypes::INTEGER, ['comment' => 'node left baum']);
        $table->addColumn('use_rgt',ColumnTypes::INTEGER, ['comment' => 'node left baum']);
        $table->addColumn('use_depth',ColumnTypes::INTEGER, ['comment' => 'node left baum']);
        $table->addIndex(['use_lft','use_rgt']);
        $table->addIndex('use_depth');
        $table->addIndex('use_referral_id');
//        $table->addIndex('use_register_confirm_code');

        $table->update();

    }
}
