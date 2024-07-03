<?php


use Phinx\Seed\AbstractSeed;

class OrderSuccess extends AbstractSeed
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

        $orders = \App\Models\Order::where('ord_status_code', \App\Models\Order::SUCCESS)
            ->all();

        foreach ($orders as $order) {
            $commission = $order->commissions->first();
            if ($commission) {
                $order->successed_at = $commission->created_at;
                if (null == $order->payment_successed_at) {
                    $order->payment_successed_at = $commission->created_at;
                }
                $order->update();
            }
        }
    }
}
