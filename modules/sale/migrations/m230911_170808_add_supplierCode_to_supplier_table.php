<?php

use app\modules\sale\models\Supplier;
use yii\db\Migration;

/**
 * Class m230911_170808_add_supplierCode_to_supplier_table
 */
class m230911_170808_add_supplierCode_to_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Supplier::tableName(), 'supplierCode', $this->string(32)->notNull());
        $this->createIndex('idx-supplier-supplierCode', Supplier::tableName(), 'supplierCode');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-supplier-supplierCode', Supplier::tableName());
        $this->dropColumn(Supplier::tableName(), 'supplierCode');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230911_170808_add_supplierCode_to_supplier_table cannot be reverted.\n";

        return false;
    }
    */
}
