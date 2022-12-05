<?php

namespace app\modules\sale\repositories;

use app\components\GlobalConstant;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\holiday\HolidayCategory;
use ParentRepository;
use Yii;
use yii\db\ActiveRecord;

class HolidayRepository extends ParentRepository
{
    public function findCategories(): array
    {
        return HolidayCategory::find()->select(['id', 'name'])->where(['status' => GlobalConstant::ACTIVE_STATUS])->all();
    }
}