<?php
namespace app\modules\hrm\repositories;

use app\repository\ParentRepository;

class HrmConfigurationRepository extends ParentRepository
{
    public function findAll($queryArray, $model, $withArray, $asArray)
    {
        $query = $model::find();
        if (!empty($withArray)) {
            $query->with($withArray);
        }

        $query->where($queryArray);
        if ($asArray) {
            $query->asArray();
        }

        return $query->all();
    }
}