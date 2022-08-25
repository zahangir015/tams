<?php

use yii\db\Migration;

/**
 * Class m220823_165644_alert_code_column_from_provider_table
 */
class m220823_165644_alert_code_column_from_provider_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%provider}}', 'code', $this->string(64));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220823_165644_alert_code_column_from_provider_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220823_165644_alert_code_column_from_provider_table cannot be reverted.\n";

        return false;
    }
    */
}
