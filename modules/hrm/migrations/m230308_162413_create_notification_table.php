<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification}}`.
 */
class m230308_162413_create_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'receiverId' => $this->integer()->notNull(),
            'type' => $this->string()->notNull(),
            'message' => $this->text()->notNull(),
            'url' => $this->string()->null(),
            'isRead' => $this->boolean()->notNull()->defaultValue(0),
            'isMailSent' => $this->boolean()->notNull()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer()->notNull(),
            'createdAt' => $this->integer()->notNull(),
        ]);

        // creates index for column `type`
        $this->createIndex(
            'idx-notification-type',
            'notification',
            'type'
        );

        // creates index for column `receiverId`
        $this->createIndex(
            'idx-notification-receiverId',
            'notification',
            'receiverId'
        );

        // add foreign key for table `employee`
        $this->addForeignKey(
            'fk-notification-receiverId',
            'notification',
            'receiverId',
            'employee',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notification}}');
    }
}
