<?php

namespace frontend\modules\document\models;

use Yii;

/**
 * This is the model class for table "property".
 *
 * @property int $id
 * @property string|null $property
 *
 * @property DocumentProperty[] $documentProperties
 */
class Property extends \yii\db\ActiveRecord
{
    const KEY_WORDS = 'Ключевые слова';
    const UDK = 'Удк';
    const LITERATURE = 'Литература';
    const FIO = 'ФИО';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'property' => 'Property',
        ];
    }

    /**
     * Gets query for [[DocumentProperties]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentProperties()
    {
        return $this->hasMany(DocumentProperty::className(), ['property_id' => 'id']);
    }

    public static function getPropertyById(int $id)
    {
        return Property::findOne(['id' => $id])->property;
    }

    public static function getIdByProperty(string $property)
    {
        return Property::findOne(['property' => $property])->id;
    }
}
