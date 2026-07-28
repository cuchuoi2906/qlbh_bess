<?php

use Phinx\Migration\AbstractMigration;

/**
 * Product tables:
 * products, products_images, products_hapu, product_liked, product_price_policies,
 * commission_plan, events
 *
 * NOTE: Triggers on products, products_images, product_price_policies are defined
 * separately and replicate data to the `duocthuchinh` database — re-create manually
 * on production if needed (they are not included here to keep migrations portable).
 */
class CreateProductTables extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `commission_plan` (
                `cpl_id`                  INT          NOT NULL AUTO_INCREMENT,
                `cpl_plan_name`           VARCHAR(255) NOT NULL COMMENT 'Tên chính sách hoa hồng',
                `cpl_commission_percent`  INT          NOT NULL COMMENT 'Phần trăm hoa hồng trực tiếp',
                `cpl_start_time`          INT          NOT NULL DEFAULT 0,
                `cpl_end_time`            INT          NOT NULL DEFAULT 0,
                `cpl_active`              INT          NOT NULL DEFAULT 1 COMMENT 'Trạng thái',
                `cpl_created_at`          TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `cpl_updated_at`          TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`cpl_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `events` (
                `evt_id`                        INT          NOT NULL AUTO_INCREMENT,
                `evt_name`                      VARCHAR(255) NOT NULL COMMENT 'Tên sự kiện',
                `evt_active`                    INT          NOT NULL DEFAULT 1,
                `evt_note`                      TEXT         NOT NULL,
                `evt_start_time`                INT          NOT NULL,
                `evt_end_time`                  INT          DEFAULT NULL,
                `evt_direct_commission_ratio`   FLOAT        NOT NULL DEFAULT 1,
                `evt_parent_commission_ratio`   FLOAT        NOT NULL DEFAULT 1,
                `evt_admin_id`                  INT          NOT NULL DEFAULT 0,
                `evt_created_at`                TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `evt_updated_at`                TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `evt_deleted_at`                TIMESTAMP    NULL     DEFAULT NULL,
                PRIMARY KEY (`evt_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `products` (
                `pro_id`                INT          NOT NULL AUTO_INCREMENT,
                `pro_name_vn`           VARCHAR(255) NOT NULL,
                `pro_teaser_vn`         TEXT         NOT NULL,
                `pro_price`             FLOAT        NOT NULL,
                `pro_category_id`       INT          NOT NULL,
                `pro_functions_vn`      TEXT,
                `pro_specifications_vn` TEXT,
                `pro_active`            INT          NOT NULL,
                `pro_active_inventory`  TINYINT      DEFAULT 1,
                `pro_created_at`        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `pro_updated_at`        TIMESTAMP    NULL     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `pro_deleted_at`        TIMESTAMP    NULL     DEFAULT NULL,
                `pro_is_hot`            INT          NOT NULL DEFAULT 0,
                `pro_discount_price`    FLOAT        NOT NULL,
                `pro_quantity`          INT          NOT NULL,
                `pro_commission`        INT          NOT NULL DEFAULT 0 COMMENT 'Tổng hoa hồng % khi bán 1 sản phẩm',
                `pro_commission_plan_id`INT          NOT NULL DEFAULT 0,
                `pro_order`             FLOAT        NOT NULL DEFAULT 0,
                `pro_barcode`           VARCHAR(100) DEFAULT NULL,
                `pro_code`              VARCHAR(255) NOT NULL DEFAULT '',
                `pro_is_trial`          INT          NOT NULL DEFAULT 0,
                `pro_price_cost`        INT          NOT NULL DEFAULT 0 COMMENT 'Giá nhập',
                `pro_total_liked`       INT          NOT NULL DEFAULT 0,
                `pro_point`             INT          NOT NULL DEFAULT 0,
                `pro_brand_id`          INT          NOT NULL DEFAULT 0,
                `pro_video_file_name`   VARCHAR(255) DEFAULT '',
                `pro_type`              VARCHAR(50)  NOT NULL,
                `pro_order_by`          INT          DEFAULT 9999,
                `pro_date`              VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`pro_id`),
                UNIQUE KEY `product_code`   (`pro_code`),
                KEY        `pro_order`      (`pro_order`),
                KEY        `pro_total_liked`(`pro_total_liked`),
                KEY        `type`           (`pro_type`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `products_images` (
                `pri_id`         INT          NOT NULL AUTO_INCREMENT,
                `pri_product_id` INT          NOT NULL,
                `pri_file_name`  VARCHAR(255) NOT NULL,
                `pri_is_avatar`  INT          NOT NULL,
                `pri_created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `pri_updated_at` TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `pri_deleted_at` TIMESTAMP    NULL     DEFAULT NULL,
                PRIMARY KEY (`pri_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `products_hapu` (
                `pro_ha_id`           INT          NOT NULL AUTO_INCREMENT,
                `pro_ha_product_id`   INT          NOT NULL,
                `pro_ha_supplier`     VARCHAR(1000) DEFAULT NULL,
                `pro_ha_price`        FLOAT        NOT NULL DEFAULT 0,
                `pro_ha_pharmacy`     VARCHAR(255) DEFAULT NULL,
                `pro_ha_note`         TEXT,
                `pro_ha_price_min`    FLOAT        NOT NULL DEFAULT 0,
                `pro_ha_pharmacy_min` VARCHAR(255) DEFAULT NULL,
                `pro_ha_price_max`    FLOAT        NOT NULL DEFAULT 0,
                `pro_ha_pharmacy_max` VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`pro_ha_id`),
                UNIQUE KEY `product_id` (`pro_ha_product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `product_liked` (
                `prl_id`         INT       NOT NULL AUTO_INCREMENT,
                `prl_user_id`    INT       NOT NULL,
                `prl_product_id` INT       NOT NULL,
                `prl_created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `prl_updated_at` TIMESTAMP NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `prl_deleted_at` TIMESTAMP NULL     DEFAULT NULL,
                PRIMARY KEY (`prl_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `product_price_policies` (
                `ppp_id`         INT       NOT NULL AUTO_INCREMENT,
                `ppp_product_id` INT       NOT NULL,
                `ppp_quantity`   INT       NOT NULL DEFAULT 1 COMMENT 'Ngưỡng số lượng sản phẩm',
                `ppp_price`      INT       NOT NULL DEFAULT 0 COMMENT 'Giá sản phẩm khi đạt ngưỡng',
                `ppp_created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `ppp_updated_at` TIMESTAMP NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`ppp_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `top_racing_campaign` (
                `trc_id`          INT          NOT NULL AUTO_INCREMENT,
                `trc_title`       VARCHAR(255) NOT NULL COMMENT 'Tên chiến dịch',
                `trc_description` TEXT         NOT NULL,
                `trc_active`      INT          NOT NULL DEFAULT 1,
                `trc_start`       INT          NOT NULL,
                `trc_end`         INT          NOT NULL,
                `trc_all_product` INT          NOT NULL,
                `trc_admin_id`    INT          NOT NULL,
                `trc_type`        VARCHAR(255) NOT NULL DEFAULT 'TEAM',
                `trc_created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `trc_updated_at`  TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `trc_deleted_at`  TIMESTAMP    NULL     DEFAULT NULL,
                PRIMARY KEY (`trc_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `top_racing_campaign_product` (
                `trcp_id`          INT NOT NULL AUTO_INCREMENT,
                `trcp_campaign_id` INT NOT NULL DEFAULT 0,
                `trcp_product_id`  INT NOT NULL DEFAULT 0,
                PRIMARY KEY (`trcp_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `data_product_crawl` (
                `id`                         INT   NOT NULL AUTO_INCREMENT,
                `name`                       VARCHAR(255) NOT NULL,
                `price_vuaduoc`              FLOAT NOT NULL,
                `price_discount`             FLOAT NOT NULL,
                `price_policies`             FLOAT NOT NULL,
                `price_crawl`                FLOAT NOT NULL,
                `price_difference`           FLOAT NOT NULL,
                `status`                     INT   NOT NULL DEFAULT 0,
                `price_sale_crawl_quality_2` FLOAT NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `data_product_crawl_date` (
                `id`   INT          NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `date` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");
    }

    public function down(): void
    {
        foreach ([
            'data_product_crawl_date', 'data_product_crawl',
            'top_racing_campaign_product', 'top_racing_campaign',
            'product_price_policies', 'product_liked',
            'products_hapu', 'products_images', 'products',
            'events', 'commission_plan',
        ] as $table) {
            $this->execute("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
