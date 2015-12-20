<?php

use yii\db\Schema;
use yii\db\Migration;

class m151220_054913_alter_admin_menu extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $sql = <<<SQL
ALTER TABLE `dp_admin_menu`
MODIFY COLUMN `parent_id`  smallint(5) UNSIGNED NULL DEFAULT 0 COMMENT '父id,对应本表的menu_id字段' AFTER `menu_id`;

update dp_admin_menu set parent_id=null where parent_id=0;

ALTER TABLE `dp_admin_menu` ADD FOREIGN KEY (`parent_id`) REFERENCES `dp_admin_menu` (`menu_id`) ON DELETE RESTRICT ON UPDATE CASCADE;
SQL;
        $this->execute($sql);

    }

    public function safeDown()
    {
        $sql = <<<SQL
ALTER TABLE `dp_admin_menu` DROP FOREIGN KEY `dp_admin_menu_ibfk_1`;
update dp_admin_menu set parent_id=0 where parent_id is null;
ALTER TABLE `dp_admin_menu`
MODIFY COLUMN `parent_id`  smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id,对应本表的menu_id字段' AFTER `menu_id`;
SQL;
        $this->execute($sql);
    }
}
