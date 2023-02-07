<?php

use app\modules\account\models\Expense;
use yii\db\Migration;

/**
 * Class m230207_163727_alter_status_column_to_expense_table
 */
class m230207_163727_alter_status_column_to_expense_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(Expense::tableName(), 'status', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230207_163727_alter_status_column_to_expense_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230207_163727_alter_status_column_to_expense_table cannot be reverted.\n";

        return false;
    }
    */
}
