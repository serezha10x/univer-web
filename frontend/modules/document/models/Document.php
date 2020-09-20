<?php

namespace frontend\modules\document\models;

use frontend\modules\literature\Literature;
use frontend\modules\teacher\models\Teacher;
use Yii;

/**
 * This is the model class for table "document".
 *
 * @property int $id
 * @property int|null $teacher_id
 * @property string|null $document_name
 * @property int|null $document_type_id
 * @property string|null $file_name_before
 * @property string|null $file_name_after
 *
 * @property DocumentType $documentType
 * @property Teacher $teacher
 * @property Keyword[] $keywords
 * @property Literature[] $literatures
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'document_type_id'], 'integer'],
            [['document_name', 'file_name_before', 'file_name_after'], 'string', 'max' => 255],
            [['document_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentType::className(), 'targetAttribute' => ['document_type_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::className(), 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'teacher_id' => 'Teacher ID',
            'document_name' => 'Document Name',
            'document_type_id' => 'Document Type ID',
            'file_name_before' => 'File Name Before',
            'file_name_after' => 'File Name After',
        ];
    }

    /**
     * Gets query for [[DocumentType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentType()
    {
        return $this->hasOne(DocumentType::className(), ['id' => 'document_type_id']);
    }

    /**
     * Gets query for [[Teacher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['id' => 'teacher_id']);
    }

    /**
     * Gets query for [[Keywords]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKeywords()
    {
        return $this->hasMany(Keyword::className(), ['document_id' => 'id']);
    }

    /**
     * Gets query for [[Literatures]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLiteratures()
    {
        return $this->hasMany(Literature::className(), ['document_id' => 'id']);
    }
}
