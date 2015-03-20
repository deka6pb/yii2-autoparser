<?php

namespace deka6pb\autoparser\models;

use yii\helpers\ArrayHelper;

class Img extends Posts
{
    public $type = self::TYPE_IMG;
    public $files = [];

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['text', 'string', 'max'=> 15895],
        ]);
    }
}