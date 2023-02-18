<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chart_of_account}}`.
 */
class m230126_174311_create_chart_of_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chart_of_account}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'accountTypeId' => $this->integer(11)->notNull(),
            'accountGroupId' => $this->integer(11)->notNull(),
            'code' => $this->string(10)->notNull()->unique(),
            'name' => $this->string(150)->notNull(),
            'description' => $this->string(150),
            'reportType' => $this->string()->null(),
            'status' => $this->tinyInteger()->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'createdBy' => $this->integer()->notNull(),
            'updatedAt' => $this->integer(11)->null(),
            'updatedBy' => $this->integer()->null(),
        ]);

        // creates index for column `accountTypeId`
        $this->createIndex(
            'idx-chart-of-account-accountTypeId',
            'chart_of_account',
            'accountTypeId'
        );

        // add foreign key for table `account_type`
        $this->addForeignKey(
            'fk-chart-of-account-accountTypeId',
            'chart_of_account',
            'accountTypeId',
            'account_type',
            'id',
            'CASCADE'
        );

        // creates index for column `accountGroupId`
        $this->createIndex(
            'idx-chart-of-account-accountGroupId',
            'chart_of_account',
            'accountGroupId'
        );

        // add foreign key for table `account_group`
        $this->addForeignKey(
            'fk-chart-of-account-accountGroupId',
            'chart_of_account',
            'accountGroupId',
            'account_group',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `accountTypeId`
        $this->dropIndex(
            'idx-chart-of-account-accountTypeId',
            'chart_of_account'
        );

        // drops foreign key for table `account_type`
        $this->dropForeignKey(
            'fk-chart-of-account-accountTypeId',
            'chart_of_account'
        );

        // drops index for column `accountGroupId`
        $this->dropIndex(
            'idx-chart-of-account-accountGroupId',
            'chart_of_account'
        );

        // drops foreign key for table `account_group`
        $this->dropForeignKey(
            'fk-chart-of-account-accountGroupId',
            'chart_of_account'
        );

        $this->dropTable('{{%chart_of_account}}');
    }
}
