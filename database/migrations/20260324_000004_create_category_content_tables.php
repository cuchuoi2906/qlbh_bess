<?php

use Phinx\Migration\AbstractMigration;

/**
 * Category & content tables:
 * categories, category_news, posts, banner, banner_slider, slider,
 * contact, registration, notification, notification_status
 */
class CreateCategoryContentTables extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `categories` (
                `cat_id`              INT          NOT NULL AUTO_INCREMENT,
                `cat_name_vn`         VARCHAR(255) NOT NULL,
                `cat_rewrite`         VARCHAR(255) NOT NULL,
                `cat_parent_id`       INT          NOT NULL,
                `cat_active`          INT          NOT NULL,
                `cat_order`           INT          NOT NULL,
                `cat_description_vn`  TEXT         NOT NULL,
                `cat_icon`            VARCHAR(255) NOT NULL,
                `cat_seo_title`       VARCHAR(255) NOT NULL,
                `cat_seo_keyword`     TEXT         NOT NULL,
                `cat_seo_description` TEXT         NOT NULL,
                `cat_type`            VARCHAR(255) NOT NULL,
                `cat_created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `cat_updated_at`      TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `cat_deleted_at`      TIMESTAMP    NULL     DEFAULT NULL,
                PRIMARY KEY (`cat_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `category_news` (
                `cat_id`              INT          NOT NULL AUTO_INCREMENT,
                `cat_name_vn`         VARCHAR(255) NOT NULL,
                `cat_rewrite`         VARCHAR(255) NOT NULL,
                `cat_parent_id`       INT          NOT NULL,
                `cat_active`          INT          NOT NULL,
                `cat_order`           INT          NOT NULL,
                `cat_description_vn`  TEXT         NOT NULL,
                `cat_seo_title`       VARCHAR(255) NOT NULL,
                `cat_seo_keyword`     TEXT         NOT NULL,
                `cat_seo_description` TEXT         NOT NULL,
                `cat_type`            VARCHAR(255) NOT NULL,
                `cat_created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `cat_updated_at`      TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `cat_deleted_at`      TIMESTAMP    NULL     DEFAULT NULL,
                PRIMARY KEY (`cat_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `posts` (
                `pos_id`              INT          NOT NULL AUTO_INCREMENT,
                `pos_title_vn`        VARCHAR(255) NOT NULL,
                `pos_rewrite`         VARCHAR(255) NOT NULL,
                `pos_image`           VARCHAR(255) NOT NULL,
                `pos_teaser_vn`       TEXT         NOT NULL,
                `pos_content_vn`      LONGTEXT     NOT NULL,
                `pos_active`          INT          NOT NULL,
                `pos_category_id`     INT          NOT NULL,
                `pos_is_hot`          INT          NOT NULL,
                `pos_show_home`       INT          NOT NULL,
                `pos_created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `pos_updated_at`      TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `pos_deleted_at`      TIMESTAMP    NULL     DEFAULT NULL,
                `pos_seo_title`       VARCHAR(255) NOT NULL,
                `pos_seo_keyword`     TEXT         NOT NULL,
                `pos_seo_description` TEXT         NOT NULL,
                `pos_total_view`      INT          NOT NULL DEFAULT 0,
                `pos_type`            VARCHAR(255) NOT NULL,
                `pos_product_id`      INT          NOT NULL DEFAULT 0 COMMENT 'Id sản phẩm',
                `pot_deleted_at`      TIMESTAMP    NULL     DEFAULT NULL,
                PRIMARY KEY (`pos_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `slider` (
                `sli_id`  INT          NOT NULL AUTO_INCREMENT,
                `sli_name`VARCHAR(255) DEFAULT NULL,
                `sli_key` VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`sli_id`),
                KEY `sli_key` (`sli_key`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `banner` (
                `ban_id`          INT          NOT NULL AUTO_INCREMENT,
                `ban_title`       VARCHAR(255) DEFAULT NULL,
                `ban_link`        VARCHAR(255) DEFAULT NULL,
                `ban_image`       VARCHAR(255) DEFAULT NULL,
                `ban_html`        TEXT,
                `ban_type`        INT          DEFAULT NULL,
                `ban_active`      INT          DEFAULT NULL,
                `ban_create_time` INT          DEFAULT NULL,
                `ban_update_time` INT          DEFAULT NULL,
                `ban_object_type` VARCHAR(255) NOT NULL COMMENT 'Object type (CATEGORY, PRODUCT, NEWS, VIDEO)',
                `ban_object_id`   INT          NOT NULL DEFAULT 0 COMMENT 'Object id',
                `ban_type_new`    TINYINT      DEFAULT 0,
                PRIMARY KEY (`ban_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `banner_slider` (
                `bsl_id`         INT NOT NULL AUTO_INCREMENT,
                `bsl_sli_id`     INT DEFAULT NULL,
                `bsl_ban_id`     INT DEFAULT NULL,
                `bsl_full_time`  INT DEFAULT NULL,
                `bsl_start_time` INT DEFAULT NULL,
                `bsl_end_time`   INT DEFAULT NULL,
                `bsl_order`      INT DEFAULT NULL,
                PRIMARY KEY (`bsl_id`),
                KEY `index_1` (`bsl_sli_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `contact` (
                `con_id`         INT          NOT NULL AUTO_INCREMENT,
                `con_full_name`  VARCHAR(255) NOT NULL,
                `con_phone`      VARCHAR(255) NOT NULL,
                `con_email`      VARCHAR(255) NOT NULL,
                `con_note`       TEXT         NOT NULL,
                `con_read`       INT          NOT NULL,
                `con_created_at` DATETIME     NOT NULL,
                PRIMARY KEY (`con_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `registration` (
                `re_id`         INT          NOT NULL AUTO_INCREMENT COMMENT 'id cua vatgia.com',
                `re_name`       VARCHAR(255) DEFAULT NULL,
                `re_phone`      VARCHAR(50)  DEFAULT NULL,
                `re_email`      VARCHAR(100) DEFAULT NULL,
                `re_address`    VARCHAR(255) DEFAULT NULL,
                `re_comment`    VARCHAR(255) DEFAULT NULL,
                `re_created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`re_id`),
                KEY `index`       (`re_phone`),
                KEY `index_email` (`re_email`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `notification` (
                `not_id`         INT          NOT NULL AUTO_INCREMENT,
                `not_title`      VARCHAR(255) NOT NULL COMMENT 'Tiêu đề notification',
                `not_content`    TEXT         NOT NULL COMMENT 'Nội dung notification',
                `not_type`       VARCHAR(50)  NOT NULL COMMENT 'Loại thông báo. SYSTEM|COMMISSION|...',
                `not_is_send_all`INT          NOT NULL DEFAULT 0 COMMENT 'Gửi cho tất cả user?',
                `not_admin_id`   INT          NOT NULL DEFAULT 0,
                `not_created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `not_update_at`  TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`not_id`),
                KEY `not_is_send_all` (`not_is_send_all`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `notification_status` (
                `nts_id`              INT NOT NULL AUTO_INCREMENT,
                `nts_notification_id` INT NOT NULL COMMENT 'ID trong bảng notification',
                `nts_user_id`         INT NOT NULL COMMENT 'ID trong bảng user',
                `nts_status`          INT NOT NULL DEFAULT 0 COMMENT '0=chưa đọc|1=đã đọc|-1=đã xóa',
                `nts_read_at`         INT DEFAULT NULL COMMENT 'Thời điểm đọc thông báo',
                PRIMARY KEY (`nts_id`),
                UNIQUE KEY `nts_notification_id` (`nts_notification_id`, `nts_user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");
    }

    public function down(): void
    {
        foreach ([
            'notification_status', 'notification', 'registration', 'contact',
            'banner_slider', 'banner', 'slider', 'posts', 'category_news', 'categories',
        ] as $table) {
            $this->execute("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
