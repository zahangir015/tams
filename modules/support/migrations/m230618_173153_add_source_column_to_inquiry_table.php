<?php

use app\modules\support\models\Inquiry;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%inquiry}}`.
 */
class m230618_173153_add_source_column_to_inquiry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Inquiry::tableName(), 'source', $this->string()->notNull());
        $this->createIndex('idx-inquiry-source', Inquiry::tableName(), 'source');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-inquiry-source', Inquiry::tableName());
        $this->dropColumn(Inquiry::tableName(), 'source');
    }
}
