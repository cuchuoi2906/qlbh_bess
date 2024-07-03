<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
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
        $data = [];

        $faker = Faker\Factory::create();


        for ($i = 0; $i <= 10; $i++) {
            $phone = $faker->phoneNumber;
            $user = [
                'use_active' => 1,
                'use_login' => $phone,
                'use_loginname' => $phone,
                'use_password' => md5('123456'),
                'use_gender' => rand(0, 1),
                'use_phone' => $phone,
                'use_email' => $faker->email,
                'use_mobile' => $phone,
                'use_referral_id' => 0,
            ];
            array_push($data, $user);
        }


        for ($i = 0; $i <= 100; $i++) {
            $phone = $faker->phoneNumber;
            $user = [
                'use_active' => 1,
                'use_login' => $phone,
                'use_loginname' => $phone,
                'use_password' => md5('123456'),
                'use_gender' => rand(0, 1),
                'use_phone' => $phone,
                'use_email' => $faker->email,
                'use_mobile' => $phone,
                'use_referral_id' => rand(1,10),
            ];
            array_push($data, $user);
        }


        $table = $this->table('users');
        $table->truncate();
        $table->insert($data)
            ->save();
    }
}
