<?php

namespace backend\modules\document\models;

use Yii;

/**
 * This is the model class for table "document_type".
 *
 * @property int $id
 * @property string|null $type
 *
 * @property Document[] $documents
 */
class DocumentType extends \yii\db\ActiveRecord
{
    const KURSOVOY = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
        ];
    }

    /**
     * Gets query for [[Documents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['document_type_id' => 'id']);
    }

    public static function getDocumentType(string $type)
    {
        foreach (DocumentType::find()->all() as $documentType) {
            if ($documentType->type === $type) {
                return $documentType->id;
            }
        }

        $newType = new DocumentType();
        $newType->type = $type;
        $newType->save();

        return $newType->id;
    }
}
