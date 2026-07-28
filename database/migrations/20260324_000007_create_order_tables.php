<?php

use Phinx\Migration\AbstractMigration;

/**
 * Order tables:
 * orders, order_products, order_commissions, order_logs,
 * order_product_commission, queue
 */
class CreateOrderTables extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `orders` (
                `ord_id`                  INT          NOT NULL AUTO_INCREMENT,
                `ord_code`                VARCHAR(255) NOT NULL,
                `ord_user_id`             INT          NOT NULL,
                `ord_status_code`         VARCHAR(255) NOT NULL COMMENT 'Trạng thái đơn hàng: Mới tạo - Đang vận chuyển - Đã nhận hàng - Thành công',
                `ord_payment_type`        VARCHAR(100) NOT NULL DEFAULT 'COD' COMMENT 'Loại thanh toán: COD, ATM, VISA, ...',
                `ord_payment_status`      INT          NOT NULL DEFAULT 0 COMMENT '0=Chưa thanh toán|1=Đã thanh toán',
                `ord_amount`              FLOAT        NOT NULL COMMENT 'Tổng tiền đơn hàng khách hàng phải trả',
                `ord_ship_name`           VARCHAR(255) NOT NULL,
                `ord_ship_address`        VARCHAR(255) NOT NULL,
                `ord_ship_phone`          VARCHAR(255) NOT NULL,
                `ord_ship_email`          VARCHAR(255) NOT NULL,
                `ord_note`                TEXT         NOT NULL,
                `ord_created_at`          TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `ord_updated_at`          TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `ord_deleted_at`          TIMESTAMP    NULL     DEFAULT NULL,
                `ord_commission_type`     INT          NOT NULL DEFAULT 1 COMMENT '1:Trừ vào giá|2:Cộng vào ví hoa hồng',
                `ord_payment_successed_at`TIMESTAMP    NULL     DEFAULT NULL,
                `ord_successed_at`        TIMESTAMP    NULL     DEFAULT NULL,
                `ord_active`              INT          NOT NULL DEFAULT 1,
                `ord_province_id`         INT          NOT NULL DEFAULT 0,
                `ord_district_id`         INT          NOT NULL DEFAULT 0,
                `ord_ward_id`             INT          NOT NULL DEFAULT 0,
                `ord_shipping_code`       VARCHAR(255) NOT NULL,
                `ord_shipping_fee`        INT          NOT NULL,
                `ord_shipping_carrier`    VARCHAR(255) NOT NULL,
                `ord_shipping_car`        VARCHAR(255) DEFAULT NULL,
                `ord_shipping_number_car` VARCHAR(255) DEFAULT NULL,
                `ord_shipping_car_start`  VARCHAR(255) DEFAULT NULL,
                `ord_shipping_car_phone`  VARCHAR(255) DEFAULT NULL,
                `ord_shipping_car_cod`    TINYINT      NOT NULL DEFAULT 1,
                `ord_shipping_note`       TEXT,
                `ord_address_id`          INT          NOT NULL DEFAULT 0,
                `ord_auto_shipping_fee`   INT          NOT NULL DEFAULT 0,
                `ord_lever_policies`      TINYINT      NOT NULL,
                `ord_shipping_at`         TIMESTAMP    NULL     DEFAULT NULL,
                `ord_pending_at`          TIMESTAMP    NULL     DEFAULT NULL,
                `ord_discount_admin`      FLOAT        NOT NULL DEFAULT 0 COMMENT 'Tiền khuyến mãi thêm từ hệ thống',
                `ord_vat`                 TINYINT      DEFAULT 0,
                `ord_admin_user_id`       INT          DEFAULT 63,
                `ord_admin_userprice_id`  INT          NOT NULL DEFAULT 0,
                `ord_status_process`      INT          NOT NULL DEFAULT 0,
                `ord_shipping_fee_car`    INT          NOT NULL DEFAULT 0,
                `ord_admin_group_user_id` INT          NOT NULL DEFAULT 0,
                `ord_stock_check_status`  TINYINT      NOT NULL DEFAULT 0,
                PRIMARY KEY (`ord_id`),
                KEY `ord_user_id`   (`ord_user_id`),
                KEY `ord_created_at`(`ord_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `order_products` (
                `orp_id`                  INT   NOT NULL AUTO_INCREMENT,
                `orp_ord_id`              INT   NOT NULL,
                `orp_product_id`          INT   NOT NULL,
                `orp_quantity`            INT   NOT NULL,
                `orp_price`               FLOAT NOT NULL,
                `orp_sale_price`          FLOAT NOT NULL,
                `orp_commit_current`      FLOAT NOT NULL,
                `orp_commission_product`  INT   DEFAULT 0,
                `orp_commission_buy`      INT   DEFAULT 0 COMMENT 'pro_commission giá mua thời điểm hiện tại',
                `orp_supplier_hapu`       VARCHAR(1000) DEFAULT NULL,
                `orp_price_hapu`          FLOAT NOT NULL DEFAULT 0,
                `orp_pharmacy_hapu`       VARCHAR(255) DEFAULT NULL,
                `orp_note_hapu`           TEXT,
                `orp_check_hapu_status`   TINYINT NOT NULL DEFAULT 0,
                `orp_check_hapu_note`     TEXT,
                `orp_hapu_status_pick`    TINYINT NOT NULL DEFAULT 0,
                PRIMARY KEY (`orp_id`),
                KEY `orp_ord_id`     (`orp_ord_id`),
                KEY `orp_product_id` (`orp_product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `order_commissions` (
                `orc_id`                  INT          NOT NULL AUTO_INCREMENT,
                `orc_order_id`            INT          NOT NULL,
                `orc_user_id`             INT          NOT NULL,
                `orc_status_code`         VARCHAR(255) NOT NULL COMMENT 'Trạng thái commit',
                `orc_amount`              FLOAT        NOT NULL COMMENT 'Hoa hồng chuyển cho khách',
                `orc_created_at`          TIMESTAMP    NULL DEFAULT CURRENT_TIMESTAMP,
                `orc_updated_at`          TIMESTAMP    NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `orc_deleted_at`          TIMESTAMP    NULL DEFAULT NULL,
                `orc_is_owner`            INT          NOT NULL DEFAULT 0 COMMENT 'Đơn hàng có phải do user mua hay không?',
                `orc_is_direct`           INT          NOT NULL DEFAULT 0 COMMENT 'Có phải là hoa hồng trực tiếp không?',
                `orc_type`                INT          NOT NULL DEFAULT 0 COMMENT 'Loại hoa hồng. 0:Tính tiền 1:Không tính tiền',
                `orc_vat`                 INT          NOT NULL DEFAULT 0,
                `orc_point`               INT          NOT NULL DEFAULT 0,
                `orc_event_id`            INT          NOT NULL DEFAULT 0,
                `orc_commission_buy`      INT          DEFAULT 0 COMMENT 'Phần trăm user được hưởng thời điểm mua',
                `orc_commission_backup`   FLOAT        DEFAULT 0,
                PRIMARY KEY (`orc_id`),
                KEY `orc_order_id_2` (`orc_order_id`),
                KEY `orc_user_id`    (`orc_user_id`),
                KEY `orc_is_owner`   (`orc_is_owner`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `order_logs` (
                `orl_id`                 INT          NOT NULL AUTO_INCREMENT,
                `orl_ord_id`             INT          NOT NULL,
                `orl_old_status_code`    VARCHAR(255) NOT NULL DEFAULT '',
                `orl_new_status_code`    VARCHAR(255) NOT NULL DEFAULT '',
                `orl_old_payment_status` VARCHAR(255) NOT NULL DEFAULT '',
                `orl_new_payment_status` VARCHAR(255) NOT NULL DEFAULT '',
                `orl_updated_at`         TIMESTAMP    NULL DEFAULT NULL,
                `orl_updated_by`         INT          NOT NULL DEFAULT 0,
                `orl_note`               TEXT         NOT NULL,
                PRIMARY KEY (`orl_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `order_product_commission` (
                `opc_id`        INT NOT NULL AUTO_INCREMENT,
                `opc_order_id`  INT NOT NULL,
                `opc_product_id`INT NOT NULL,
                `opc_user_id`   INT NOT NULL,
                `opc_quantity`  INT NOT NULL,
                `opc_commission`INT NOT NULL,
                `opc_is_owner`  INT NOT NULL DEFAULT 0 COMMENT 'Hoa hồng người đặt hàng hưởng',
                `opc_is_direct` INT NOT NULL DEFAULT 0 COMMENT 'Hoa hồng trực tiếp từ chiết khấu sản phẩm',
                `opc_type`      INT NOT NULL DEFAULT 0 COMMENT 'Loại hoa hồng. > 0 thì chỉ dùng để lên cấp',
                `opc_vat`       INT NOT NULL DEFAULT 0,
                PRIMARY KEY (`opc_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `queue` (
                `que_id`           INT          NOT NULL AUTO_INCREMENT,
                `que_name`         VARCHAR(255) DEFAULT NULL,
                `que_payload`      TEXT,
                `que_created_at`   DATETIME     DEFAULT NULL,
                `que_updated_at`   DATETIME     DEFAULT NULL,
                `que_running`      TINYINT(1)   DEFAULT 0,
                `que_running_at`   INT          DEFAULT 0,
                `que_attempts`     INT          DEFAULT NULL,
                `que_can_run_at`   INT          DEFAULT NULL,
                `que_can_run_local`TINYINT(1)   DEFAULT 0,
                PRIMARY KEY (`que_id`),
                KEY `que_name_index`  (`que_name`),
                KEY `can_run_at_index`(`que_can_run_at`),
                KEY `running_index`   (`que_running`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");
    }

    public function down(): void
    {
        foreach (['queue', 'order_product_commission', 'order_logs', 'order_commissions', 'order_products', 'orders'] as $table) {
            $this->execute("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
