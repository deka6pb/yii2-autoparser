<?php
namespace deka6pb\autoparser\models;

use deka6pb\autoparser\components\FileFileSystem;
use yii\web\UploadedFile;

class Files extends FileDB {
    public function save($runValidation = true, $attributeNames = null)
    {
        $filePath = FileFileSystem::getFilePath($this->name);

        if(FileFileSystem::saveFile($this->url, $filePath) === false)
            return false;

        $classInfo = FileFileSystem::parseClassname(get_class($this));
        $_FILES[$classInfo['classname']] = FileFileSystem::getFileInfo($this->name);

        $this->file = UploadedFile::getInstance($this, 'file');

        if( !$this->validate() || !$this->file ) {
            if(file_exists($filePath))
                unlink($filePath);
            return false;
        }

        return parent::save($runValidation, $attributeNames);
    }

    public function delete() {
        FileFileSystem::deleteFile($this->name);
    }
}
