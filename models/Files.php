<?php

namespace deka6pb\autoparser\models;

use SplFileInfo;
use Yii;
use yii\base\ErrorException;
use yii\web\UploadedFile;

/**
 * This is the model class for table "files".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 *
 * @property Posts[] $posts
 */
class Files extends \yii\db\ActiveRecord
{
    public $file;

    const SCENARIO_INSERT = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['url'], 'string'],
            ['name', 'unique'],
            ['url', 'unique'],
            [['name'], 'string', 'max' => 256],
            ['!file', 'file', 'extensions'=>'jpg, png, gif', 'maxSize' => 5242880],
        ];
    }

    public function scenarios()
    {
        return array(
            'default' => array('!file'),
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
        ];
    }

    //region Relations
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostFiles()
    {
        return $this->hasMany(PostFile::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(Files::className(), ['id' => 'file_id'])->viaTable('post_file', ['post_id' => 'id']);
    }
    //endregion

    //region Description
    public function transactions()
    {
        return [
            self::SCENARIO_INSERT => self::OP_INSERT,
            self::SCENARIO_UPDATE => self::OP_UPDATE,
        ];
    }

    public function stopTransaction() {
        $transaction = self::getDb()->getTransaction();
        if($transaction)
            $transaction->rollback();
    }
    //endregion

    public function getFilePath() {
        return \Yii::getAlias(Yii::$app->controller->module->getTmpDir()) . DIRECTORY_SEPARATOR . $this->name;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $file = self::getFilePath($this->name);

        if($this->saveFile($file) === false)
            return false;

        $classInfo = $this->parseClassname(get_class($this));
        $_FILES[$classInfo['classname']] = $this->getUploadFileInfoArray();

        $this->file = UploadedFile::getInstance($this, 'file');
        if( !$this->validate() || !$this->file ) {
            if(file_exists($file))
                unlink($file);
            return false;
        }

        return parent::save($runValidation, $attributeNames);
    }

    public function getUploadFileInfoArray() {
        $filePath = $this->getFilePath($this->name);
        $info = new SplFileInfo($filePath);

        return [
            'error' => [
                'file' => 0
            ],
            'name' => [
                'file' => $info->getFilename()
            ],
            'size' => [
                'file' => $info->getSize()
            ],
            'tmp_name' => [
                'file' => $info->getPathname()
            ],
            'type' => [
                'file' => 'application/' . $info->getExtension()
            ]
        ];
    }

    function parseClassname ($name)
    {
        return array(
            'namespace' => array_slice(explode('\\', $name), 0, -1),
            'classname' => join('', array_slice(explode('\\', $name), -1)),
        );
    }

    public function saveFile($file) {
        if(empty($file))
            throw new ErrorException;

        if(file_exists($file))
            return true;

        if (!copy($this->url, $file)) {
            return false;
        }

        return true;
    }

    public function deleteFile() {
        $file = $this->getFilePath();

        if(file_exists($file))
            unlink($file);
    }
}
