<?php

use Phinx\Migration\AbstractMigration;

/**
 * User tables:
 * users, user_address, user_banks, user_cart, user_cart_admin,
 * user_wallet, user_wallet_log, user_money_add, user_money_add_request,
 * user_money_logs, user_payment_request, user_reward, user_right,
 * user_transfer, user_device, user_change_phone,
 * best_team, survey_users, policies_by_produc_import,
 * money_load_batch, money_add_request_notify
 */
class CreateUserTables extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `users` (
                `use_id`                    INT          NOT NULL AUTO_INCREMENT COMMENT 'id cua vatgia.com',
                `use_id_vatgia`             INT          NOT NULL DEFAULT 0,
                `use_active`                INT          NOT NULL DEFAULT 0,
                `use_login`                 VARCHAR(100) DEFAULT NULL,
                `use_loginname`             VARCHAR(100) DEFAULT NULL,
                `use_password`              VARCHAR(50)  DEFAULT NULL,
                `use_birthdays`             VARCHAR(10)  DEFAULT NULL,
                `use_gender`                TINYINT      NOT NULL,
                `use_city`                  INT          DEFAULT 1,
                `use_phone`                 VARCHAR(50)  DEFAULT NULL,
                `use_email`                 VARCHAR(100) DEFAULT NULL,
                `use_address`               VARCHAR(255) DEFAULT NULL,
                `use_date`                  INT          DEFAULT 0,
                `use_group`                 INT          DEFAULT 0,
                `use_name`                  VARCHAR(255) DEFAULT NULL,
                `use_mobile`                VARCHAR(255) DEFAULT NULL,
                `use_hits`                  INT          DEFAULT 1,
                `use_avatar`                VARCHAR(255) DEFAULT NULL,
                `use_idvg_access_token`     VARCHAR(255) DEFAULT NULL,
                `use_security`              VARCHAR(255) DEFAULT NULL,
                `use_updated_at`            TIMESTAMP    NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `use_supplier`              TINYINT(1)   DEFAULT 0,
                `use_referral_id`           INT          NOT NULL DEFAULT 0 COMMENT 'Mã người giới thiệu',
                `use_reset_token`           VARCHAR(255) NOT NULL DEFAULT '',
                `use_forgot_password_confirm_code` VARCHAR(255) DEFAULT '' COMMENT 'Mã đổi password',
                `use_identity_number`       VARCHAR(255) DEFAULT NULL COMMENT 'Số CMND',
                `use_identity_front_image`  VARCHAR(255) DEFAULT NULL COMMENT 'Ảnh CMND mặt trước',
                `use_identity_back_image`   VARCHAR(255) DEFAULT NULL COMMENT 'Ảnh CMND mặt sau',
                `use_lft`                   INT          NOT NULL DEFAULT 0,
                `use_rgt`                   INT          NOT NULL DEFAULT 0,
                `use_depth`                 INT          NOT NULL DEFAULT 0,
                `use_level`                 INT          NOT NULL DEFAULT 1,
                `use_commission`            FLOAT        NOT NULL DEFAULT 0,
                `use_money_add`             FLOAT        NOT NULL DEFAULT 0,
                `use_total_direct_refer`    INT          NOT NULL DEFAULT 0 COMMENT 'Tổng người trực tiếp giới thiệu',
                `use_total_refer`           INT          NOT NULL DEFAULT 0 COMMENT 'Tổng người giới thiệu',
                `use_register_confirm_code` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Mã xác thực',
                `use_trade_code`            VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Mã giao dịch',
                `use_deleted_at`            TIMESTAMP    NULL DEFAULT NULL,
                `use_status`                INT          NOT NULL DEFAULT 1,
                `use_created_at`            TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `use_all_level`             VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Cây phân cấp cho user',
                `use_premium`               INT          NOT NULL DEFAULT 0,
                `use_premium_commission`    INT          NOT NULL DEFAULT 0,
                `use_family`                INT          NOT NULL DEFAULT 0,
                `use_referer_code`          VARCHAR(255) NOT NULL,
                `use_is_seller`             INT          NOT NULL DEFAULT 0 COMMENT 'User mua hàng với mức chiết khấu cao nhất',
                `use_province_id`           INT          DEFAULT 0,
                `use_district_id`           INT          DEFAULT 0,
                `use_ward_id`               INT          DEFAULT 0,
                `use_address_register`      VARCHAR(3000) DEFAULT '',
                `use_province_registry`     VARCHAR(1000) DEFAULT NULL,
                `use_district_registry`     VARCHAR(5000) DEFAULT NULL,
                `use_job_code`              INT          DEFAULT 0,
                `use_sale`                  INT          DEFAULT 0,
                `use_source`                INT          DEFAULT 0,
                `user_sale_id`              INT          DEFAULT 0,
                `use_content`               TEXT,
                `use_type`                  INT          DEFAULT 0,
                `use_partner_date`          TIMESTAMP    NULL DEFAULT NULL COMMENT 'Ngày set làm nhà phân phối',
                `use_partner_note`          TEXT         NOT NULL COMMENT 'Ghi chú nhà phân phối',
                `use_note`                  TEXT,
                `use_cccd_img`              VARCHAR(1000) DEFAULT NULL,
                `use_business_license_img`  VARCHAR(1000) DEFAULT NULL,
                `use_pharma_license_img`    VARCHAR(1000) DEFAULT NULL,
                `use_gpp_cert_img`          VARCHAR(1000) DEFAULT NULL,
                `use_tax_code`              VARCHAR(100) DEFAULT NULL,
                PRIMARY KEY (`use_id`),
                KEY `index`                  (`use_login`),
                KEY `index_email`            (`use_email`),
                KEY `use_lft`                (`use_lft`, `use_rgt`),
                KEY `use_depth`              (`use_depth`),
                KEY `use_referral_id`        (`use_referral_id`),
                KEY `use_level`              (`use_level`),
                KEY `use_total_direct_refer` (`use_total_direct_refer`),
                KEY `use_total_refer`        (`use_total_refer`),
                KEY `use_sale`               (`use_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_address` (
                `usa_id`          INT          NOT NULL AUTO_INCREMENT,
                `usa_is_main`     INT          NOT NULL DEFAULT 0,
                `usa_user_id`     INT          NOT NULL,
                `usa_title`       VARCHAR(255) NOT NULL,
                `usa_name`        VARCHAR(255) NOT NULL,
                `usa_phone`       VARCHAR(255) NOT NULL,
                `usa_address`     VARCHAR(255) NOT NULL,
                `usa_ward_id`     INT          NOT NULL,
                `usa_district_id` INT          NOT NULL,
                `usa_province_id` INT          NOT NULL,
                `usa_created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `usa_updated_at`  TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `usa_deleted_at`  TIMESTAMP    NULL     DEFAULT NULL,
                PRIMARY KEY (`usa_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_banks` (
                `usb_id`             INT          NOT NULL AUTO_INCREMENT,
                `usb_user_id`        INT          NOT NULL COMMENT 'User id',
                `usb_bank_name`      VARCHAR(255) NOT NULL COMMENT 'Tên ngân hàng',
                `usb_account_name`   VARCHAR(100) NOT NULL COMMENT 'Tên chủ tài khoản',
                `usb_account_number` VARCHAR(50)  NOT NULL COMMENT 'Số tài khoản',
                `usb_branch`         VARCHAR(255) NOT NULL COMMENT 'Chi nhánh',
                `usb_created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `usb_updated_at`     TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`usb_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_cart` (
                `usc_id`         INT       NOT NULL AUTO_INCREMENT,
                `usc_user_id`    INT       NOT NULL COMMENT 'User id',
                `usc_product_id` INT       NOT NULL COMMENT 'Product ID',
                `usc_quantity`   INT       NOT NULL COMMENT 'Số lượng',
                `usc_created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `usc_updated_at` TIMESTAMP NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `usc_price`      INT       NOT NULL DEFAULT 0 COMMENT 'Giá tùy chỉnh của npp',
                PRIMARY KEY (`usc_id`),
                UNIQUE KEY `usc_user_id` (`usc_user_id`, `usc_product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_cart_admin` (
                `usc_id`         INT       NOT NULL AUTO_INCREMENT,
                `usc_user_id`    INT       NOT NULL COMMENT 'User id',
                `usc_product_id` INT       NOT NULL COMMENT 'Product ID',
                `usc_quantity`   INT       NOT NULL COMMENT 'Số lượng',
                `usc_created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `usc_updated_at` TIMESTAMP NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`usc_id`),
                UNIQUE KEY `usc_user_id` (`usc_user_id`, `usc_product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_wallet` (
                `usw_id`         INT       NOT NULL AUTO_INCREMENT,
                `usw_user_id`    INT       NOT NULL COMMENT 'User id',
                `usw_commission` FLOAT     NOT NULL DEFAULT 0 COMMENT 'Ví tiền hoa hồng',
                `usw_charge`     FLOAT     NOT NULL DEFAULT 0 COMMENT 'Ví tiền nạp',
                `usw_created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `usw_updated_at` TIMESTAMP NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `usw_vat`        INT       NOT NULL DEFAULT 0,
                PRIMARY KEY (`usw_id`),
                UNIQUE KEY `usw_user_id` (`usw_user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_wallet_log` (
                `uwl_id`              INT          NOT NULL AUTO_INCREMENT,
                `uwl_use_id`          INT          NOT NULL,
                `uwl_wallet_id`       INT          NOT NULL,
                `uwl_admin_id`        INT          NOT NULL,
                `uwl_ip`              VARCHAR(255) NOT NULL COMMENT 'Ip',
                `uwl_money_old`       FLOAT        NOT NULL COMMENT 'Tiền cũ có trong ví',
                `uwl_money_new`       FLOAT        NOT NULL COMMENT 'Tiền sau khi thêm',
                `uwl_money_add`       FLOAT        NOT NULL DEFAULT 0 COMMENT 'Tiền được thêm vào',
                `uwl_money_reduction` FLOAT        NOT NULL DEFAULT 0 COMMENT 'Tiền giảm đi',
                `uwl_type`            INT          NOT NULL DEFAULT 0 COMMENT '0:admin|1:user',
                `uwl_wallet_type`     INT          NOT NULL DEFAULT 0 COMMENT '0:tài khoản chính|1:tài khoản tạm giữ',
                `uwl_note`            VARCHAR(255) DEFAULT NULL COMMENT 'Nguyên nhân',
                `uwl_created_at`      TIMESTAMP    NULL DEFAULT CURRENT_TIMESTAMP,
                `uwl_updated_at`      TIMESTAMP    NULL DEFAULT NULL,
                PRIMARY KEY (`uwl_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_money_add` (
                `uma_id`         INT       NOT NULL AUTO_INCREMENT,
                `uma_user_id`    INT       NOT NULL COMMENT 'User id',
                `uma_amount`     FLOAT     NOT NULL DEFAULT 0 COMMENT 'Số tiền nạp',
                `uma_type`       VARCHAR(30) NOT NULL DEFAULT 'ADMIN' COMMENT 'Loại giao dịch',
                `uma_note`       LONGTEXT  NOT NULL,
                `uma_created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `uma_updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`uma_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_money_add_request` (
                `umar_id`             INT          NOT NULL AUTO_INCREMENT,
                `umar_user_id`        INT          NOT NULL COMMENT 'User id',
                `umar_amount`         FLOAT        NOT NULL DEFAULT 0 COMMENT 'Số tiền nạp',
                `umar_order_id`       INT          NOT NULL DEFAULT 0 COMMENT 'Order id cần thanh toán',
                `umar_type`           VARCHAR(255) NOT NULL COMMENT 'Phân biệt loại thanh toán',
                `umar_transaction_id` VARCHAR(255) NOT NULL DEFAULT 'NEW' COMMENT 'Mã giao dịch',
                `umar_status`         VARCHAR(30)  NOT NULL COMMENT 'trạng thái',
                `umar_note`           LONGTEXT     NOT NULL,
                `umar_created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `umar_updated_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`umar_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_money_logs` (
                `uml_id`          INT          NOT NULL AUTO_INCREMENT,
                `uml_user_id`     INT          NOT NULL COMMENT 'ID trong bảng user',
                `uml_amount`      FLOAT        NOT NULL COMMENT 'Số tiền được cộng',
                `uml_current`     CHAR(34)     DEFAULT NULL,
                `uml_note`        VARCHAR(254) NOT NULL COMMENT 'Ghi rõ note của giao dịch nào',
                `uml_type`        VARCHAR(50)  NOT NULL COMMENT 'Loại Ví: Hoa Hồng, Tiền Add',
                `uml_created_at`  TIMESTAMP    NULL DEFAULT CURRENT_TIMESTAMP,
                `uml_source`      INT          NOT NULL DEFAULT 0 COMMENT 'Nguồn nạp tiền. 0:admin|1:từ ngân hàng|2:chuyển từ ví hoa hồng',
                `uml_source_type` INT          NOT NULL DEFAULT 0 COMMENT '1:đối tượng đơn hàng|2:đối tượng người dùng|0:mặc định',
                `uml_log_type`    INT          NOT NULL DEFAULT 0 COMMENT '0:log về tiền|1:log về thanh toán đơn hàng',
                PRIMARY KEY (`uml_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_payment_request` (
                `upr_id`           INT   NOT NULL AUTO_INCREMENT,
                `upr_user_id`      INT   NOT NULL COMMENT 'User id',
                `upr_money`        FLOAT NOT NULL COMMENT 'Số tiền muốn rút',
                `upr_bank_id`      INT   NOT NULL COMMENT 'Tài khoản ngân hàng',
                `upr_is_paid`      INT   NOT NULL COMMENT 'Đã được thanh toán?',
                `upr_paid_time`    INT   DEFAULT 0,
                `upr_created_at`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `upr_note`         TEXT NOT NULL,
                `upr_admin_accept` INT   DEFAULT NULL COMMENT 'Admin accept payment request',
                `upr_ip`           INT UNSIGNED DEFAULT NULL COMMENT 'IP admin',
                PRIMARY KEY (`upr_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_reward` (
                `usr_id`                         INT          NOT NULL AUTO_INCREMENT,
                `usr_user_id`                    INT          NOT NULL,
                `usr_total_point`                BIGINT       NOT NULL DEFAULT 0,
                `usr_total_owner_point`          BIGINT       NOT NULL DEFAULT 0,
                `usr_marketing_commission`       BIGINT       NOT NULL DEFAULT 0,
                `usr_marketing_commission_real`  BIGINT       NOT NULL DEFAULT 0,
                `usr_percent`                    INT          NOT NULL DEFAULT 0 COMMENT 'Phần trăm tính thưởng',
                `usr_independent_point`          BIGINT       NOT NULL DEFAULT 0 COMMENT 'Số point còn lại sau khi đã trừ các nhóm độc lập',
                `usr_independent`                INT          NOT NULL DEFAULT 0 COMMENT 'Có phải nhóm độc lập hay không?',
                `usr_number_independent_childs`  INT          NOT NULL DEFAULT 0 COMMENT 'Số nhóm độc lập',
                `usr_leader_commission`          BIGINT       NOT NULL DEFAULT 0,
                `usr_month`                      VARCHAR(255) NOT NULL COMMENT 'Tháng tính thưởng',
                `usr_year`                       VARCHAR(255) NOT NULL COMMENT 'Năm tính thưởng',
                `usr_status`                     INT          NOT NULL DEFAULT 0 COMMENT 'Trạng thái trả thưởng: 0-chưa chốt, 1-đã chốt',
                `usr_created_at`                 TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `usr_updated_at`                 TIMESTAMP    NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `usr_deleted_at`                 TIMESTAMP    NULL DEFAULT NULL,
                PRIMARY KEY (`usr_id`),
                UNIQUE KEY `usr_user_id` (`usr_user_id`, `usr_month`, `usr_year`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_right` (
                `ur_code`     INT UNSIGNED NOT NULL DEFAULT 0,
                `ur_variable` VARCHAR(30)  DEFAULT NULL,
                `ur_quantity` INT          DEFAULT NULL,
                PRIMARY KEY (`ur_code`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_transfer` (
                `ust`            INT NOT NULL AUTO_INCREMENT,
                `ust_user_id`    INT NOT NULL COMMENT 'User id',
                `ust_amount`     INT NOT NULL DEFAULT 0,
                `ust_created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`ust`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_device` (
                `usd_id`              INT          NOT NULL AUTO_INCREMENT,
                `usd_user_id`         INT          NOT NULL COMMENT 'User id',
                `usd_registration_id` VARCHAR(255) NOT NULL COMMENT 'Device ID',
                `usd_device_info`     TEXT         COMMENT 'Thông tin thiết bị (JSON)',
                `usd_created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `usd_updated_at`      TIMESTAMP    NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`usd_id`),
                UNIQUE KEY `usd_user_id` (`usd_user_id`, `usd_registration_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `user_change_phone` (
                `ucp_id`                    INT        NOT NULL AUTO_INCREMENT,
                `ucp_user_id`               INT        NOT NULL COMMENT 'User id',
                `ucp_old_phone`             VARCHAR(20) DEFAULT NULL COMMENT 'Số điện thoại cũ',
                `ucp_new_phone`             VARCHAR(20) NOT NULL COMMENT 'Số điện thoại mới',
                `ucp_old_code`              VARCHAR(6) DEFAULT NULL COMMENT 'Mã xác thực gửi về số cũ',
                `ucp_new_code`              VARCHAR(6) NOT NULL COMMENT 'Mã xác thực gửi về số mới',
                `ucp_can_confirm_new_phone` INT        NOT NULL DEFAULT 1,
                `ucp_created_at`            TIMESTAMP  NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `ucp_updated_at`            TIMESTAMP  NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `ucp_deleted_at`            TIMESTAMP  NULL DEFAULT NULL,
                PRIMARY KEY (`ucp_id`),
                UNIQUE KEY `ucp_user_id` (`ucp_user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `best_team` (
                `bes_id`         INT  NOT NULL AUTO_INCREMENT,
                `bes_user_id`    INT  NOT NULL,
                `bes_date`       DATE NOT NULL,
                `bes_point`      INT  NOT NULL DEFAULT 0,
                `bes_type`       INT  NOT NULL DEFAULT 0 COMMENT 'Loại xếp hạng. 0 => Team mạnh nhất',
                `bes_product_id` INT  NOT NULL DEFAULT 0,
                `bes_money`      INT  NOT NULL DEFAULT 0,
                `bes_money_point`INT  NOT NULL DEFAULT 0,
                PRIMARY KEY (`bes_id`),
                UNIQUE KEY `bes_user_id` (`bes_user_id`, `bes_date`, `bes_type`, `bes_product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `survey_users` (
                `survey_id`               INT          NOT NULL AUTO_INCREMENT COMMENT 'id khao sat',
                `survey_userId`           INT          DEFAULT NULL,
                `survey_userId_admin`     INT          DEFAULT NULL,
                `survey_job`              VARCHAR(255) DEFAULT NULL COMMENT 'Nghề nghiệp',
                `survey_regis_reason`     VARCHAR(255) DEFAULT NULL COMMENT 'Lý do đăng ký',
                `survey_busined`          VARCHAR(255) DEFAULT NULL COMMENT 'Đã từng kinh doanh',
                `survey_busines_date`     VARCHAR(255) DEFAULT NULL COMMENT 'Thời gian kinh doanh',
                `survey_busines_desired`  VARCHAR(255) DEFAULT NULL COMMENT 'Hình thức kinh doanh mong muốn',
                `survey_note`             MEDIUMTEXT   COMMENT 'Ghi chú',
                `survey_created_at`       DATETIME     DEFAULT NULL,
                `survey_updated_at`       DATETIME     DEFAULT NULL,
                PRIMARY KEY (`survey_id`),
                KEY `index` (`survey_userId`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `policies_by_produc_import` (
                `policies_id`         INT          NOT NULL AUTO_INCREMENT,
                `policies_name`       VARCHAR(255) DEFAULT NULL,
                `policies_pro_id`     VARCHAR(255) DEFAULT NULL,
                `policies_quantity`   INT          DEFAULT NULL,
                `policies_charge`     VARCHAR(255) DEFAULT NULL COMMENT 'Số tiền chiết khấu',
                `policies_status`     INT          DEFAULT NULL,
                `policies_created_at` DATETIME     DEFAULT NULL,
                `policies_updated_at` DATETIME     DEFAULT NULL,
                `policies_content`    TEXT         COMMENT 'Nội dung góp ý',
                PRIMARY KEY (`policies_id`),
                KEY `index` (`policies_pro_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `money_load_batch` (
                `money_id`               INT          NOT NULL AUTO_INCREMENT COMMENT 'id khao sat',
                `money_name`             VARCHAR(255) DEFAULT NULL COMMENT 'Tên file export',
                `money_user_id`          INT          DEFAULT NULL,
                `money_admin_id`         INT          DEFAULT NULL COMMENT 'id người sửa',
                `money_admin_id_import`  INT          DEFAULT NULL COMMENT 'id import',
                `money_charge`           VARCHAR(255) DEFAULT NULL COMMENT 'Số tiền nạp',
                `money_status`           INT          DEFAULT NULL,
                `money_type`             INT          DEFAULT NULL COMMENT 'Hình thức cộng tiền',
                `money_created_at`       DATETIME     DEFAULT NULL,
                `money_updated_at`       DATETIME     DEFAULT NULL,
                `money_content`          TEXT         COMMENT 'Nội dung góp ý',
                PRIMARY KEY (`money_id`),
                KEY `index`  (`money_user_id`),
                KEY `index1` (`money_name`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `money_add_request_notify` (
                `marn_id`             INT          NOT NULL AUTO_INCREMENT,
                `marn_account_name`   VARCHAR(255) DEFAULT NULL COMMENT 'Tên chủ tài khoản',
                `marn_account_number` VARCHAR(255) DEFAULT NULL COMMENT 'Số tài khoản',
                `marn_bank_name`      VARCHAR(255) DEFAULT NULL COMMENT 'Tên ngân hàng',
                `marn_trade_code`     VARCHAR(255) DEFAULT NULL COMMENT 'Mã giao dịch',
                `marn_money`          INT          NOT NULL DEFAULT 0 COMMENT 'Số tiền',
                `marn_order_id`       INT          NOT NULL DEFAULT 0 COMMENT 'Mã đơn hàng',
                `marn_image`          VARCHAR(255) DEFAULT NULL COMMENT 'Ảnh giao dịch',
                `marn_type`           INT          NOT NULL DEFAULT 0 COMMENT 'Loại yêu cầu. 0=nạp tiền, 1=thanh toán đơn hàng',
                `marn_user_id`        INT          NOT NULL COMMENT 'User',
                `marn_created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `marn_updated_at`     TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `marn_deleted_at`     TIMESTAMP    NULL     DEFAULT NULL,
                `marn_status`         INT          NOT NULL DEFAULT 0 COMMENT 'Trạng thái: 0=Chờ|-1=Đã hủy|1=Đã nạp',
                `marn_admin_id`       INT          NOT NULL DEFAULT 0,
                `marn_note`           TEXT,
                `marn_money_add`      INT          NOT NULL DEFAULT 0,
                PRIMARY KEY (`marn_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");
    }

    public function down(): void
    {
        foreach ([
            'money_add_request_notify', 'money_load_batch', 'policies_by_produc_import',
            'survey_users', 'best_team', 'user_change_phone', 'user_device', 'user_transfer',
            'user_right', 'user_reward', 'user_payment_request', 'user_money_logs',
            'user_money_add_request', 'user_money_add', 'user_wallet_log', 'user_wallet',
            'user_cart_admin', 'user_cart', 'user_banks', 'user_address', 'users',
        ] as $table) {
            $this->execute("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
