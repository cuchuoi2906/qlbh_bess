<?php


use Phinx\Migration\AbstractMigration;

class AlterIndexOrderCommission extends AbstractMigration
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
        $table = $this->table('order_commissions');
        $table->removeIndex(['orc_order_id', 'orc_user_id']);
        $table->addIndex(['orc_order_id', 'orc_user_id', 'orc_is_direct'], ['unique' => true]);

        $table->update();
    }

    public function down()
    {
        $table = $this->table('order_commissions');
        $table->removeIndex(['orc_order_id', 'orc_user_id', 'orc_is_direct']);
        $table->addIndex(['orc_order_id', 'orc_user_id'], ['unique' => true]);

        $table->update();
    }
}
