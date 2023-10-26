<?php

use app\modules\account\models\Transaction;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%transaction}}`.
 */
class m231026_045731_add_remarks_column_to_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Transaction::tableName(), 'remarks', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Transaction::tableName(), 'remarks');
    }
}
