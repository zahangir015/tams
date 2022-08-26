<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%service_payment_history}}`.
 */
class m220823_175201_create_service_payment_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%service_payment_history}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'date' => $this->date()->notNull(),
            'refId' => $this->integer()->notNull(),
            'refModel' => $this->string(150)->notNull(),
            'subRefId' => $this->integer()->null(),
            'subRefModel' => $this->string(150)->null(),
            'paidAmount' => $this->double()->defaultValue(0),
            'dueAmount' => $this->double()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%service_payment_history}}');
    }
}
