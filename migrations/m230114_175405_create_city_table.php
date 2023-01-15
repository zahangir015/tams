<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city}}`.
 */
class m230114_175405_create_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'countryId' => $this->integer(36)->notNull(),
            'name' => $this->string(120)->notNull(),
            'status' => $this->boolean()->defaultValue(1),
        ]);

        // creates index for column `name`
        $this->createIndex(
            'idx-city-name',
            'city',
            'name'
        );

        // creates index for column `countryId`
        $this->createIndex(
            'idx-city-countryId',
            'city',
            'countryId'
        );

        // add foreign key for table `country`
        $this->addForeignKey(
            'fk-city-countryId',
            'city',
            'countryId',
            'country',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `name`
        $this->dropIndex(
            'idx-city-name',
            'city'
        );

        // drops foreign key for table `country`
        $this->dropForeignKey(
            'fk-city-countryId',
            'city'
        );

        // drops index for column `countryId`
        $this->dropIndex(
            'idx-city-countryId',
            'city'
        );

        $this->dropTable('{{%city}}');
    }
}
