<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hotel_repository}}`.
 */
class m230117_174728_create_hotel_repository_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hotel_repository}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%hotel_repository}}');
    }
}
