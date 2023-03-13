<?php

use yii\db\Migration;

/**
 * Class m230313_071027_alter_rosterId_from_attendance_table
 */
class m230313_071027_alter_rosterId_from_attendance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(\app\modules\hrm\models\Attendance::tableName(), 'rosterId', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230313_071027_alter_rosterId_from_attendance_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230313_071027_alter_rosterId_from_attendance_table cannot be reverted.\n";

        return false;
    }
    */
}
