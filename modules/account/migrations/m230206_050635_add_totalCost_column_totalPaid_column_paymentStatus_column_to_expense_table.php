<?php

use app\modules\account\models\Expense;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%expense}}`.
 */
class m230206_050635_add_totalCost_column_totalPaid_column_paymentStatus_column_to_expense_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Expense::tableName(), 'totalCost', $this->double()->notNull());
        $this->addColumn(Expense::tableName(), 'totalPaid', $this->double()->notNull());
        $this->addColumn(Expense::tableName(), 'paymentStatus', "ENUM('Full Paid','Partially Paid','Due','Refund Adjustment') DEFAULT 'Due'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Expense::tableName(), 'totalCost');
        $this->dropColumn(Expense::tableName(), 'totalPaid');
        $this->dropColumn(Expense::tableName(), 'paymentStatus');
    }
}
