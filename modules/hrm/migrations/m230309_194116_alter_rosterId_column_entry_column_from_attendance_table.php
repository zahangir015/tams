<?php

use app\modules\hrm\models\Attendance;
use yii\db\Migration;

/**
 * Class m230309_194116_alter_rosterId_column_entry_column_from_attendance_table
 */
class m230309_194116_alter_rosterId_column_entry_column_from_attendance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(Attendance::tableName(), 'rosterId', $this->integer(11)->null());
        $this->alterColumn(Attendance::tableName(), 'entry', $this->time()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230309_194116_alter_rosterId_column_entry_column_from_attendance_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230309_194116_alter_rosterId_column_entry_column_from_attendance_table cannot be reverted.\n";

        return false;
    }
    */
}
