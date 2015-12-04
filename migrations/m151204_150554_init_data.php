<?php

use yii\db\Schema;
use yii\db\Migration;

class m151204_150554_init_data extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . $this->className() . '.sql');
        $this->execute($sql);
    }

    public function safeDown()
    {
        return false;
    }
}
