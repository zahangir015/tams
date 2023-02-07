<?php

use app\modules\account\models\Expense;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%expense}}`.
 */
class m230207_164757_add_identificationNumber_column_to_expense_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Expense::tableName(), 'identificationNumber', $this->string(32)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Expense::tableName(), 'identificationNumber');
    }
}
