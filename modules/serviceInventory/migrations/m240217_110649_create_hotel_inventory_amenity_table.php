<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hotel_inventory_amenity}}`.
 */
class m240217_110649_create_hotel_inventory_amenity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hotel_inventory_amenity}}', [
            'id' => $this->primaryKey(),
            'hotelInventoryId' => $this->integer()->notNull(),
            'amenityId' => $this->integer()->notNull()
        ]);

        $this->createIndex('idx-hotel-inventory-amenity-hotelInventoryId','hotel_inventory_amenity','hotelInventoryId');
        $this->addForeignKey('fk-hotel-inventory-amenity-hotelInventoryId','hotel_inventory_amenity','hotelInventoryId','hotel_inventory','id','CASCADE');

        $this->createIndex('idx-hotel-inventory-amenity-amenityId','hotel_inventory_amenity','amenityId',);
        $this->addForeignKey('fk-hotel-inventory-amenity-amenityId','hotel_inventory_amenity','amenityId','amenity','id','CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-hotel-inventory-amenity-hotelInventoryId','hotel_inventory_amenity');
        $this->dropForeignKey('fk-hotel-inventory-amenity-hotelInventoryId','hotel_inventory_amenity');

        $this->dropIndex('idx-hotel-inventory-amenity-amenityId','hotel_inventory_amenity');
        $this->dropForeignKey('fk-hotel-inventory-amenity-amenityId','hotel_inventory_amenity');

        $this->dropTable('{{%hotel_inventory_amenity}}');
    }
}
