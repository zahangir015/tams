<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bank_account}}`.
 */
class m220823_175047_create_bank_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bank_account}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string(150)->notNull(),
            'shortName' => $this->string(20)->notNull(),
            'accountName' => $this->string(150)->notNull(),
            'accountNumber' => $this->string(60)->notNull(),
            'branch' => $this->string(50)->notNull(),
            'routingNumber' => $this->string(50)->null(),
            'swiftCode' => $this->string(50)->null(),
            'code' => $this->string(50)->null(),
            'paymentCharge' => $this->double()->defaultValue(0),
            'logo' => $this->string()->null(),
            'tag' => $this->string()->null(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        // creates index for column `name`
        $this->createIndex(
            'idx-bank-account-name',
            'bank_account',
            'name'
        );

        // creates index for column `accountName`
        $this->createIndex(
            'idx-bank-account-accountName',
            'bank_account',
            'accountName'
        );

        // creates index for column `accountNumber`
        $this->createIndex(
            'idx-bank-account-accountNumber',
            'bank_account',
            'accountNumber'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `name`
        $this->dropIndex(
            'idx-bank-account-name',
            'bank_account'
        );

        // drops index for column `accountName`
        $this->dropIndex(
            'idx-bank-account-accountName',
            'bank_account'
        );

        // drops index for column `accountNumber`
        $this->dropIndex(
            'idx-bank-account-accountNumber',
            'bank_account'
        );

        $this->dropTable('{{%bank_account}}');
    }
}
