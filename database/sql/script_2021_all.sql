ALTER TABLE products ADD pro_active_inventory TINYINT(4) DEFAULT 1 AFTER pro_active; -- Cấu hình hiển thị trang thái tồn kho

INSERT INTO `modules` (`mod_id`, `mod_name`, `mod_path`, `mod_listname`, `mod_listfile`, `mod_order`, `mod_help`, `lang_id`, `mod_checkloca`, `mod_group`) VALUES
(47,	'Báo cáo doanh thu',	'report_users_order',	'Tổng hợp',	'order.php',	0,	NULL,	1,	0,	'Báo cáo');

INSERT INTO `modules` (`mod_id`, `mod_name`, `mod_path`, `mod_listname`, `mod_listfile`, `mod_order`, `mod_help`, `lang_id`, `mod_checkloca`, `mod_group`) VALUES
(48,	'Báo cáo điểm thưởng',	'report_users_reward_points',	'Tổng hợp',	'listing.php',	0,	NULL,	1,	0,	'Báo cáo');

INSERT INTO `modules` (`mod_id`, `mod_name`, `mod_path`, `mod_listname`, `mod_listfile`, `mod_order`, `mod_help`, `lang_id`, `mod_checkloca`, `mod_group`) VALUES
(49,	'khách hàng đăng ký',	'registration',	'Danh sách',	'registration.php',	0,	NULL,	1,	0,	'');

INSERT INTO `modules` (`mod_id`, `mod_name`, `mod_path`, `mod_listname`, `mod_listfile`, `mod_order`, `mod_help`, `lang_id`, `mod_checkloca`, `mod_group`) VALUES
(20,	'Quản lý leader',	'leader_users',	'Danh sách',	'listing.php',	0,	NULL,	1,	0,	'');


ALTER TABLE users ADD use_leader  int(1) DEFAULT 0 AFTER use_is_seller;
ALTER TABLE users ADD use_content  text DEFAULT '' AFTER use_leader;


ALTER TABLE order_products ADD orp_commission_product  int(11) DEFAULT 0 COMMENT 'Hoa hồng theo sản phẩm pro_commission *(% hoa hồng theo level user 1 + % hoa hồng theo level user 2 +....)';
ALTER TABLE order_products ADD orp_commission_buy  int(11) DEFAULT 0 COMMENT 'pro_commission  giá mua thời điểm hiện tại';
ALTER TABLE order_commissions ADD orc_commission_buy  int(11) DEFAULT 0 COMMENT 'phần trăm user được hưởng thời điểm mua';
ALTER TABLE order_commissions ADD orc_commission_backup  float DEFAULT 0 COMMENT 'Back up lại phần chiết khấu cộng cho parent';



INSERT INTO `modules` (`mod_id`, `mod_name`, `mod_path`, `mod_listname`, `mod_listfile`, `mod_order`, `mod_help`, `lang_id`, `mod_checkloca`, `mod_group`) VALUES
(48,	'Báo cáo điểm thưởng',	'report_users_reward_points',	'Tổng hợp',	'listing.php',	0,	NULL,	1,	0,	'Báo cáo');

INSERT INTO `modules` (`mod_id`, `mod_name`, `mod_path`, `mod_listname`, `mod_listfile`, `mod_order`, `mod_help`, `lang_id`, `mod_checkloca`, `mod_group`) VALUES
(54,	'Báo cáo đơn hàng theo mã vận đơn', 'report_oder_by_code', 'Tổng hợp',	'listing.php',	0,	NULL,	1,	0,	'Báo cáo');


INSERT INTO `settings_website` (`swe_id`, `swe_key`, `swe_value_vn`, `swe_type`, `swe_create_time`, `swe_update_time`, `swe_label`, `swe_display`) VALUES
(258,	'tong_gia_tri_point_huong_uu_dai_1',	'1000000_50',	'number',	NULL,	1658053443,	'Tổng giá trị point hương ưu đãi 1',	1),
(259,	'tong_gia_tri_point_huong_uu_dai_2',	'3000000_60',	'number',	NULL,	1658053448,	'Tổng giá trị point hương ưu đãi 2',	1),
(260,	'tong_gia_tri_point_huong_uu_dai_3',	'9000000_70',	'number',	NULL,	1646751480,	'Tổng giá trị point hương ưu đãi 3',	1),
(261,	'tong_gia_tri_point_huong_uu_dai_4',	'27000000_80',	'number',	NULL,	1646751485,	'Tổng giá trị point hương ưu đãi 4',	1),
(262,	'tong_gia_tri_point_huong_uu_dai_5',	'81000000_90',	'number',	NULL,	1646751497,	'Tổng giá trị point hương ưu đãi 5',	1),
(263,	'tong_gia_tri_point_huong_uu_dai_6',	'243000000_100', 'number',	NULL,	1646751497,	'Tổng giá trị point hương ưu đãi 6',	1);

ALTER TABLE products ADD pro_video_file_name varchar(255) DEFAULT ''; -- Them ten video file name
ALTER TABLE banner ADD ban_type_new TINYINT(4) DEFAULT 0; -- Them loai banner
ALTER TABLE orders ADD ord_shipping_at timestamp NULL DEFAULT NULL; -- Them thoi gian dang van chuyen
ALTER TABLE orders ADD ord_pending_at timestamp NULL DEFAULT NULL; -- Them thoi gian truuoc khi doi trang thai dang van chuyen

INSERT INTO `ward` (`war_id`, `war_name`, `war_type`, `war_location`, `war_district_id`) VALUES
('26069', 'An Hòa', 'Phường', '10534B 1065222', '731');

-- Export du lieu Ví
SELECT use_id as 'user id',use_name as 'Tên user',uwl_money_old as 'Số tiền cũ', uwl_money_add as 'Số tiền thêm vào'
, uwl_money_reduction as 'Số tiền giảm đi',uwl_money_new as 'Số tiền mới',uwl_note as 'Ghi chú',usw_updated_at as 'Thời gian'
FROM user_wallet INNER JOIN user_wallet_log ON 	usw_id = uwl_wallet_id INNER JOIN users ON use_id = usw_user_id WHERE usw_updated_at > '2022-09-01 00:00:00';

INSERT INTO `settings_website` (`swe_key`, `swe_value_vn`, `swe_type`, `swe_create_time`, `swe_update_time`, `swe_label`, `swe_display`) VALUES
('user_id_admin',	'78',	'number',	NULL,	now(),	'user admin được phép sửa giá đơn hàng, Mỗi user cách nhau dấu ,',	1),
('user_order_id',	'1235',	'number',	NULL,	now(),	'user đặt hàng được phép sửa giá đơn hàng, Mỗi user cách nhau dấu ,',	1);

						$moneyNoMarket = 1000000;// Số tiền không tính < 1tr
						$moneyMaxGroupOnly = 10000000; //  Số tiền quy định nhóm độc lập
						$moneyMaxOneGroupOnly = 4000000; //  Số tiền quy định có 1 nhóm độc  lập của chính  f0
						$percentCommit  = 20;

INSERT INTO `settings_website` (`swe_key`, `swe_value_vn`, `swe_type`, `swe_create_time`, `swe_update_time`, `swe_label`, `swe_display`) VALUES
('diem_mkt_thuong_lanh_dao_1',	'1000000',	'number',	NULL,	now(),	'Số  điểm MKT cá nhân tối thiểu',	1),
('diem_mkt_thuong_lanh_dao_2',	'4000000',	'number',	NULL,	now(),	'Điều kiện được thưởng MKT',	1),
('diem_mkt_thuong_lanh_dao_3',	'10000000',	'number',	NULL,	now(),	'Điều kiện đạt nhóm độc lập',	1),
('diem_mkt_thuong_lanh_dao_4',	'20',	'number',	NULL,	now(),	'Phần trăm thưởng lãnh ',	1);


INSERT INTO `modules` (`mod_id`, `mod_name`, `mod_path`, `mod_listname`, `mod_listfile`, `mod_order`, `mod_help`, `lang_id`, `mod_checkloca`, `mod_group`) VALUES
(90,	'categoryNews', 'category_news', 'Chuyên mục',	'listing.php',	0,	NULL,	1,	0,	'Nội dung');


DROP TABLE IF EXISTS `category_news`;
CREATE TABLE `category_news` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name_vn` varchar(255) NOT NULL,
  `cat_rewrite` varchar(255) NOT NULL,
  `cat_parent_id` int(11) NOT NULL,
  `cat_active` int(11) NOT NULL,
  `cat_order` int(11) NOT NULL,
  `cat_description_vn` text NOT NULL,
  `cat_seo_title` varchar(255) NOT NULL,
  `cat_seo_keyword` text NOT NULL,
  `cat_seo_description` text NOT NULL,
  `cat_type` varchar(255) NOT NULL,
  `cat_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cat_updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `cat_deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
