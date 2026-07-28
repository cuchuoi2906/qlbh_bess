<?php

use Phinx\Migration\AbstractMigration;

/**
 * Warehouse tables:
 * warehouse (nhập kho), sales_export (xuất kho / công nợ)
 */
class CreateWarehouseTables extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `warehouse` (
                `who_id`               INT            NOT NULL AUTO_INCREMENT   COMMENT 'ID phiếu nhập',
                `who_pro_id`           INT            NOT NULL                  COMMENT 'ID của sản phẩm',
                `who_product_name`     VARCHAR(255)   DEFAULT NULL              COMMENT 'Tên Sản phẩm',
                `who_import_date`      DATETIME       DEFAULT NULL              COMMENT 'Ngày nhập',
                `who_product_type`     VARCHAR(100)   DEFAULT NULL              COMMENT 'Loại nhập',
                `who_note`             TEXT           COMMENT 'Ghi chú',
                `who_quantity`         INT            DEFAULT 0                 COMMENT 'Số lượng sp Nhập',
                `who_quantity_available` INT          DEFAULT 0                 COMMENT 'Số lượng khả dụng',
                `who_quantity_packing` INT            DEFAULT 0                 COMMENT 'Số lượng PKiện',
                `who_carton_quantity`  INT            DEFAULT 0                 COMMENT 'Số lượng kiện/thùng',
                `who_unit_price`       DECIMAL(18,2)  DEFAULT 0.00             COMMENT 'Giá nhập',
                `who_other_cost`       DECIMAL(18,2)  DEFAULT 0.00             COMMENT 'Chi phí khác',
                `who_total_price`      DECIMAL(18,2)  DEFAULT 0.00             COMMENT 'Tổng nhập',
                `who_packaging_unit`   VARCHAR(50)    DEFAULT NULL              COMMENT 'Đơn vị đóng gói (chai, hộp, gói...)',
                `who_supplier_code`    VARCHAR(50)    DEFAULT NULL              COMMENT 'Nhà cung cấp (NCC) - Mã',
                `who_supplier_name`    VARCHAR(255)   DEFAULT NULL              COMMENT 'Tên nhà cung cấp',
                `who_receiver_name`    VARCHAR(255)   DEFAULT NULL              COMMENT 'Người nhận hàng',
                `who_warehouse_name`   VARCHAR(255)   DEFAULT NULL              COMMENT 'Kho nhận',
                `who_lot_number`       VARCHAR(50)    DEFAULT NULL              COMMENT 'Số lô/LOT',
                `who_mfg_date`         DATETIME       DEFAULT NULL              COMMENT 'Ngày sản xuất (MFG)',
                `who_exp_date`         DATETIME       DEFAULT NULL              COMMENT 'Hạn dùng EXP',
                `who_status`           VARCHAR(50)    DEFAULT 'pending'         COMMENT 'Trạng thái: pending, completed, cancelled',
                `who_created_by`       VARCHAR(100)   DEFAULT NULL              COMMENT 'Người tạo phiếu',
                `who_created_date`     DATETIME       DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
                `who_updated_date`     DATETIME       DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
                PRIMARY KEY (`who_id`),
                KEY `who_pro_id`       (`who_pro_id`),
                KEY `idx_import_date`  (`who_import_date`),
                KEY `idx_supplier`     (`who_supplier_name`),
                KEY `idx_lot_number`   (`who_lot_number`),
                KEY `idx_product_name` (`who_product_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
              COMMENT='Bảng quản lý nhập kho';
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `sales_export` (
                `sae_id`               INT            NOT NULL AUTO_INCREMENT   COMMENT 'ID phiếu xuất',
                `sae_pro_id`           INT            NOT NULL                  COMMENT 'ID của sản phẩm',
                `sae_who_id`           INT            NOT NULL DEFAULT 0        COMMENT 'ID bản ghi nhập kho (warehouse.who_id)',
                `sae_product_name`     VARCHAR(255)   DEFAULT NULL              COMMENT 'Tên Sản phẩm',
                `sae_export_date`      DATETIME       DEFAULT NULL              COMMENT 'Ngày xuất',
                `sae_product_type`     VARCHAR(100)   DEFAULT NULL              COMMENT 'Loại xuất',
                `sae_note`             TEXT           COMMENT 'Ghi chú',
                `sae_quantity_ban`     INT            DEFAULT 0                 COMMENT 'Số lượng sp Bán',
                `sae_quantity_kha_dung`INT            DEFAULT 0                 COMMENT 'Số lượng khả dụng (tồn kho tại thời điểm xuất)',
                `sae_quantity_con_lai` INT            DEFAULT 0                 COMMENT 'Số lượng còn lại sau khi xuất',
                `sae_unit_price`       DECIMAL(18,2)  DEFAULT 0.00             COMMENT 'Giá bán',
                `sae_other_cost`       DECIMAL(18,2)  DEFAULT 0.00             COMMENT 'Chi phí khác',
                `sae_total_ban`        DECIMAL(18,2)  DEFAULT 0.00             COMMENT 'Tổng Bán',
                `sae_customer_name`    VARCHAR(255)   DEFAULT NULL              COMMENT 'Khách hàng',
                `sae_customer_phone`   VARCHAR(20)    DEFAULT NULL              COMMENT 'Số điện thoại',
                `sae_customer_address` TEXT           COMMENT 'Địa chỉ/Kho nhận',
                `sae_lot_number`       VARCHAR(50)    DEFAULT NULL              COMMENT 'Số lô/LOT',
                `sae_mfg_date`         DATETIME       DEFAULT NULL              COMMENT 'Ngày sản xuất (MFG)',
                `sae_exp_date`         DATETIME       DEFAULT NULL              COMMENT 'Hạn dùng EXP',
                `sae_status`           VARCHAR(50)    DEFAULT 'pending'         COMMENT 'Trạng thái: pending, completed, cancelled',
                `sae_payment_status`   VARCHAR(50)    DEFAULT NULL              COMMENT 'Trạng thái thanh toán: Chưa thanh toán, Thanh toán một phần, Đã thanh toán',
                `sae_payment_date`     DATE           DEFAULT NULL              COMMENT 'Ngày thanh toán',
                `sae_created_by`       VARCHAR(100)   DEFAULT NULL              COMMENT 'Người tạo phiếu',
                `sae_created_date`     DATETIME       DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
                `sae_updated_date`     DATETIME       DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
                PRIMARY KEY (`sae_id`),
                KEY `idx_who_id`       (`sae_who_id`),
                KEY `idx_product_name` (`sae_product_name`),
                KEY `idx_export_date`  (`sae_export_date`),
                KEY `idx_lot_number`   (`sae_lot_number`),
                KEY `idx_customer`     (`sae_customer_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
              COMMENT='Bảng quản lý xuất kho/bán hàng';
        ");
    }

    public function down(): void
    {
        foreach (['sales_export', 'warehouse'] as $table) {
            $this->execute("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
