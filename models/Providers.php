<?php

namespace deka6pb\autoparser\models;

use Yii;
use yii\helpers\BaseJson;

/**
 * This is the model class for table "providers".
 *
 * @property integer $id
 * @property string $name
 * @property string $options
 */
class Providers extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'providers';
    }

    public static function getProviders() {
        $local = Yii::$app->controller->module->getProviders();

        return Yii::$app->controller->module->getProviders();
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'options'], 'required'],
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
