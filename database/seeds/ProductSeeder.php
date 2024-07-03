<?php


use App\Models\Categories\Category;
use Phinx\Seed\AbstractSeed;

class ProductSeeder extends AbstractSeed
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


        //Insert category trước
        /*
        $table = $this->table('categories');
        $table->truncate();
        $table->insert([
            [
                'cat_id' => 1,
                'cat_name_vn' => 'Mỹ phẩm',
                'cat_rewrite' => 'my-pham',
                'cat_active' => 1,
                'cat_type' => 'PRODUCT'
            ],
            [
                'cat_id' => 2,
                'cat_name_vn' => 'Hàng tiêu dùng',
                'cat_rewrite' => 'hang-tieu-dung',
                'cat_active' => 1,
                'cat_type' => 'PRODUCT'
            ],
            [
                'cat_id' => 3,
                'cat_name_vn' => 'Thực phẩm chức năng',
                'cat_rewrite' => 'thuc-pham-chuc-nang',
                'cat_active' => 1,
                'cat_type' => 'PRODUCT'
            ],
            [
                'cat_id' => 4,
                'cat_name_vn' => 'Chăm sóc tóc',
                'cat_rewrite' => 'cham-soc-toc',
                'cat_active' => 1,
                'cat_type' => 'PRODUCT'
            ],
            [
                'cat_id' => 5,
                'cat_name_vn' => 'Chăm sóc da',
                'cat_rewrite' => 'cham-soc-da',
                'cat_active' => 1,
                'cat_type' => 'PRODUCT'
            ],
        ])->save();
        */


        $faker = Faker\Factory::create();
        $data = [];
        $img = [];
        $categories = Category::where('cat_type', 'PRODUCT')->all();
        $categories = $categories->lists('cat_id', 'cat_id');
        for ($i = 1; $i < 16; $i++) {
            $price = $faker->numerify() * 1500;
            shuffle($categories);
            $cat_id = reset($categories);
            $name = $faker->name;
            $teaser = $faker->text();
            $data[] = [
                'pro_id' => $i,
                'pro_name_vn' => $name,
//                'pro_name_en' => $name,
                'pro_teaser_vn' => $teaser,
//                'pro_teaser_en' => $teaser,
                'pro_price' => $price,
                'pro_category_id' => $cat_id,
                'pro_functions_vn' => $faker->paragraphs(5, true),
//                'pro_functions_en' => $faker->paragraphs(5, true),
                'pro_specifications_vn' => $faker->paragraphs(5, true),
//                'pro_specifications_en' => $faker->paragraphs(5, true),
                'pro_active' => 1,
                'pro_is_hot' => round(rand(0, 1)),
                'pro_quantity' => round(rand(0, 30)),
                'pro_discount_price' => (round(rand(0, 1)) == 1) ? $price * rand(0, 1) : 0,
                'pro_commission' => (int)rand(10, 40)
            ];

            for ($j = 0; $j <= 3; $j++) {
                $img[] = [
                    'pri_product_id' => $i,
                    'pri_file_name' => $faker->image(dirname(__FILE__) . '/../../public/upload/products/', 400, 400, null, false),
                    'pri_is_avatar' => $j == 0 ? 1 : 0,
                    'pri_created_at' => date('Y-m-d H:i:s')
                ];
            }

        }
        $table = $this->table('products');
        $table->truncate();
        $table->insert($data)
            ->save();

        $table = $this->table('products_images');
        $table->truncate();
        $table->insert($img)
            ->save();
    }
}
