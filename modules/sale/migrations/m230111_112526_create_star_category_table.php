<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%star_category}}`.
 */
class m230111_112526_create_star_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%star_category}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string(100)->notNull(),
            'level' => $this->integer()->notNull(),
            'pointRange' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        // creates index for column `name`
        $this->createIndex(
            'idx-star-category-name',
            'star_category',
            'name'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `name`
        $this->dropIndex(
            'idx-star-category-name',
            'star_category'
        );

        $this->dropTable('{{%star_category}}');
    }
}
