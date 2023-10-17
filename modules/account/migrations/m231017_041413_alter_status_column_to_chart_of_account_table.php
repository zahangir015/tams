<?php

use app\modules\account\models\ChartOfAccount;
use yii\db\Migration;

/**
 * Class m231017_041413_alter_status_column_to_chart_of_account_table
 */
class m231017_041413_alter_status_column_to_chart_of_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(ChartOfAccount::tableName(), 'status', $this->boolean()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231017_041413_alter_status_column_to_chart_of_account_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231017_041413_alter_status_column_to_chart_of_account_table cannot be reverted.\n";

        return false;
    }
    */
}
