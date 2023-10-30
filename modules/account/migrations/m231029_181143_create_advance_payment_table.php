<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%advance_payment}}`.
 */
class m231029_181143_create_advance_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%advance_payment}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'refId' => $this->integer(11)->notNull(),
            'refModel' => $this->string(150)->notNull(),
            'bankId' => $this->integer(11)->notNull(),
            'date' => $this->date()->notNull(),
            'paidAmount' => $this->double()->defaultValue(0),
            'remarks' => $this->text(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        $this->createIndex('idx-advance-payment-date', 'advance_payment', 'date');
        $this->createIndex('idx-advance-payment-bankId', 'advance_payment', 'bankId');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-advance-payment-date', 'advance_payment');
        $this->dropIndex('idx-advance-payment-bankId', 'advance_payment');
        $this->dropTable('{{%advance_payment}}');
    }
}
