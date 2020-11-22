<?php

namespace frontend\modules\document\models;

use Yii;

/**
 * This is the model class for table "source".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $uri
 */
class Source extends \yii\db\ActiveRecord
{
    const LOCAL_FILE = 'Файл загруженный локально';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'uri'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'uri' => 'Uri',
        ];
    }
}
