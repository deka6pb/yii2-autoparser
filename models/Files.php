<?php

namespace deka6pb\autoparser\models;

use deka6pb\autoparser\behaviors\FileBehavior;
use Yii;

/**
 * This is the model class for table "files".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 *
 * @property Posts[] $posts
 */
class Files extends \yii\db\ActiveRecord {
    const SCENARIO_INSERT = 'create';
    const SCENARIO_UPDATE = 'update';
    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'files';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'url'], 'required'],
            [['url'], 'string'],
            ['name', 'unique'],
            ['url', 'unique'],
            [['name'], 'string', 'max' => 256],
            ['!file', 'file', 'extensions' => 'jpg, png, gif', 'maxSize' => 5242880],
        ];
    }

    public function scenarios() {
        return [
            'default' => ['!file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'   => 'ID',
            'name' => 'Name',
            'url'  => 'Url',
        ];
    }

    public function behaviors() {
        return [
            'FileBehavior' => [
                'class'     => FileBehavior::className(),
                'attribute' => 'name',
                'path'      => Yii::$app->controller->module->getTmpDir()
            ]
        ];
    }

    //region Relations
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostFiles() {
        return $this->hasMany(PostFile::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles() {
        return $this->hasMany(Files::className(), ['id' => 'file_id'])->viaTable('post_file', ['post_id' => 'id']);
    }
    //endregion

    //region Description
    public function transactions() {
        return [
            self::SCENARIO_INSERT => self::OP_INSERT,
            self::SCENARIO_UPDATE => self::OP_UPDATE,
        ];
    }

    public function stopTransaction() {
        $transaction = self::getDb()->getTransaction();
        if ($transaction)
            $transaction->rollback();
    }
    //endregion
}
