<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hotel_proposal}}`.
 */
class m230605_164026_create_hotel_proposal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hotel_proposal}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'agencyId' => $this->integer()->notNull(),
            'hotelCategoryId' => $this->integer()->notNull(),
            'hotelName' => $this->string(150)->notNull(),
            'hotelAddress' => $this->string(150)->notNull(),
            'countryId' => $this->integer()->notNull(),
            'cityId' => $this->integer()->notNull(),

            'numberOfAdult' => $this->integer()->notNull(),
            'numberOfChild' => $this->integer()->notNull()->defaultValue(0),
            'amenities' => $this->text(),

            'totalPrice' => $this->double()->notNull()->defaultValue(0),
            'discount' => $this->double()->notNull()->defaultValue(0),
            'notes' => $this->text(),

            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        $this->createIndex('idx-hotel-proposal-agencyId', 'hotel_proposal', 'agencyId');
        $this->addForeignKey('fk-hotel-proposal-agencyId', 'hotel_proposal', 'agencyId', 'agency', 'id', 'CASCADE');

        $this->createIndex('idx-hotel-proposal-hotelCategoryId', 'hotel_proposal', 'hotelCategoryId');
        $this->addForeignKey('fk-hotel-proposal-hotelCategoryId', 'hotel_proposal', 'hotelCategoryId', 'hotel_category', 'id', 'CASCADE');

        $this->createIndex('idx-hotel-proposal-countryId', 'hotel_proposal', 'countryId');
        $this->addForeignKey('fk-hotel-proposal-countryId', 'hotel_proposal', 'countryId', 'country', 'id', 'CASCADE');

        $this->createIndex('idx-hotel-proposal-cityId', 'hotel_proposal', 'cityId');
        $this->addForeignKey('fk-hotel-proposal-cityId', 'hotel_proposal', 'cityId', 'city', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-hotel-proposal-agencyId', 'hotel_proposal');
        $this->dropIndex('idx-hotel-proposal-agencyId', 'hotel_proposal');

        $this->dropForeignKey('fk-hotel-proposal-hotelCategoryId', 'hotel_proposal');
        $this->dropIndex('idx-hotel-proposal-hotelCategoryId', 'hotel_proposal');

        $this->dropForeignKey('fk-hotel-proposal-countryId', 'hotel_proposal');
        $this->dropIndex('idx-hotel-proposal-countryId', 'hotel_proposal');

        $this->dropForeignKey('fk-hotel-proposal-cityId', 'hotel_proposal');
        $this->dropIndex('idx-hotel-proposal-cityId', 'hotel_proposal');

        $this->dropTable('{{%hotel_proposal}}');
    }
}
