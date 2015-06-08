<?php

namespace deka6pb\autoparser\models;

use deka6pb\autoparser\components\FileFileSystem;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "consumer".
 *
 * @property string $name
 * @property string $class
 * @property string $APP_ID
 * @property string $APP_SECRET
 * @property string $ACCESS_TOKEN
 * @property string $CODE
 * @property string $GROUP_ID
 * @property string $ALBUM_ID
 * @property bool $on
 */
class Consumer extends \yii\db\ActiveRecord
{
    protected $filePath;

    public function __construct() {
        $this->filePath = Yii::$app->controller->module->getConsumersFilePath();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class', 'APP_ID', 'APP_SECRET', 'ACCESS_TOKEN', 'GROUP_ID', 'ALBUM_ID', 'on'], 'required'],
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
            'APP_ID' => 'App  ID',
            'APP_SECRET' => 'App  Secret',
            'ACCESS_TOKEN' => 'Access  Token',
            'CODE' => 'Code',
            'GROUP_ID' => 'Group  ID',
            'ALBUM_ID' => 'Album  ID',
            'on' => 'On',
        ];
    }

    public function save($runValidation = true, $attributeNames = null) {
        return $this->merge($runValidation, $attributeNames);
    }

    public function merge($runValidation = true, $attributes = null) {
        if ($runValidation && !$this->validate($attributes)) {
            Yii::info('Model not inserted due to validation error.', __METHOD__);
            return false;
        }

        try {
            $id = Yii::$app->request->get('id');
            $data = Yii::$app->request->post('Consumer');

            $consumers = FileFileSystem::array_from_file($this->filePath);

            if($id)
                ArrayHelper::remove($consumers['consumers'], $id);

            $name = $data['name'];
            ArrayHelper::remove($data, 'name');
            $temp[$name] = $data;

            $consumers['consumers'] = array_merge($consumers['consumers'], $temp);

            $config = $this->generateConfigFile($consumers);

            file_put_contents($this->filePath, $config, FILE_TEXT);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function generateConfigFile($config)
    {
        $this->generateConfigFileRecursive($config, $output);
        $output = preg_replace('#,$\n#s', '', $output);
        return "<?php\nreturn " . $output . ";\n";
    }

    public function generateConfigFileRecursive($attributes, &$output = "", $depth = 1)
    {
        $output .= "[\n";
        foreach ($attributes as $attribute => $value) {
            if (!is_array($value)) {
                $output .= str_repeat("\t", $depth) . "'" . $this->escape($attribute) . "' => '" . $this->escape($value) . "',\n";
            } else {
                $output .= str_repeat("\t", $depth) . "'" . $this->escape($attribute) . "' => ";
                $this->generateConfigFileRecursive($value, $output, $depth + 1);
            }
        }
        $output .= str_repeat("\t", $depth - 1) . "],\n";
    }

    private function escape($value)
    {
        return str_replace("'", "\'", $value);
    }

    public static function findOne($id) {
        $model = new Consumer();
        $consumers = self::getAll();

        foreach($consumers AS $consumer) {
            if($consumer['name'] == $id)
                $model->attributes = $consumer;
        }

        return $model;
    }

    public function delete() {
        try {
            $id = Yii::$app->request->get('id');

            $consumers = FileFileSystem::array_from_file($this->filePath);

            if($id)
                unset($consumers['consumers'][$id]);

            $config = $this->generateConfigFile($consumers);

            file_put_contents($this->filePath, $config, FILE_TEXT);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function getAll() {
        $result = [];

        $data = FileFileSystem::array_from_file(Yii::$app->controller->module->getConsumersFilePath());

        foreach($data['consumers'] AS $name=>$consumer) {
            $consumer['name'] = $name;
            $result[] = $consumer;
        }

        return $result;
    }
}
