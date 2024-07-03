<?php


use Phinx\Seed\AbstractSeed;

class ResetDatabaseSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {


        $this->table('users')->truncate();
        $this->execute('ALTER TABLE users AUTO_INCREMENT=1234;');

        $this->table('orders')->truncate();
        $this->table('order_products')->truncate();
        $this->table('order_logs')->truncate();
        $this->table('order_commissions')->truncate();
        $this->table('notification')->truncate();
        $this->table('notification_status')->truncate();
        $this->table('user_banks')->truncate();
        $this->table('user_cart')->truncate();
        $this->table('user_device')->truncate();
        $this->table('user_money_add')->truncate();
        $this->table('user_money_add_request')->truncate();
        $this->table('user_money_logs')->truncate();
        $this->table('user_payment_request')->truncate();
        $this->table('user_transfer')->truncate();
        $this->table('user_wallet')->truncate();
        $this->table('user_wallet_log')->truncate();
        $this->table('money_add_request_notify')->truncate();

    }
}
