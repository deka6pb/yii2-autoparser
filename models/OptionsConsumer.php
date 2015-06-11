<?php
namespace deka6pb\autoparser\models;

use yii\base\Model;

class OptionsConsumer extends Model {
    public $name;
    public $class;
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
    public function rules()
    {
        return [
            [['name', 'class', 'APP_ID', 'APP_SECRET', 'ACCESS_TOKEN', 'CODE', 'GROUP_ID', 'ALBUM_ID'], 'required'],
            [['on'], 'integer'],
            [['name', 'class', 'APP_ID', 'APP_SECRET', 'ACCESS_TOKEN', 'CODE', 'GROUP_ID', 'ALBUM_ID'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'class' => 'Class',
            'app_id' => 'App ID',
            'app_secret' => 'App Secret',
            'access_token' => 'Access Token',
            'code' => 'Code',
            'group_id' => 'Group ID',
            'album_id' => 'Album ID',
            'on' => 'On',
        ];
    }

    public function __toString() {
        return $this->name;
    }
}