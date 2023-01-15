<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%airline_repository}}`.
 */
class m230114_175450_create_airline_repository_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%airline_repository}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string(120)->notNull(),
            'iataCode' => $this->string(5)->notNull(),
            'status' => $this->boolean()->defaultValue(1),
        ]);

        // creates index for column `name`
        $this->createIndex(
            'idx-airline-repository-name',
            'airline_repository',
            'name'
        );

        // creates index for column `iataCode`
        $this->createIndex(
            'idx-airline-repository-iataCode',
            'airline_repository',
            'iataCode'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `name`
        $this->dropIndex(
            'idx-airline-repository-name',
            'airline_repository'
        );

        // drops index for column `iataCode`
        $this->dropIndex(
            'idx-airline-repository-iataCode',
            'airline_repository'
        );
        
        $this->dropTable('{{%airline_repository}}');
    }
}
