<?php

namespace deka6pb\autoparser\models;

use deka6pb\autoparser\components\Abstraction\IDataTimeFormats;
use deka6pb\autoparser\components\Abstraction\IItemStatus;
use deka6pb\autoparser\components\Abstraction\IItemType;
use deka6pb\autoparser\components\DateTimeStampBehavior;
use deka6pb\autoparser\components\FileFileSystem;
use deka6pb\autoparser\components\TItemStatus;
use deka6pb\autoparser\components\UploadedFiles;
use Faker\Provider\File;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property integer $type
 * @property string $text
 * @property integer $status
 * @property string $tags
 * @property integer $sid
 * @property string $provider
 * @property string $created
 * @property string $published
 * @property string $url
 *
 * @property PostFile[] $postFiles
 * @property Files[] $files
 * @property Files $postFile
 */
class Posts extends \yii\db\ActiveRecord implements IItemStatus, IItemType, IDataTimeFormats
{
    use TItemStatus;

    private $files;

    const SCENARIO_INSERT = 'create';
    const SCENARIO_UPDATE = 'update';

    const DEFAULT_TYPE = 'Manually';

    public function init() {

    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status', 'sid', 'provider'], 'required'],
            [['type', 'status', 'sid'], 'integer'],
            ['sid', 'unique'],
            [['text'], 'string'],
            [['tags', 'provider'], 'string', 'max' => 256],
            [['url'], 'string', 'max' => 2083],
            ['text', 'string', 'max'=> 15895],
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif', 'maxFiles' => 8],
            [['files'], 'required', 'when' => function($model) {
                return $model->type == 2 || $model->type == 3;
            }],
        ];
    }

    protected function addCondition($query, $attribute, $partialMatch = false)
    {
        if (($pos = strrpos($attribute, '.')) !== false) {
            $modelAttribute = substr($attribute, $pos + 1);
        } else {
            $modelAttribute = $attribute;
        }

        $value = $this->$modelAttribute;
        if (trim($value) === '') {
            return;
        }

        /*
         * The following line is additionally added for right aliasing
         * of columns so filtering happen correctly in the self join
         */
        $attribute = $this->tableName().".".$attribute;

        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }

    public function behaviors()
    {
        return [
            'DateTimeStampBehavior' => [
                'class' => DateTimeStampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => 'created',
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => 'published',
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'text' => 'Text',
            'status' => 'Status',
            'tags' => 'Tags',
            'post_file_id' => 'Post File ID',
            'sid' => 'Sid',
            'provider' => 'Provider',
            'created' => 'Created',
            'published' => 'Published',
            'url' => 'Url'
        ];
    }

    public static function getStatusAliases(){
        return [
            ''                              => 'All',
            self::STATUS_NEW                => 'New',
            self::STATUS_PUBLISHED          => 'Published',
            self::STATUS_STOPPED            => 'Stoped',
        ];
    }

    public static function getTypesAliases(){
        return [
            self::TYPE_TEXT                 => 'Text',
            self::TYPE_IMG                  => 'Text & Picture',
            self::TYPE_GIF                  => 'Text & Animation',
        ];
    }

    //region Transaction
    public function transactions()
    {
        return [
            self::SCENARIO_INSERT => self::OP_INSERT,
            //self::SCENARIO_UPDATE => self::OP_UPDATE,
        ];
    }

    public function stopTransaction() {
        $transaction = self::getDb()->getTransaction();
        if($transaction)
            $transaction->rollback();
    }
    //endregion

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_INSERT => ['type', 'text', 'status', 'tags', 'file_id', 'sid', 'provider', 'created', 'published', 'url'],
            self::SCENARIO_UPDATE => ['published'],
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

    public function setFiles($value) {
        $this->files[] = $value;
    }
    //endregion

    /**
     * @return string Читабельный статус поста.
     */
    public function getStatus()
    {
        $status = self::getStatusAliases();
        return $status[$this->status];
    }

    public function getNewPosts($count) {
        return $this::find()
            ->with('files')
            ->where(['status' => self::STATUS_NEW])
            ->limit($count)
            ->all();
    }

    //region Get and Set
    public function setText($text) {
        $text = addslashes(strip_tags(str_replace(["<br>", "<br/>", "<br />"], "", $text)));
        $this->text = $text;
    }

    public function getText() {
        return $this->text;
    }
    //endregion

    public function beforeValidate() {
        if(!isset(self::scenarios()[$this->scenario])) {
            return parent::beforeValidate();
        }

        if($this->scenario === self::SCENARIO_INSERT) {
            $this->status = self::STATUS_NEW;
        }

        if(!is_int($this->type)) {
            $this->type = (int) $this->type;
        }

        if(empty($this->provider)) {
            $this->provider = self::DEFAULT_TYPE;
        }

        if(empty($this->sid)) {
            $last_post = Posts::find()->where(['provider' => $this->provider])->orderBy('sid desc')->one();
            $last_sid = (!empty($last_post->sid)) ? $last_post->sid : 0;
            $this->sid = ++$last_sid;
        }

        return parent::beforeValidate();
    }

    public function beforeSave($insert) {
        $urls = [];
        if(!empty($this->files)) {
            foreach($this->files AS $file) {
                $urls[] = $file->url;
            }
            $classInfo = FileFileSystem::parseClassname(get_class($this));
            $_FILES[$classInfo['classname']] = FileFileSystem::getFilesInfo('files', $urls);
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes) {
        $this->files = UploadedFiles::getInstances($this, 'files');

        if(!empty($this->files)) {
            if(!$this->upload()) {
                return false;
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public static function countNewPosts() {
        return Posts::find()
            ->where(['status' => self::STATUS_NEW])
            ->count();
    }

    public function upload() {
        if ($this->validate()) {
            foreach ($this->files as $file) {
                $filename = $file->baseName . '.' . $file->extension;
                $file->saveAs(FileFileSystem::getFilePath($filename));

                $fileModel = new Files();
                $fileModel->name = $file->name;
                $fileModel->url = FileFileSystem::getFilePath($file->name);

                if(!$fileModel->save()) {
                    $file->stopTransaction();
                    return false;
                }

                $postFile = new PostFile();
                $postFile->post_id = $this->id;
                $postFile->file_id = $fileModel->id;

                if(!$postFile->validate() || !$postFile->save()) {
                    $postFile->stopTransaction();
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
