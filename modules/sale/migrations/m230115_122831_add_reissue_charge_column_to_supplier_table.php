<?php

use app\modules\sale\models\Supplier;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%supplier}}`.
 */
class m230115_122831_add_reissue_charge_column_to_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Supplier::tableName(), 'reissueCharge', $this->float()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Supplier::tableName(), 'reissueCharge');
    }
}
