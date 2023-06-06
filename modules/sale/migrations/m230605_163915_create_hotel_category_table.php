<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hotel_category}}`.
 */
class m230605_163915_create_hotel_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hotel_category}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        $this->createIndex('idx-hotel-category-name', 'hotel_category', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-hotel-category-name', 'hotel_category');

        $this->dropTable('{{%hotel_category}}');
    }
}
