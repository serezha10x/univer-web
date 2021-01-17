<?php

namespace backend\modules\document\models;

use Yii;

/**
 * This is the model class for table "keyword".
 *
 * @property int $id
 * @property int|null $document_id
 * @property string|null $key
 * @property string|null $value
 *
 * @property Document $document
 */
class Keyword extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'keyword';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_id'], 'integer'],
            [['key', 'value'], 'string', 'max' => 255],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
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
            'key' => 'Key',
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

    public static function getKeywordById(int $id)
    {
        return Keyword::findOne($id);
    }

}
