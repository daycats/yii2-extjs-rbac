<?php

use yii\db\Schema;
use yii\db\Migration;

class m151229_152849_alter_admin_menu_url extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $sql = <<<SQL
ALTER TABLE `dp_admin_menu_url`
DROP INDEX `alias` ,
ADD UNIQUE INDEX `alias_status` (`alias`, `status`) USING BTREE ;
SQL;
        $this->execute($sql);
    }

    public function safeDown()
    {
        $sql = <<<SQL
ALTER TABLE `dp_admin_menu_url`
DROP INDEX `alias_status` ,
ADD UNIQUE INDEX `alias` (`alias`) USING BTREE ;
SQL;
        $this->execute($sql);
    }
}
