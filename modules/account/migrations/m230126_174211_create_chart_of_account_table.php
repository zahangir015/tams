<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chart_of_account}}`.
 */
class m230126_174211_create_chart_of_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chart_of_account}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%chart_of_account}}');
    }
}
