<?php

use app\modules\sale\models\FlightProposal;
use yii\db\Migration;

/**
 * Class m230615_174619_alter_tripType_column_to_flight_proposal_table
 */
class m230615_174619_alter_tripType_column_to_flight_proposal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(FlightProposal::tableName(), 'tripType', "ENUM('One Way','Return')");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230615_174619_alter_tripType_column_to_flight_proposal_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230615_174619_alter_tripType_column_to_flight_proposal_table cannot be reverted.\n";

        return false;
    }
    */
}
