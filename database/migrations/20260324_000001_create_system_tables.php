<?php

use Phinx\Migration\AbstractMigration;

/**
 * System / infrastructure tables:
 * languages, modules, settings_website, admin_translate, translate_display
 */
class CreateSystemTables extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `languages` (
                `lang_id`     INT         NOT NULL DEFAULT 0,
                `lang_name`   VARCHAR(50) DEFAULT NULL,
                `lang_path`   VARCHAR(15) DEFAULT 'home',
                `lang_image`  VARCHAR(255) DEFAULT NULL,
                `lang_domain` VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`lang_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `modules` (
                `mod_id`        INT          NOT NULL AUTO_INCREMENT,
                `mod_name`      VARCHAR(255) DEFAULT NULL,
                `mod_path`      VARCHAR(255) DEFAULT NULL,
                `mod_listname`  VARCHAR(255) DEFAULT NULL,
                `mod_listfile`  VARCHAR(255) DEFAULT NULL,
                `mod_order`     INT          DEFAULT 0,
                `mod_help`      MEDIUMTEXT,
                `lang_id`       INT          DEFAULT 1,
                `mod_checkloca` INT          DEFAULT 0,
                `mod_group`     VARCHAR(100) NOT NULL DEFAULT '',
                PRIMARY KEY (`mod_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `settings_website` (
                `swe_id`        INT          NOT NULL AUTO_INCREMENT,
                `swe_key`       VARCHAR(255) DEFAULT NULL,
                `swe_value_vn`  TEXT,
                `swe_type`      VARCHAR(255) DEFAULT NULL,
                `swe_create_time` INT        DEFAULT NULL,
                `swe_update_time` INT        DEFAULT NULL,
                `swe_label`     VARCHAR(255) NOT NULL,
                `swe_display`   INT          NOT NULL DEFAULT 1,
                PRIMARY KEY (`swe_id`),
                UNIQUE KEY `swe_key` (`swe_key`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `admin_translate` (
                `tra_keyword` VARCHAR(255) NOT NULL,
                `tra_text`    VARCHAR(255) DEFAULT NULL,
                `lang_id`     INT          DEFAULT NULL,
                `tra_source`  VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`tra_keyword`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `translate_display` (
                `tra_keyword` VARCHAR(255) NOT NULL DEFAULT '',
                `tra_text`    VARCHAR(255) DEFAULT NULL,
                `tra_source`  VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`tra_keyword`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");
    }

    public function down(): void
    {
        foreach (['translate_display', 'admin_translate', 'settings_website', 'modules', 'languages'] as $table) {
            $this->execute("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
