<?php
namespace deka6pb\autoparser\components;

use SplFileInfo;
use Yii;
use yii\base\ErrorException;

class FileFileSystem {
    public static function getFilePath($name) {
        return \Yii::getAlias(Yii::$app->controller->module->getTmpDir()) . DIRECTORY_SEPARATOR . $name;
    }

    public static function getFileUrl($name) {
        return \Yii::getAlias(Yii::$app->controller->module->getUploadedUrl()) . DIRECTORY_SEPARATOR . $name;
    }

    public static function getFileInfo($filename) {
        $filePath = self::getFilePath($filename);
        $info = new SplFileInfo($filePath);

        return [
            'error'    => [
                'file' => 0
            ],
            'name'     => [
                'file' => $info->getFilename()
            ],
            'size'     => [
                'file' => $info->getSize()
            ],
            'tmp_name' => [
                'file' => $info->getPathname()
            ],
            'type'     => [
                'file' => 'application/' . $info->getExtension()
            ]
        ];
    }

    public static function getFilesInfo($propertyName, $filenames) {
        $result = [];
        foreach ($filenames AS $filename) {
            //$filePath = self::getFilePath($filename);
            $info = new SplFileInfo($filename);

            $result['error'][$propertyName][] = 0;
            $result['name'][$propertyName][] = $info->getFilename();
            $result['size'][$propertyName][] = (!empty($info->getSize)) ? $info->getSize() : 0;
            $result['tmp_name'][$propertyName][] = $info->getPathname();
            $result['type'][$propertyName][] = 'application/' . $info->getExtension();
        }

        return $result;
    }

    public static function saveFile($url, $filePath) {
        if (empty($filePath))
            throw new ErrorException;

        if (file_exists($filePath))
            return true;

        if (!copy($url, $filePath)) {
            return false;
        }

        return true;
    }

    public static function deleteFile($filename) {
        $filePath = self::getFilePath($filename);

        if (file_exists($filePath))
            unlink($filePath);
    }

    public static function parseClassname($name) {
        return [
            'namespace' => array_slice(explode('\\', $name), 0, -1),
            'classname' => join('', array_slice(explode('\\', $name), -1)),
        ];
    }

    static function array2file($value, $filename) {
        file_put_contents($filename, $value, FILE_TEXT);
    }

    static function array_from_file($filename) {
        return include $filename;
    }
}