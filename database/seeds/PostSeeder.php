<?php


use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
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

        $types = [
            'VIDEO',
            'NEWS',
        ];
        $faker = Faker\Factory::create();
        $data = [];

        foreach ($types as $type) {
            $categories = \App\Models\Categories\Category::where('cat_type', $types)->all();
            if ($categories->count() > 0) {
                $categories = $categories->lists('cat_id');
            } else {
                $categories = [0];
            }
            for ($i = 0; $i <= 2; $i++) {
                shuffle($categories);
                $cat_id = reset($categories);
                $title = $faker->paragraph;
                $data[] = [
                    'pos_title_vn' => $title,
//                    'pos_title_en' => $title,
                    'pos_rewrite' => removeTitle($title),
                    'pos_image' => $faker->image(dirname(__FILE__) . '/../../public/upload/posts/', 640, 640, 'technics', false),
                    'pos_teaser_vn' => $faker->paragraph(2),
//                    'pos_teaser_en' => $faker->paragraph(2),
                    'pos_content_vn' => $faker->paragraph(10),
//                    'pos_content_en' => $faker->paragraph(10),
                    'pos_active' => 1,
                    'pos_category_id' => $cat_id,
                    'pos_is_hot' => round(rand(0, 1)),
                    'pos_show_home' => round(rand(0, 1)),
                    'pos_type' => strtoupper($type)
                ];
            }
        }

        $table = $this->table('posts');
//        $table->truncate();
        $table->insert($data)
            ->save();
    }
}
