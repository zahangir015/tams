<?php

use app\modules\hrm\models\EmployeeDesignation;
use yii\db\Migration;

/**
 * Class m230108_051711_alter_endDate_column_to_employee_table
 */
class m230108_051711_alter_endDate_column_to_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(EmployeeDesignation::tableName(), 'endDate', $this->date()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230108_051711_alter_endDate_column_to_employee_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230108_051711_alter_endDate_column_to_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
