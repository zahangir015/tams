<?php

use yii\db\Migration;

/**
 * Class m230601_073657_alter_unique_column_to_provider_table
 */
class m230601_073657_alter_unique_column_to_provider_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(\app\modules\sale\models\Provider::tableName(), 'name', $this->string(10)->notNull());
        $this->dropIndex('name', \app\modules\sale\models\Provider::tableName());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230601_073657_alter_unique_column_to_provider_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230601_073657_alter_unique_column_to_provider_table cannot be reverted.\n";

        return false;
    }
    */
}
