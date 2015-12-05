<?php

use yii\db\Migration;

class m151205_153317_update_config_name extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->update('dp_config', ['name' => 'system.loadingText'], ['name' => 'system.loading_text']);
    }

    public function safeDown()
    {
        $this->update('dp_config', ['name' => 'system.loading_text'], ['name' => 'system.loadingText']);
    }

}
