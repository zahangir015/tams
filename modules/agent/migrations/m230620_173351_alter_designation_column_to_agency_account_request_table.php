<?php

use yii\db\Migration;

/**
 * Class m230620_173351_alter_designation_column_to_agency_account_request_table
 */
class m230620_173351_alter_designation_column_to_agency_account_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(\app\modules\agent\models\AgencyAccountRequest::tableName(), 'designation', $this->string(30)->null());
        $this->alterColumn(\app\modules\agent\models\AgencyAccountRequest::tableName(), 'email', $this->string(60)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230620_173351_alter_designation_column_to_agency_account_request_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230620_173351_alter_designation_column_to_agency_account_request_table cannot be reverted.\n";

        return false;
    }
    */
}
