<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%flight_proposal}}`.
 */
class m230523_140828_create_flight_proposal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%flight_proposal}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'agencyId' => $this->integer()->notNull(),
            'airlineId' => $this->integer()->notNull(),
            'class' => $this->string('120')->notNull(),
            'tripType' => $this->boolean()->notNull(),
            'route' => $this->string('120')->notNull(),
            'departure' => $this->dateTime()->notNull(),
            'arrival' => $this->dateTime()->notNull(),

            'numberOfAdult' => $this->integer()->notNull(),
            'pricePerAdult' => $this->double()->notNull()->defaultValue(0),
            'baggagePerAdult' => $this->string()->null(),

            'numberOfChild' => $this->integer()->notNull()->defaultValue(0),
            'pricePerChild' => $this->double()->notNull()->defaultValue(0),
            'baggagePerChild' => $this->string()->null(),

            'numberOfInfant' => $this->integer()->notNull()->defaultValue(0),
            'pricePerInfant' => $this->double()->notNull()->defaultValue(0),
            'baggagePerInfant' => $this->string()->null(),

            'totalPrice' => $this->double()->notNull()->defaultValue(0),
            'discount' => $this->double()->notNull()->defaultValue(0),
            'notes' => $this->text(),

            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        $this->createIndex('idx-flight-proposal-agencyId', 'flight_proposal', 'agencyId');
        $this->addForeignKey('fk-flight-proposal-agencyId', 'flight_proposal', 'agencyId', 'agency', 'id', 'CASCADE');

        $this->createIndex('idx-flight-proposal-airlineId', 'flight_proposal', 'airlineId');
        $this->addForeignKey('fk-flight-proposal-airlineId', 'flight_proposal', 'airlineId', 'airline', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-flight-proposal-agencyId', 'flight_proposal');
        $this->dropIndex('idx-flight-proposal-agencyId', 'flight_proposal');

        $this->dropForeignKey('fk-flight-proposal-airlineId', 'flight_proposal');
        $this->dropIndex('idx-flight-proposal-airlineId', 'flight_proposal');

        $this->dropTable('{{%flight_proposal}}');
    }
}
