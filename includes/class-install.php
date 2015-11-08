<?php
namespace WeDevs\ERP\Accounting;

/**
 * Installer class
 */
class Install {

    public function install() {
        $this->create_tables();
    }

    /**
     * Create necessary tables
     *
     * @since 0.1
     *
     * @return  void
     */
    public function create_tables() {
        global $wpdb;

        $collate = '';

        if ( $wpdb->has_cap( 'collation' ) ) {
            if ( ! empty($wpdb->charset ) ) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }

            if ( ! empty($wpdb->collate ) ) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }

        $table_schema = [
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}erp_ac_chart_classes` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(100) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}erp_ac_chart_types` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(60) NOT NULL DEFAULT '',
              `class_id` tinyint(3) NOT NULL,
              PRIMARY KEY (`id`),
              KEY `class_id` (`class_id`)
            ) $collate;",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}erp_ac_journals` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `ledger_id` int(11) unsigned NOT NULL,
              `transaction_id` bigint(20) unsigned NOT NULL,
              `type` varchar(20) DEFAULT NULL,
              `debit` float(10,2) unsigned DEFAULT NULL,
              `credit` float(10,2) unsigned DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ledger_id` (`ledger_id`),
              KEY `transaction_id` (`transaction_id`)
            ) $collate;",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}erp_ac_ledger` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `code` varchar(10) DEFAULT NULL,
              `name` varchar(100) DEFAULT NULL,
              `description` text,
              `parent` int(11) unsigned NOT NULL DEFAULT '0',
              `type_id` int(3) unsigned NOT NULL DEFAULT '0',
              `currency` varchar(10) DEFAULT '',
              `tax` decimal(2,2) DEFAULT NULL,
              `cash_account` tinyint(2) unsigned NOT NULL DEFAULT '0',
              `reconcile` tinyint(2) unsigned NOT NULL DEFAULT '0',
              `system` tinyint(2) unsigned NOT NULL DEFAULT '0',
              `active` tinyint(2) unsigned NOT NULL DEFAULT '1',
              PRIMARY KEY (`id`),
              KEY `code` (`code`),
              KEY `type_id` (`type_id`),
              KEY `parent` (`parent`)
            ) $collate;",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}erp_ac_banks` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `ledger_id` int(10) unsigned DEFAULT NULL,
              `account_number` varchar(20) DEFAULT NULL,
              `bank_name` varchar(30) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ledger_id` (`ledger_id`)
            ) $collate;",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}erp_ac_transactions` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `type` varchar(10) DEFAULT NULL,
              `form_type` varchar(20) DEFAULT NULL,
              `status` varchar(20) DEFAULT NULL,
              `user_id` int(10) unsigned DEFAULT NULL,
              `billing_address` tinytext,
              `ref` varchar(50) DEFAULT NULL,
              `summary` text,
              `issue_date` date DEFAULT NULL,
              `due_date` date DEFAULT NULL,
              `currency` varchar(10) DEFAULT NULL,
              `conversion_rate` decimal(2,2) unsigned DEFAULT NULL,
              `total` float(10,2) DEFAULT '0.00',
              `trans_total` float(10,2) DEFAULT '0.00',
              `files` varchar(255) DEFAULT NULL,
              `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
              `created_by` int(11) unsigned DEFAULT NULL,
              `created_at` datetime DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `user_id` (`user_id`),
              KEY `type` (`type`),
              KEY `status` (`status`),
              KEY `issue_date` (`issue_date`)
            ) $collate;",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}erp_ac_transaction_items` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `transaction_id` bigint(20) unsigned DEFAULT NULL,
              `journal_id` bigint(20) unsigned DEFAULT NULL,
              `product_id` int(10) unsigned DEFAULT NULL,
              `description` text,
              `qty` tinyint(5) unsigned NOT NULL DEFAULT '1',
              `unit_price` float(10,2) unsigned NOT NULL DEFAULT '0.00',
              `discount` tinyint(3) unsigned NOT NULL DEFAULT '0',
              `tax` tinyint(3) unsigned NOT NULL DEFAULT '0',
              `line_total` float(10,2) unsigned NOT NULL DEFAULT '0.00',
              `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`),
              KEY `transaction_id` (`transaction_id`),
              KEY `journal_id` (`journal_id`),
              KEY `product_id` (`product_id`)
            ) $collate;",
        ];

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        foreach ( $table_schema as $table ) {
            dbDelta( $table );
        }
    }
}