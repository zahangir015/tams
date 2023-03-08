<?php

use app\modules\hrm\models\LeaveApprovalHistory;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%leave_approval_history}}`.
 */
class m230308_173404_add_uid_column_to_leave_approval_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(LeaveApprovalHistory::tableName(), 'uid', $this->string(36)->notNull()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(LeaveApprovalHistory::tableName(), 'uid');
    }
}
