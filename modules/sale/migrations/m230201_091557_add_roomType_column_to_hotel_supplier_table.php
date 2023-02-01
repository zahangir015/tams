<?php

use app\modules\sale\models\hotel\HotelSupplier;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hotel_supplier}}`.
 */
class m230201_091557_add_roomType_column_to_hotel_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(HotelSupplier::tableName(), 'roomType', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(HotelSupplier::tableName(), 'roomType');
    }
}
