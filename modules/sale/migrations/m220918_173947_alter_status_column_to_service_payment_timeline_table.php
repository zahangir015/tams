<?php

use app\modules\account\models\ServicePaymentTimeline;
use yii\db\Migration;

/**
 * Class m220918_173947_alter_status_column_to_service_payment_timeline_table
 */
class m220918_173947_alter_status_column_to_service_payment_timeline_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(ServicePaymentTimeline::tableName(), 'status', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220918_173947_alter_status_column_to_service_payment_timeline_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220918_173947_alter_status_column_to_service_payment_timeline_table cannot be reverted.\n";

        return false;
    }
    */
}
