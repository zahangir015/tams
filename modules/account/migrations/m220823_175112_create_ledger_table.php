<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ledger}}`.
 */
class m220823_175112_create_ledger_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ledger}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'title' => $this->string(150)->notNull(),
            'date' => $this->date()->notNull(),
            'reference' => $this->string()->null(),
            'refId' => $this->integer()->notNull(),
            'refModel' => $this->string(150)->notNull(),
            'subRefId' => $this->integer()->null(),
            'subRefModel' => $this->string(150)->null(),
            'debit' => $this->double()->defaultValue(0),
            'credit' => $this->double()->defaultValue(0),
            'balance' => $this->double()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ledger}}');
    }
}
