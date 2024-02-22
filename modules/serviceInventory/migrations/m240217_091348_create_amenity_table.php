<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%amenity}}`.
 */
class m240217_091348_create_amenity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%amenity}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'status' => $this->boolean()->defaultValue(1),
            'createdAt' => $this->integer(11)->notNull(),
            'createdBy' => $this->integer(11)->notNull(),
            'updatedAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%amenity}}');
    }
}
