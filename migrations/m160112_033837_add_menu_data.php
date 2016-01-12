<?php

use yii\db\Migration;

class m160112_033837_add_menu_data extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->db->createCommand()
            ->insert('dp_admin_menu_url', [
                'name' => '菜单拖拽排序',
                'alias' => 'admin.menu.drop',
                'route' => '/admin/menu/drop',
                'method' => 'POST',
                'status' => 1,
            ])
            ->execute();

        $this->db->createCommand()
            ->insert('dp_admin_menu_url_relation', [
                'menu_id' => 5,
                'url_id' => $this->db->lastInsertID,
                'status' => 1,
            ])
            ->execute();
    }

    public function safeDown()
    {
        $row = $this->db
            ->createCommand('SELECT * FROM dp_admin_menu_url WHERE alias=:alias', [
                ':alias' => 'admin.menu.drop'
            ])
            ->queryOne();
        if ($row) {
            $this->db->createCommand()
                ->delete('dp_admin_menu_url', [
                    'url_id' => $row['url_id'],
                ])
                ->execute();
            $this->db->createCommand()
                ->delete('dp_admin_menu_url_relation', [
                    'menu_id' => 5,
                    'url_id' => $row['url_id'],
                ])
                ->execute();
        }

        return true;
    }
}
