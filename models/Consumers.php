<?php

namespace deka6pb\autoparser\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/**
 * Consumers represents the model behind the search form about `deka6pb\autoparser\models\Consumer`.
 */
class Consumers extends Consumer
{
    public $filePath;

    public function __construct() {
        $this->filePath = Yii::$app->controller->module->getConsumersFilePath();
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class', 'APP_ID', 'APP_SECRET', 'ACCESS_TOKEN', 'CODE', 'GROUP_ID', 'ALBUM_ID'], 'safe'],
            [['on'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $dataProvider = new ArrayDataProvider([
            'allModels' => Consumer::getAll(),
            'sort' => [
                'attributes' => [],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        return $dataProvider;
    }


}
