<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%flight_proposal_itinerary}}`.
 */
class m230523_141100_create_flight_proposal_itinerary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%flight_proposal_itinerary}}', [
            'id' => $this->primaryKey(),
            'flightProposalId' => $this->integer()->notNull(),
            'flightNumber' => $this->string('100')->notNull(),
            'departureFrom' => $this->string(3)->notNull(),
            'departure' => $this->dateTime()->notNull(),
            'arrivalTo' => $this->string(3)->notNull(),
            'arrival' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex('idx-flight-proposal-itinerary-flightProposalId', 'flight_proposal_itinerary', 'flightProposalId');
        $this->addForeignKey('fk-flight-proposal-itinerary-flightProposalId', 'flight_proposal_itinerary', 'flightProposalId', 'flight_proposal', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-flight-proposal-itinerary-flightProposalId', 'flight_proposal_itinerary');
        $this->dropIndex('idx-flight-proposal-itinerary-flightProposalId', 'flight_proposal_itinerary');

        $this->dropTable('{{%flight_proposal_itinerary}}');
    }
}
