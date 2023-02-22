<?php

use app\modules\sale\models\holiday\HolidaySupplier;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%holiday_supplier}}`.
 */
class m230221_044229_add_title_column_to_holiday_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(HolidaySupplier::tableName(), 'title', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(HolidaySupplier::tableName(), 'title');
    }
}
