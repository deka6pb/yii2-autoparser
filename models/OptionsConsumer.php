<?php
namespace deka6pb\autoparser\models;

use yii\helpers\ArrayHelper;

class OptionsConsumer extends Options {
    public $APP_ID;
    public $APP_SECRET;
    public $ACCESS_TOKEN;
    public $CODE;
    public $GROUP_ID;
    public $ALBUM_ID;
    public $on = 1;

    /**
     * @inheritdoc
     */
    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
            [['APP_ID', 'APP_SECRET', 'ACCESS_TOKEN', 'CODE', 'GROUP_ID', 'ALBUM_ID'], 'required'],
            [['on'], 'integer'],
            [['APP_ID', 'APP_SECRET', 'ACCESS_TOKEN', 'CODE', 'GROUP_ID', 'ALBUM_ID'], 'string', 'max' => 255]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'app_id'       => 'App ID',
            'app_secret'   => 'App Secret',
            'access_token' => 'Access Token',
            'code'         => 'Code',
            'group_id'     => 'Group ID',
            'album_id'     => 'Album ID',
            'on'           => 'On',
        ]);
    }
}