<?php

use yii\db\Schema;
use yii\db\Migration;

class m151203_044239_init extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->className() . '.sql');
        $this->execute($sql);
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0;');
        $this->dropTable('dp_admin_action');
        $this->dropTable('dp_admin_group');
        $this->dropTable('dp_admin_group_menu_relation');
        $this->dropTable('dp_admin_menu');
        $this->dropTable('dp_admin_menu_url');
        $this->dropTable('dp_admin_menu_url_relation');
        $this->dropTable('dp_admin_menu_url_rule');
        $this->dropTable('dp_admin_user');
        $this->dropTable('dp_admin_user_menu_relation');
    }

}
