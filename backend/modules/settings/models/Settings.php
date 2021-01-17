<?php

namespace backend\modules\settings\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $key
 * @property string|null $value
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'key', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Описание параметра',
            'key' => 'Ключ',
            'value' => 'Значение',
        ];
    }

    public static function getSettings(string $key)
    {
        return self::findOne(['key' => $key])->value;
    }
}
