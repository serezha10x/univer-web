<?php

namespace backend\modules\document\models;

use Yii;

/**
 * This is the model class for table "document_property".
 *
 * @property int $id
 * @property int|null $document_id
 * @property int|null $property_id
 * @property string|null $value
 *
 * @property Document $document
 * @property Property $property
 */
class DocumentProperty extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_property';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_id', 'property_id'], 'integer'],
            [['value'], 'string'],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Property::className(), 'targetAttribute' => ['property_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_id' => 'Document ID',
            'property_id' => 'Property ID',
            'value' => 'Value',
        ];
    }

    /**
     * Gets query for [[Document]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }

    /**
     * Gets query for [[Property]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::className(), ['id' => 'property_id']);
    }

    public static function getValuesByProperty(int $documentId, string $property)
    {
        return DocumentProperty::find()->where([
            'document_id' => $documentId,
            'property_id' => Property::getIdByProperty($property)])->all();
    }
}
