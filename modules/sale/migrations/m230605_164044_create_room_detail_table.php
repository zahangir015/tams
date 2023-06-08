<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%room_detail}}`.
 */
class m230605_164044_create_room_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%room_detail}}', [
            'id' => $this->primaryKey(),
            'hotelProposalId' => $this->integer()->notNull(),
            'roomTypeId' => $this->integer()->notNull(),
            'numberOfRoom' => $this->integer()->notNull(),
            'numberOfNight' => $this->integer()->notNull(),
            'perNightPrice' => $this->double()->notNull(),
            'extraBed' => $this->boolean()->defaultValue(0),
            'breakfast' => $this->boolean()->defaultValue(0),
            'checkIn' => $this->dateTime(),
            'checkOut' => $this->dateTime(),
        ]);

        $this->createIndex('idx-room-detail-hotelProposalId', 'room_detail', 'hotelProposalId');
        $this->addForeignKey('fk-room-detail-hotelProposalId', 'room_detail', 'hotelProposalId', 'hotel_proposal', 'id', 'CASCADE');

        $this->createIndex('idx-room-detail-roomTypeId', 'room_detail', 'roomTypeId');
        $this->addForeignKey('fk-room-detail-roomTypeId', 'room_detail', 'roomTypeId', 'room_type', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-room-detail-hotelProposalId', 'room_detail');
        $this->dropIndex('idx-room-detail-hotelProposalId', 'room_detail');

        $this->dropForeignKey('fk-room-detail-roomTypeId', 'room_detail');
        $this->dropIndex('idx-room-detail-roomTypeId', 'room_detail');

        $this->dropTable('{{%room_detail}}');
    }
}
