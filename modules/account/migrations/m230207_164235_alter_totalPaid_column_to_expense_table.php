
<?php

use yii\db\Migration;

/**
 * Class m230207_164235_alter_totalPaid_column_to_expense_table
 */
class m230207_164235_alter_totalPaid_column_to_expense_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(\app\modules\account\models\Expense::tableName(), 'totalPaid', $this->double()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230207_164235_alter_totalPaid_column_to_expense_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230207_164235_alter_totalPaid_column_to_expense_table cannot be reverted.\n";

        return false;
    }
    */
}
