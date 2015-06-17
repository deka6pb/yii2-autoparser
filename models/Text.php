<?php

namespace deka6pb\autoparser\models;

use yii\helpers\ArrayHelper;

class Text extends Posts
{
    public $type = self::TYPE_TEXT;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['text', 'required'],
        ]);
    }
}