<?php

namespace app\modules\sale\repositories;

use app\components\GlobalConstant;
use app\modules\sale\models\visa\Visa;
use app\modules\sale\models\visa\VisaSupplier;
use ParentRepository;
use Yii;
use yii\db\ActiveRecord;

class VisaRepository extends ParentRepository
{
    public function findAllVisa(string $query): array
    {
        return Visa::find()
            ->select(['id', 'identificationNumber', 'reference'])
            ->where(['like', 'identificationNumber', $query])
            ->orWhere(['like', 'reference', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}