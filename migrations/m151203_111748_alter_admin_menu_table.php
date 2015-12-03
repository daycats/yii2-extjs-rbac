<?php

use yii\db\Schema;
use yii\db\Migration;

class m151203_111748_alter_admin_menu_table extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $sql = <<<SQL
ALTER TABLE `dp_admin_menu`
ADD COLUMN `params`  text NULL COMMENT '参数' AFTER `view_package`;
SQL;
        $this->execute($sql);
    }

    public function safeDown()
    {
        $this->dropColumn('dp_admin_menu', 'params');
    }

}
