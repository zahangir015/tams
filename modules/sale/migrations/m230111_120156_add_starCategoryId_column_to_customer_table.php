<?php

use app\modules\sale\models\Customer;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%customer}}`.
 */
class m230111_120156_add_starCategoryId_column_to_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Customer::tableName(), 'starCategoryId', $this->integer(11)->null());

        // creates index for column `starCategoryId`
        $this->createIndex(
            'idx-customer-starCategoryId',
            'customer',
            'starCategoryId'
        );

        // add foreign key for table `star_category`
        $this->addForeignKey(
            'fk-customer-starCategoryId',
            'customer',
            'starCategoryId',
            'star_category',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `invoice`
        $this->dropForeignKey(
            'fk-customer-starCategoryId',
            'customer'
        );

        // drops index for column `starCategoryId`
        $this->dropIndex(
            'idx-customer-starCategoryId',
            'customer'
        );

        $this->dropColumn(Customer::tableName(), 'starCategoryId');
    }
}
