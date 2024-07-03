<?php


use Phinx\Seed\AbstractSeed;

class SettingSeeder extends AbstractSeed
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
        //Seting ngưỡng tăng cấp độ user
        for ($i = 1; $i <= 100; $i++) {
            $data = array_merge($data, [
                [
                    'swe_key' => 'user_up_level_' . $i . '_threshold',
                    'swe_label' => 'Tổng số tiền hoa hồng khi user đạt được sẽ được lên level ' . $i,
                    'swe_value_vn' => '500000000',
//                    'swe_value_en' => '0',
                    'swe_type' => 'number',
                    'swe_create_time' => time(),
                ],
            ]);
        }


        //Config số phần trăm tương ứng với từng cấp độ
        for ($i = 1; $i <= 100; $i++) {
            $data = array_merge($data, [
                [
                    'swe_key' => 'user_level_' . $i . '_commission',
                    'swe_label' => '% hoa hồng user level ' . $i,
                    'swe_value_vn' => '10',
//                    'swe_value_en' => '10',
                    'swe_type' => 'number',
                    'swe_create_time' => time(),
                ],
            ]);
        }

        $table = $this->table('settings_website');
//        $table->truncate();
        $table->insert($data)
            ->save();

    }
}
