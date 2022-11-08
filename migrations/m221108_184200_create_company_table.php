<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%company}}`.
 */
class m221108_184200_create_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%company}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string(120)->notNull()->unique(),
            'shortName' => $this->string(10)->notNull()->unique(),
            'phone' => $this->string(100)->notNull(),
            'email' => $this->string(100)->notNull(),
            'address' => $this->string()->notNull(),
            'logo' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%company}}');
    }
}
