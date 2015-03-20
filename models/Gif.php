<?php

namespace deka6pb\autoparser\models;

use yii\helpers\ArrayHelper;

class Gif extends Posts
{
    public $type = self::TYPE_GIF;
    public $files = [];

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['text', 'string', 'max'=> 15895],
        ]);
    }
}