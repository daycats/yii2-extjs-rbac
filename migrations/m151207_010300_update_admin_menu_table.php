<?php

use yii\db\Schema;
use yii\db\Migration;

class m151207_010300_update_admin_menu_table extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $sql = <<<SQL
UPDATE `dp_admin_menu` SET view_package = REPLACE (view_package, 'dp.view', 'DP.view') WHERE view_package LIKE 'dp.view%'
SQL;
        $this->execute($sql);
    }

    public function safeDown()
    {
        $sql = <<<SQL
UPDATE `dp_admin_menu` SET view_package = REPLACE (view_package, 'DP.view', 'dp.view') WHERE view_package LIKE 'dp.view%'
SQL;
        $this->execute($sql);
    }

}
