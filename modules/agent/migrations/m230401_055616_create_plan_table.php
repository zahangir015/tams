<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plan}}`.
 */
class m230401_055616_create_plan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plan}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string()->notNull()->unique(),
            'userLimit' => $this->string()->notNull(),
            'monthlySubscriptionFee' => $this->double()->notNull(),
            'yearlySubscriptionFee' => $this->double()->notNull(),
            'modules' => $this->text(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        $this->createIndex('idx-plan-name', 'plan', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-plan-name', 'plan');
        $this->dropTable('{{%plan}}');
    }
}
