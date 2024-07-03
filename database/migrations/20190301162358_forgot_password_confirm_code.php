<?php


use Phinx\Migration\AbstractMigration;

class ForgotPasswordConfirmCode extends AbstractMigration
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
        $table->addColumn('use_forgot_password_confirm_code', ColumnTypes::VARCHAR, [
            ColumnOptions::COMMENT => 'Mã sử dụng để người dùng đổi lại password',
            ColumnOptions::NULL => true,
            ColumnOptions::DEFAULT => ''
        ]);

        $table->update();

    }
}
