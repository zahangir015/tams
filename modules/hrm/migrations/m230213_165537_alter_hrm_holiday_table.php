<?php

use yii\db\Migration;

/**
 * Class m230213_165537_alter_hrm_holiday_table
 */
class m230213_165537_alter_hrm_holiday_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('hrm_holiday', 'public_holiday');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230213_165537_alter_hrm_holiday_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230213_165537_alter_hrm_holiday_table cannot be reverted.\n";

        return false;
    }
    */
}
