<?php

use app\modules\account\models\Expense;
use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%expense}}`.
 */
class m230207_100924_drop_name_column_from_expense_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn(Expense::tableName(), 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn(Expense::tableName(), 'name', $this->string()->notNull());
    }
}
