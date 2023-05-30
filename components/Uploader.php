<?php

namespace app\components;

use Yii;
use yii\base\InvalidConfigException;

class Uploader
{
    /**
     * @param $file
     * @param bool $cdnEnable
     * @param string $dir
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function processFile($file, bool $cdnEnable, string $dir = 'uploads/tmp')
    {
        $validationResponse = Utilities::validateFile($file, 'image');
        if ($validationResponse['error']) {
            return $validationResponse;
        }

        $fileName = str_replace(' ', '_', $file->name);
        $fileDir = dirname($fileName);
        $dirPath = !empty($fileDir && $fileDir !== '.') ? ($dir . '/' . $fileDir) : $dir;
        $uploadPath = $dirPath . DIRECTORY_SEPARATOR;
        self::checkDir($uploadPath);
        $originUploadPath = $uploadPath;
        self::checkDir($originUploadPath);
        $baseFileName = basename($fileName);
        if ($file->saveAs($originUploadPath . $baseFileName)) {
            if ($cdnEnable) {
                return ['error' => false, 'cdnUrl' => self::uploadCDN($fileName, $uploadPath . $baseFileName), 'name' => $baseFileName['name'], 'message' => 'File uploaded'];
            } else {
                return ['error' => false, 'cdnUrl' => null, 'name' => $baseFileName, 'message' => 'File uploaded'];
            }
        } else {
            return ['error' => true, 'cdnUrl' => null, 'name' => null, 'message' => 'File upload failed'];
        }
    }

    /**
     * @param $filename
     * @param $path
     * @param string $cdnDir
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function uploadCDN($filename, $path, string $cdnDir = '')
    {
        $s3 = Yii::$app->get('s3');
        $result = $s3->upload(empty($cdnDir) ? $filename : $cdnDir . $filename, $path);
        return $result ? $result['ObjectURL'] : null;
    }

    /**
     * @param $filename
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function deleteCDN($filename)
    {
        $s3 = Yii::$app->get('s3');
        return $s3->delete($filename);
    }

    /**
     * @param $path
     */
    public static function checkDir($path)
    {
        if (is_array($path)) {
            foreach ($path as $p) {
                self::checkDir($p);
            }
        } else {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        }
    }

    /**
     * @param $fileName
     */
    public static function deleteLocalFile($fileName): void
    {
        $parseFileName = ltrim(parse_url($fileName)['path'], '/');
        if ($parseFileName) {
            $fileDelete = getcwd() . '/uploads/tmp/' . $parseFileName;
            if (file_exists($fileDelete)) {
                unlink($fileDelete);
            }
        }
    }

    /**
     * @param $fileName
     * @return mixed|void
     * @throws InvalidConfigException
     */
    public static function deleteFile($fileName)
    {
        $parseFileName = ltrim(parse_url($fileName)['path'], '/');
        if ($parseFileName) {
            $fileDelete = getcwd() . '/uploads/tmp/' . $parseFileName;
            if (file_exists($fileDelete)) {
                unlink($fileDelete);
            }
            return Uploader::deleteCDN($parseFileName);
        }
    }
}