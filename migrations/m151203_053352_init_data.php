<?php

use yii\db\Migration;

class m151203_053352_init_data extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->className() . '.sql');
        $this->execute($sql);
    }

    public function safeDown()
    {
        $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->className() . '.down.sql');
        $this->execute($sql);
    }

}
