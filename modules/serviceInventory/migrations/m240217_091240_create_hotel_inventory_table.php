<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hotel_inventory}}`.
 */
class m240217_091240_create_hotel_inventory_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hotel_inventory}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'supplierId' => $this->integer()->notNull(),
            'hotelName' => $this->string(255)->notNull(),
            'hotelAddress' => $this->string(255)->notNull(),
            'countryId' => $this->integer()->notNull(),
            'cityId' => $this->integer()->notNull(),
            'hotelCategoryId' => $this->integer()->notNull(),
            'status' => $this->boolean()->defaultValue(1),
            'createdAt' => $this->integer(11)->notNull(),
            'createdBy' => $this->integer(11)->notNull(),
            'updatedAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->notNull()
        ]);

        $this->createIndex('idx-hotel-inventory-supplierId','hotel_inventory','supplierId');
        $this->addForeignKey('fk-hotel-inventory-supplierId','hotel_inventory','supplierId','hotel_supplier','supplierId','CASCADE');

        $this->createIndex('idx-hotel-inventory-hotelCategoryId','hotel_inventory','hotelCategoryId');
        $this->addForeignKey('fk-hotel-inventory-hotelCategoryId','hotel_inventory','hotelCategoryId','hotel_category','id','CASCADE');

        $this->createIndex('idx-hotel-inventory-countryId','hotel_inventory','countryId');
        $this->addForeignKey('fk-hotel-inventory-countryId','hotel_inventory','countryId','country','id','CASCADE');

        $this->createIndex('idx-hotel-inventory-cityId','hotel_inventory','cityId');
        $this->addForeignKey('fk-hotel-inventory-cityId','hotel_inventory','cityId','city','id','CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-hotel-inventory-supplierId','hotel_inventory');
        $this->dropForeignKey('fk-hotel_inventory-supplierId','hotel_inventory');

        $this->dropIndex('idx-hotel-inventory-hotelCategoryId','hotel_inventory');
        $this->dropForeinKey('fk-hotel-inventory-hotelCategoryId','hotel_inventory');

        $this->dropIndex('idx-hotel-inventory-countryId','hotel_inventory');
        $this->dropForeignKey('fk-hotel-inventory-countryId','hotel_inventory');

        $this->dropIndex('idx-hotel-inventory-cityId','hotel_inventory');
        $this->dropForeignKey('fk-hotel-inventory-cityId','hotel_inventory');

        $this->dropTable('{{%hotel_inventory}}');
    }
}
