<?php

namespace deka6pb\autoparser\behaviors;

use SplFileInfo;
use Yii;
use yii\base\Behavior;

class FileBehavior extends Behavior {
    public $attribute;
    public $path;

    public function getFileUrl() {
        return Yii::getAlias('@web' . '/' . $this->path) . '/' . $this->owner->{$this->attribute};
    }

    public function getFilePath() {
        return $this->getFileDir() . DIRECTORY_SEPARATOR . $this->owner->{$this->attribute};
    }

    public function getFileDir() {
        return Yii::getAlias('@webroot/' . $this->path);
    }

    public function getFilesInfo($propertyName, $filenames) {
        $result = [];
        foreach ($filenames AS $filename) {
            $info = new SplFileInfo($filename);

            $result['error'][$propertyName][] = 0;
            $result['name'][$propertyName][] = $info->getFilename();
            $result['size'][$propertyName][] = (!empty($info->getSize)) ? $info->getSize() : 0;
            $result['tmp_name'][$propertyName][] = $info->getPathname();
            $result['type'][$propertyName][] = 'application/' . $info->getExtension();
        }

        return $result;
    }

    public function deleteFile() {
        $filePath = self::getFilePath();

        if (file_exists($filePath))
            unlink($filePath);
    }

    public function parseClassname($class) {
        $name = get_class($class);

        return [
            'namespace' => array_slice(explode('\\', $name), 0, -1),
            'classname' => join('', array_slice(explode('\\', $name), -1)),
        ];
    }
}