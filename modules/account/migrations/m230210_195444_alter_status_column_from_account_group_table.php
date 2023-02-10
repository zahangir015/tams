<?php

use app\modules\account\models\AccountGroup;
use yii\db\Migration;

/**
 * Class m230210_195444_alter_status_column_from_account_group_table
 */
class m230210_195444_alter_status_column_from_account_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(AccountGroup::tableName(), 'status', $this->boolean()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230210_195444_alter_status_column_from_account_group_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230210_195444_alter_status_column_from_account_group_table cannot be reverted.\n";

        return false;
    }
    */
}
