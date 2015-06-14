<?php
namespace deka6pb\autoparser\models;

use yii\base\Model;

abstract class Options extends Model {
    public $name;
    public $class;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class'], 'required'],
            [['name', 'class'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'class' => 'Type',
        ];
    }

    public function __toString() {
        return $this->name;
    }
}