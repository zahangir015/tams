<?php

use app\modules\account\models\ContraEntry;
use app\modules\agent\models\Agency;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%contra_entry}}`.
 */
class m231012_204333_add_agencyId_column_to_contra_entry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(ContraEntry::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->createIndex('idx-contra-entry-agencyId', ContraEntry::tableName(), 'agencyId');
        $this->addForeignKey('fk-contra-entry-agencyId', ContraEntry::tableName(), 'agencyId', Agency::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-contra-entry-agencyId', ContraEntry::tableName());
        $this->dropForeignKey('fk-contra-entry-agencyId', ContraEntry::tableName());
        $this->dropColumn(ContraEntry::tableName(), 'agencyId');
    }
}
