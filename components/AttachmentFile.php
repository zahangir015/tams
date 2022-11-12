<?php


namespace app\components;

use app\models\Attachment;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\StringHelper;
use yii\web\UploadedFile;

class AttachmentFile
{
    /**
     * @param $getModel
     * @param $fieldName
     * @param null $path
     * @param null $uid
     * @return bool|array
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function uploads($getModel, $fieldName, $uid = null, $path = NULL): bool|array
    {
        $files = UploadedFile::getInstances($getModel, $fieldName);
        $referenceModel = get_class($getModel);

        $refId = !empty($uid) ? $uid : $getModel->id;

        $row = [];
        foreach ($files as $file) {
            if (!empty($file)) {
                $getFileName = self::uniqueFileName($referenceModel) . '.' . $file->getExtension();
                $fileName = $file->name = $path ? $path . DIRECTORY_SEPARATOR . $getFileName : $getFileName;
                $uploadResponse = Uploader::processFile($file, false);
                if (!$uploadResponse['error']) {
                    Uploader::deleteLocalFile($fileName);
                    $row[] = [
                        'uid' => new Expression('UUID()'),
                        'name' => $uploadResponse,
                        'refModel' => $referenceModel,
                        'refId' => $refId,
                        'createdBy' => Yii::$app->user->identity->id,
                        'updatedBy' => Yii::$app->user->identity->id,
                        'createdAt' => time(),
                        'updatedAt' => time()
                    ];
                } else {
                    break;
                }
            }
        }
        $list = array_column($row, 'name');

        $response = Yii::$app->db->createCommand()->batchInsert(Attachment::tableName(), ['name', 'refModel', 'refId', 'cdnUrl', 'createdBy', 'updatedBy', 'createdAt', 'updatedAt'], $row)->execute();
        return $response && !empty($list) ? $list : false;
    }

    public function delete($file): bool
    {
        $fileName = ltrim(parse_url($file, PHP_URL_PATH), '/');
        $response = Uploader::deleteFile($fileName);
        return $response ? true : false;
    }

    public static function uniqueFileName($modelName): string
    {
        $name = StringHelper::basename(strtolower($modelName));
        return substr($name . Helper::uniqueCode(36), 0, 32);
    }

    public static function uploadsById($model, $fieldName, $path = NULL)
    {
        $files = UploadedFile::getInstances($model, $fieldName);
        $referenceModel = get_class($model);

        $row = [];
        foreach ($files as $file) {
            if (!empty($file)) {
                $getFileName = self::uniqueFileName($referenceModel) . '.' . $file->getExtension();
                $fileName = $file->name = $path ? $path . DIRECTORY_SEPARATOR . $getFileName : $getFileName;
                $uploadResponse = Uploader::processFile($file, true);
                if ($uploadResponse) {
                    Uploader::deleteLocalFile($fileName);
                    $row[] = [
                        'uid' => new Expression('UUID()'),
                        'name' => $uploadResponse,
                        'referenceModel' => $referenceModel,
                        'ref' => $model->id,
                        'docType' => StringHelper::basename($referenceModel),
                        'createdBy' => Yii::$app->user->identity->id,
                        'updatedBy' => Yii::$app->user->identity->id,
                        'createdAt' => time(),
                        'updatedAt' => time()
                    ];
                }
            }
        }
        $list = array_column($row, 'name');

        $response = Yii::$app->db->createCommand()->batchInsert(AttachmentFile::tableName(), ['uid', 'name', 'referenceModel', 'ref', 'docType', 'createdBy', 'updatedBy', 'createdAt', 'updatedAt'], $row)->execute();
        return $response && !empty($list) ? $list : false;
    }
}