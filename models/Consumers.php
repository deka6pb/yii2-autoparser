<?php

namespace deka6pb\autoparser\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\BaseJson;

/**
 * This is the model class for table "Consumers".
 *
 * @property integer $id
 * @property string $name
 * @property string $options
 */
class Consumers extends ActiveRecord {
    public $optionsModel;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'consumers';
    }

    public static function getConsumers() {
        return Yii::$app->controller->module->getConsumers();
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'options'], 'required'],
            ['name', 'unique'],
            [['options'], 'string'],
            [['name'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'      => 'ID',
            'name'    => 'Name',
            'options' => 'Options',
        ];
    }

    public function getOptionsToArray() {
        return BaseJson::decode($this->options);
    }

    public function __toString() {
        return $this->options;
    }
}
