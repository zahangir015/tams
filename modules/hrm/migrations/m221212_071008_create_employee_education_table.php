<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_education}}`.
 */
class m221212_071008_create_employee_education_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_education}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employee_education}}');
    }
}
