<?php
namespace deka6pb\autoparser\models;

use yii\helpers\ArrayHelper;

class OptionsProvider extends Options {
    public $OAUTH_TOKEN;
    public $count;
    public $homepage;
    public $on = 1;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['count', 'OAUTH_TOKEN'], 'required'],
            [['count', 'on'], 'integer'],
            [['homepage', 'OAUTH_TOKEN'], 'string', 'max' => 255]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'OAUTH_TOKEN' => 'Token',
            'count' => 'Count',
            'homepage' => 'Homepage',
            'on' => 'On',
        ]);
    }
}