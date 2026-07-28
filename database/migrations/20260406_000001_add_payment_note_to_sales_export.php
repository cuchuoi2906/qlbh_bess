<?php

use Phinx\Migration\AbstractMigration;

class AddPaymentNoteToSalesExport extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            ALTER TABLE `sales_export`
            ADD COLUMN `sae_payment_note` TEXT DEFAULT NULL COMMENT 'Ghi chú thanh toán'
            AFTER `sae_payment_date`
        ");
    }

    public function down(): void
    {
        $this->execute("ALTER TABLE `sales_export` DROP COLUMN `sae_payment_note`");
    }
}
