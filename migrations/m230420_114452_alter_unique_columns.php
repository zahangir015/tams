<?php

use yii\db\Migration;

/**
 * Class m230420_114452_alter_unique_columns
 */
class m230420_114452_alter_unique_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->alterColumn(\app\modules\sale\models\Airline::tableName(), 'code', $this->string(10)->notNull());
        $this->dropIndex('code', \app\modules\sale\models\Airline::tableName());
        $this->alterColumn(\app\modules\sale\models\Provider::tableName(), 'code', $this->string(10)->notNull());
        $this->dropIndex('code', \app\modules\sale\models\Provider::tableName());
        $this->alterColumn(\app\modules\sale\models\Supplier::tableName(), 'name', $this->string(30)->notNull());
        $this->dropIndex('name', \app\modules\sale\models\Supplier::tableName());
        $this->dropIndex('email', \app\modules\sale\models\Supplier::tableName());
        $this->alterColumn(\app\modules\hrm\models\Department::tableName(), 'name', $this->string(120)->notNull());
        $this->dropIndex('name', \app\modules\hrm\models\Department::tableName());
        $this->alterColumn(\app\modules\hrm\models\Designation::tableName(), 'name', $this->string(120)->notNull());
        $this->dropIndex('name', \app\modules\hrm\models\Designation::tableName());
        $this->alterColumn(\app\modules\hrm\models\Branch::tableName(), 'name', $this->string(120)->notNull());
        $this->dropIndex('name', \app\modules\hrm\models\Branch::tableName());
        $this->alterColumn(\app\modules\account\models\AccountGroup::tableName(), 'code', $this->string(10)->notNull());
        $this->dropIndex('code',\app\modules\account\models\AccountGroup::tableName());
        $this->alterColumn(\app\modules\account\models\ChartOfAccount::tableName(), 'code', $this->string(10)->notNull());
        $this->dropIndex('code', \app\modules\account\models\ChartOfAccount::tableName());
    }

    /**nb      * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230420_114452_alter_unique_columns cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230420_114452_alter_unique_columns cannot be reverted.\n";

        return false;
    }
    */
}
