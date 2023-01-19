<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hotel_repository_room}}`.
 */
class m230117_175106_create_hotel_repository_room_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hotel_repository_room}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%hotel_repository_room}}');
    }
}
