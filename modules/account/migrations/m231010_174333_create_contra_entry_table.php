<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contra_entry}}`.
 */
class m231010_174333_create_contra_entry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contra_entry}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'identificationNumber' => $this->string()->notNull()->unique(),
            'bankFrom' => $this->integer()->notNull(),
            'bankTo' => $this->integer()->notNull(),
            'amount' => $this->double()->defaultValue(0),
            'paymentDate' => $this->date()->notNull(),
            'remarks' => $this->string()->null(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedAt' => $this->integer(11)->null(),
            'createdBy' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
        ]);

        $this->createIndex('id-contra-entry-identificationNumber', 'contra_entry','identificationNumber');
        $this->createIndex('id-contra-entry-paymentDate','contra_entry','paymentDate');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contra_entry}}');
    }
}
