<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%room_type}}`.
 */
class m230605_163938_create_room_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%room_type}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);
        $this->createIndex('idx-room-type-name', 'room_type', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-room-type-name', 'room_type');

        $this->dropTable('{{%room_type}}');
    }
}
