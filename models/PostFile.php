<?php

namespace deka6pb\autoparser\models;

use deka6pb\autoparser\components\Abstraction\ATransactionModel;
use Yii;

/**
 * This is the model class for table "post_file".
 *
 * @property integer $post_id
 * @property integer $file_id
 *
 * @property Files $file
 * @property Posts $post
 */
class PostFile extends ATransactionModel {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'post_file';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['post_id', 'file_id'], 'required'],
            [['post_id', 'file_id'], 'integer'],
            [['post_id', 'file_id'], 'unique', 'targetAttribute' => ['post_id', 'file_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'post_id' => 'Post ID',
            'file_id' => 'File ID',
        ];
    }

    //region Relations
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile() {
        return $this->hasOne(Files::className(), ['id' => 'file_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost() {
        return $this->hasOne(Posts::className(), ['id' => 'post_id']);
    }
    //endregion
}
