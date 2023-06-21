<?php

use app\modules\support\models\Inquiry;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%inquiry}}`.
 */
class m230618_181628_add_identification_number_column_to_inquiry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Inquiry::tableName(), 'identificationNumber', $this->string()->notNull());
        $this->createIndex('idx-inquiry-identificationNumber', Inquiry::tableName(), 'identificationNumber');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-inquiry-identificationNumber', Inquiry::tableName());
        $this->dropColumn(Inquiry::tableName(), 'identificationNumber');
    }
}
