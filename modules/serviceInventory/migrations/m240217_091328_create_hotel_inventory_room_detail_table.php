<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hotel_inventory_room_detail}}`.
 */
class m240217_091328_create_hotel_inventory_room_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hotel_inventory_room_detail}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'roomTypeId' => $this->integer()->notNull(),
            'meal' => $this->boolean()->defaultValue(0),
            'extraBed' => $this->boolean()->defaultValue(0),
            'numberOfRoom' => $this->integer()->notNull(),
            'isAvailable' => $this->boolean()->defaultValue(0),
            'cancelationPolicy' => $this->boolean()->defaultValue(0),
            'perNightCost' => $this->double()->notNull(),
            'currency' => $this->string(3)->notNull(),
            'perNightSelling' => $this->integer()->notNull(),
            'currency' => $this->string(3)->notNull(),
            'priceValidity' => $this->date()->notNull(),
            'transfer' => $this->boolean()->defaultValue(0),
            'transferDetails' => $this->string(1024)->notNull()
        ]);

        $this->createIndex('idx-hotel-inventory-room-detail-roomTypeId','hotel_inventory_room_detail','roomTypeId');
        $this->addForeignKey('fk-hotel-inventory-room-detail-roomTypeId','hotel_inventory_room_detail','roomTypeId','room_type','id','CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-hotel-inventory-room-detail-roomTypeId','hotel_inventory_room_detail');
        $this->dropForeignKey('fk-hotel-inventory-room-detail-roomTypeId','hotel_inventory_room_detail');

        $this->dropTable('{{%hotel_inventory_room_detail}}');
    }
}
