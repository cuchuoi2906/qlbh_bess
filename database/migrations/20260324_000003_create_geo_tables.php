<?php

use Phinx\Migration\AbstractMigration;

/**
 * Geographic / address tables:
 * cities, province, district, ward
 */
class CreateGeoTables extends AbstractMigration
{
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `cities` (
                `cit_id`            INT            NOT NULL AUTO_INCREMENT,
                `cit_name`          VARCHAR(255)   DEFAULT NULL,
                `cit_alias`         VARCHAR(255)   DEFAULT NULL,
                `cit_alias_md5`     VARCHAR(32)    DEFAULT NULL,
                `cit_parent_id`     INT            DEFAULT 0,
                `cit_order`         INT            DEFAULT 1,
                `cit_map_longitude` DOUBLE         DEFAULT NULL,
                `cit_map_latitude`  DOUBLE         DEFAULT NULL,
                `cit_active`        INT            NOT NULL DEFAULT 1,
                `cit_area`          INT            NOT NULL DEFAULT 0,
                `cit_code`          VARCHAR(255)   DEFAULT NULL,
                `cit_cou_id`        INT            DEFAULT 0,
                PRIMARY KEY  (`cit_id`),
                UNIQUE KEY   `cit_code`     (`cit_code`),
                KEY          `cit_parent_id`(`cit_parent_id`),
                KEY          `cit_active`   (`cit_active`),
                KEY          `cit_area`     (`cit_area`),
                KEY          `cit_cou_id`   (`cit_cou_id`),
                KEY          `cit_alias_md5`(`cit_alias_md5`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `province` (
                `prov_id`   INT         NOT NULL AUTO_INCREMENT,
                `prov_name` VARCHAR(100) NOT NULL,
                `prov_type` VARCHAR(30)  NOT NULL,
                PRIMARY KEY (`prov_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `district` (
                `dis_id`          INT         NOT NULL AUTO_INCREMENT,
                `dis_name`        VARCHAR(100) NOT NULL,
                `dis_type`        VARCHAR(30)  NOT NULL,
                `dis_location`    VARCHAR(30)  NOT NULL,
                `dis_province_id` INT          NOT NULL,
                PRIMARY KEY (`dis_id`),
                KEY `dis_province_id` (`dis_province_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");

        $this->execute("
            CREATE TABLE IF NOT EXISTS `ward` (
                `war_id`          INT         NOT NULL AUTO_INCREMENT,
                `war_name`        VARCHAR(100) NOT NULL,
                `war_type`        VARCHAR(30)  NOT NULL,
                `war_location`    VARCHAR(30)  NOT NULL,
                `war_district_id` INT          NOT NULL,
                PRIMARY KEY (`war_id`),
                KEY `war_district_id` (`war_district_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
        ");
    }

    public function down(): void
    {
        foreach (['ward', 'district', 'province', 'cities'] as $table) {
            $this->execute("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
