<?php

use Phinx\Migration\AbstractMigration;

/**
 * Admin / permission tables:
 * admin_user, admin_user_category, admin_user_language, admin_user_right,
 * admin_user_website, admin_logs
 */
class CreateAdminTables extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `admin_user` (
                `adm_id`              INT          NOT NULL AUTO_INCREMENT,
                `adm_loginname`       VARCHAR(100) DEFAULT NULL,
                `adm_password`        VARCHAR(100) DEFAULT NULL,
                `adm_name`            VARCHAR(255) DEFAULT NULL,
                `adm_email`           VARCHAR(255) DEFAULT NULL,
                `adm_address`         VARCHAR(255) DEFAULT NULL,
                `adm_phone`           VARCHAR(255) DEFAULT NULL,
                `adm_mobile`          VARCHAR(255) DEFAULT NULL,
                `adm_access_module`   VARCHAR(255) DEFAULT NULL,
                `adm_access_category` TEXT,
                `adm_date`            INT          DEFAULT 0,
                `adm_isadmin`         TINYINT(1)   DEFAULT 0,
                `adm_active`          TINYINT(1)   DEFAULT 1,
                `lang_id`             TINYINT(1)   DEFAULT 1,
                `adm_delete`          INT          DEFAULT 0,
                `adm_all_category`    INT          DEFAULT NULL,
                `adm_all_website`     TINYINT(1)   DEFAULT 0,
                `adm_edit_all`        INT          DEFAULT 0,
                `admin_id`            INT          DEFAULT 0,
                `adm_type`            INT          NOT NULL DEFAULT 0,
                PRIMARY KEY (`adm_id`),
                KEY `adm_date` (`adm_date`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 ROW_FORMAT=FIXED;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `admin_user_category` (
                `auc_admin_id`    INT NOT NULL DEFAULT 0,
                `auc_category_id` INT NOT NULL DEFAULT 0
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `admin_user_language` (
                `aul_admin_id` INT NOT NULL DEFAULT 0,
                `aul_lang_id`  INT NOT NULL DEFAULT 0,
                PRIMARY KEY (`aul_admin_id`, `aul_lang_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `admin_user_right` (
                `adu_admin_id`        INT NOT NULL AUTO_INCREMENT,
                `adu_admin_module_id` INT NOT NULL DEFAULT 0,
                `adu_add`             INT DEFAULT 0,
                `adu_edit`            INT DEFAULT 0,
                `adu_delete`          INT DEFAULT 0,
                PRIMARY KEY (`adu_admin_id`, `adu_admin_module_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `admin_user_website` (
                `auw_user_id` INT NOT NULL DEFAULT 0,
                `auw_web_id`  INT NOT NULL DEFAULT 0,
                PRIMARY KEY (`auw_user_id`, `auw_web_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `admin_logs` (
                `adl_id`             INT          NOT NULL AUTO_INCREMENT,
                `adl_admin_user_id`  INT          NOT NULL COMMENT 'Admin id',
                `adl_action`         VARCHAR(255) NOT NULL COMMENT 'Hành động (ADD|EDIT|DELETE|ACTIVE)',
                `adl_record_id`      INT          NOT NULL DEFAULT 0,
                `adl_record_title`   VARCHAR(255) NOT NULL,
                `adl_created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `adl_updated_at`     TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `adl_deleted_at`     TIMESTAMP    NULL     DEFAULT NULL,
                PRIMARY KEY (`adl_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");
    }

    public function down(): void
    {
        foreach (['admin_logs', 'admin_user_website', 'admin_user_right', 'admin_user_language', 'admin_user_category', 'admin_user'] as $table) {
            $this->execute("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
