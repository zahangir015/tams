<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%service_payment_timeline}}`.
 */
class m220823_175201_create_service_payment_timeline_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%service_payment_timeline}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'date' => $this->date()->notNull(),
            'refId' => $this->integer()->notNull(),
            'refModel' => $this->string(150)->notNull(),
            'subRefId' => $this->integer()->null(),
            'subRefModel' => $this->string(150)->null(),
            'paidAmount' => $this->double()->defaultValue(0),
            'dueAmount' => $this->double()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1),
        ]);

        // creates index for column `date`
        $this->createIndex(
            'idx-service-payment-timeline-date',
            'service_payment_timeline',
            'date'
        );

        // creates index for column `refId`
        $this->createIndex(
            'idx-service-payment-timeline-refId',
            'service_payment_timeline',
            'refId'
        );

        // creates index for column `refModel`
        $this->createIndex(
            'idx-service-payment-timeline-refModel',
            'service_payment_timeline',
            'refModel'
        );

        // creates index for column `subRefId`
        $this->createIndex(
            'idx-service-payment-timeline-subRefId',
            'service_payment_timeline',
            'subRefId'
        );

        // creates index for column `subRefModel`
        $this->createIndex(
            'idx-service-payment-timeline-subRefModel',
            'service_payment_timeline',
            'subRefModel'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `date`
        $this->dropIndex(
            'idx-service-payment-timeline-date',
            'service_payment_timeline'
        );

        // drops index for column `refId`
        $this->dropIndex(
            'idx-service-payment-timeline-refId',
            'service_payment_timeline'
        );

        // drops index for column `refModel`
        $this->dropIndex(
            'idx-service-payment-timeline-refModel',
            'service_payment_timeline'
        );

        // drops index for column `subRefId`
        $this->dropIndex(
            'idx-service-payment-timeline-subRefId',
            'service_payment_timeline'
        );

        // drops index for column `subRefModel`
        $this->dropIndex(
            'idx-service-payment-timeline-subRefModel',
            'service_payment_timeline'
        );

        $this->dropTable('{{%service_payment_timeline}}');
    }
}
