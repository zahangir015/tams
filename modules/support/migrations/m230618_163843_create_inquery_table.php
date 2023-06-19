<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%inquiry}}`.
 */
class m230618_163843_create_inquery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%inquiry}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string(30)->notNull(),
            'subject' => $this->string(150)->notNull(),
            'company' => $this->string(60)->notNull(),
            'phone' => $this->string(20)->notNull(),
            'email' => $this->string(70)->notNull(),
            'quire' => $this->text()->notNull(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        $this->createIndex(
            'idx-inquire-name',
            'inquiry',
            'company'
        );

        $this->createIndex(
            'idx-inquire-phone',
            'inquiry',
            'phone'
        );

        $this->createIndex(
            'idx-inquire-email',
            'inquiry',
            'email'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-inquire-name', 'inquiry');
        $this->dropIndex('idx-inquire-phone', 'inquiry');
        $this->dropIndex('idx-inquire-email', 'inquiry');

        $this->dropTable('{{%inquiry}}');
    }
}
