<?php


use Phinx\Seed\AbstractSeed;

class ModuleSeeder extends AbstractSeed
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
        $data = [
            [
                'mod_id' => 29,
                'mod_name' => 'Tất cả đơn',
                'mod_path' => 'orders',
                'mod_listname' => 'Danh sách',
                'mod_listfile' => 'listing.php',
                'mod_group' => 'Đơn hàng'
            ],
            [
                'mod_id' => 30,
                'mod_name' => 'Đơn hàng mới',
                'mod_path' => 'orders',
                'mod_listname' => 'Danh sách|Tạo đơn mới',
                'mod_listfile' => 'listing.php?status=NEW|add.php',
                'mod_group' => 'Đơn hàng'
            ],
            [
                'mod_id' => 31,
                'mod_name' => 'Đơn hàng chờ xử lý',
                'mod_path' => 'orders',
                'mod_listname' => 'Danh sách',
                'mod_listfile' => 'listing.php?status=PENDING',
                'mod_group' => 'Đơn hàng'
            ],
            [
                'mod_id' => 32,
                'mod_name' => 'Đơn đang vận chuyển',
                'mod_path' => 'orders',
                'mod_listname' => 'Danh sách',
                'mod_listfile' => 'listing.php?status=BEING_TRANSPORTED',
                'mod_group' => 'Đơn hàng'
            ],
            [
                'mod_id' => 50,
                'mod_name' => 'Đã nhận hàng',
                'mod_path' => 'orders',
                'mod_listname' => 'Danh sách',
                'mod_listfile' => 'listing.php?status=RECEIVED',
                'mod_group' => 'Đơn hàng'
            ],
            [
                'mod_id' => 33,
                'mod_name' => 'Đơn thành công',
                'mod_path' => 'orders',
                'mod_listname' => 'Danh sách',
                'mod_listfile' => 'listing.php?status=SUCCESS',
                'mod_group' => 'Đơn hàng'
            ],
            [
                'mod_id' => 34,
                'mod_name' => 'Đơn bị hủy',
                'mod_path' => 'orders',
                'mod_listname' => 'Danh sách',
                'mod_listfile' => 'listing.php?status=CANCEL',
                'mod_group' => 'Đơn hàng'
            ],
            [
                'mod_id' => 1,
                'mod_name' => 'Quản trị viên',
                'mod_path' => 'admin_user',
                'mod_listname' => 'Danh sách|Thêm mới',
                'mod_listfile' => 'listing.php|add.php',
            ],
            [
                'mod_id' => 25,
                'mod_name' => 'Lịch sử quản trị',
                'mod_path' => 'admin_logs',
                'mod_listname' => 'Danh sách',
                'mod_listfile' => 'listing.php',
            ],
            [
                'mod_id' => 41,
                'mod_name' => 'Báo cáo bán hàng',
                'mod_path' => 'report_sale',
                'mod_listname' => 'Theo sản phẩm',
                'mod_listfile' => 'products.php',
                'mod_group' => 'Báo cáo'
            ],
            [
                'mod_id' => 42,
                'mod_name' => 'Báo cáo phân chia hoa hồng',
                'mod_path' => 'report_commission',
                'mod_listname' => 'Theo sản phẩm|Chi tiết theo sản phẩm|Theo đại lý|Chi tiết theo đại lý',
                'mod_listfile' => 'products_commission.php|product_commission_detail.php|user_commission.php|user_commission_detail.php',
                'mod_group' => 'Báo cáo'
            ],
            [
                'mod_id' => 43,
                'mod_name' => 'Bảng cân đối tài khoản',
                'mod_path' => 'report_wallet',
                'mod_listname' => 'Ví hoa hồng theo đại lý|Ví tiêu dùng theo đại lý',
                'mod_listfile' => 'wallet_commission.php|wallet_charge.php',
                'mod_group' => 'Báo cáo'
            ],
            [
                'mod_id' => 44,
                'mod_name' => 'Lịch sử giao dịch',
                'mod_path' => 'report_money',
                'mod_listname' => 'Lịch sử giao dịch',
                'mod_listfile' => 'user_money_log.php',
                'mod_group' => 'Báo cáo'
            ],
            [
                'mod_id' => 46,
                'mod_name' => 'Thống kê kết quả thi đua',
                'mod_path' => 'report_event',
                'mod_listname' => 'Theo sản phẩm',
                'mod_listfile' => 'listing.php',
                'mod_group' => 'Báo cáo'
            ],
            [
                'mod_id' => 45,
                'mod_name' => 'Yêu cầu nạp tiền',
                'mod_path' => 'money_request_notify',
                'mod_listname' => 'Nạp ví tiêu dùng|Thanh toán đơn hàng',
                'mod_listfile' => 'listing.php?type=0|listing.php?type=1',
                'mod_group' => ''
            ],
            [
                'mod_id' => 4,
                'mod_name' => 'Sản phẩm',
                'mod_path' => 'products',
                'mod_listname' => 'Danh mục|Thêm mới danh mục|Danh sách|Thêm mới|Thương hiệu|Thêm mới thương hiệu',
                'mod_listfile' => '../categories/listing.php?type=PRODUCT|../categories/add.php?type=PRODUCT|listing.php|add.php|../categories/listing.php?type=BRAND|../categories/add.php?type=BRAND',
            ],
            [
                'mod_id' => 17,
                'mod_name' => 'Yêu cầu rút tiền hoa hồng',
                'mod_path' => 'payment_request',
                'mod_listname' => 'Danh sách',
                'mod_listfile' => 'listing.php',
            ],
            [
                'mod_id' => 24,
                'mod_name' => 'Tiền',
                'mod_path' => 'money',
                'mod_listname' => 'Danh sách|Lịch sử',
                'mod_listfile' => 'listing.php|log.php',
            ],
            [
                'mod_id' => 14,
                'mod_name' => 'Thành viên',
                'mod_path' => 'users',
                'mod_listname' => 'Danh sách|Thêm mới|Thống kê F1',
                'mod_listfile' => 'listing.php|add.php|f1.php',
            ],
            [
                'mod_id' => 40,
                'mod_name' => 'Sự kiện',
                'mod_path' => 'events',
                'mod_listname' => 'Danh sách|Thêm mới|Đua top|Thêm sự kiện đua top',
                'mod_listfile' => 'listing.php|add.php|../top_racing/listing.php|../top_racing/add.php',
            ],
            [
                'mod_id' => 8,
                'mod_name' => 'Tin tức',
                'mod_path' => 'posts',
                'mod_listname' => 'Danh sách|Thêm mới',
                'mod_listfile' => 'listing.php?type=NEWS|add.php?type=NEWS',
            ],
            [
                'mod_id' => 6,
                'mod_name' => 'Chính sách và lợi ích',
                'mod_path' => 'posts',
                'mod_listname' => 'Danh sách|Thêm mới',
                'mod_listfile' => 'listing.php?type=POLICY|add.php?type=POLICY',
            ],
            [
                'mod_id' => 7,
                'mod_name' => 'Định hướng và mục tiêu phát triển',
                'mod_path' => 'posts',
                'mod_listname' => 'Danh sách|Thêm mới',
                'mod_listfile' => 'listing.php?type=ORIENTATION|add.php?type=ORIENTATION',
            ],
            [
                'mod_id' => 9,
                'mod_name' => 'Video',
                'mod_path' => 'posts',
                'mod_listname' => 'Danh sách|Thêm mới',
                'mod_listfile' => 'listing.php?type=VIDEO|add.php?type=VIDEO',
            ],
            [
                'mod_id' => 11,
                'mod_name' => 'Thông báo',
                'mod_path' => 'notification',
                'mod_listname' => 'Danh sách|Thêm mới',
                'mod_listfile' => 'listing.php|add.php',
            ],
            [
                'mod_id' => 12,
                'mod_name' => 'Cấu hình',
                'mod_path' => 'settings',
                'mod_listname' => 'Cấu hình chung|Cấu hình tài khoản ngân hàng|Team mạnh nhất|Ngưỡng point lên level|% hoa thông theo level|Phí vận chuyển',
                'mod_listfile' => 'listing.php|bank_accounts.php|event_best_team.php|listing.php?prefix=user_up_level|listing.php?prefix=user_level|listing.php?prefix=shipping_fee',
            ],

//            [
//                'mod_id' => 15,
//                'mod_name' => 'Liên hệ',
//                'mod_path' => 'contact',
//                'mod_listname' => 'Danh sách',
//                'mod_listfile' => 'listing.php',
//            ],


            /*
            [
                'mod_id' => 23,
                'mod_name' => 'Chính sách hoa hồng trực tiếp',
                'mod_path' => 'commission_plan',
                'mod_listname' => 'Danh sách|Thêm mới',
                'mod_listfile' => 'listing.php|add.php',
            ],
            */
            [
                'mod_id' => 16,
                'mod_name' => 'File hệ thống',
                'mod_path' => 'files',
                'mod_listname' => 'Danh sách',
                'mod_listfile' => 'listing.php',
            ],
            [
                'mod_id' => 13,
                'mod_name' => 'Media',
                'mod_path' => 'sliders',
                'mod_listname' => 'Danh sách banner|Thêm mới banner|Thêm banner vào vị trí hiển thị',
                'mod_listfile' => 'listing.php|add_banner.php|add_banner_slider.php',
            ],
        ];

        $posts = $this->table('modules');
        $posts->truncate();
        $posts->insert($data)
            ->save();
    }
}
