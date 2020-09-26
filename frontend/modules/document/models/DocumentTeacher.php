<?php

namespace frontend\modules\document\models;

use frontend\modules\teacher\models\Teacher;
use Yii;

/**
 * This is the model class for table "document_teacher".
 *
 * @property int $id
 * @property int|null $document_id
 * @property int|null $teacher_id
 *
 * @property Document $document
 * @property Teacher $teacher
 */
class DocumentTeacher extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_teacher';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_id', 'teacher_id'], 'integer'],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
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
            'document_id' => 'Document ID',
            'teacher_id' => 'Teacher ID',
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
     * Gets query for [[Teacher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['id' => 'teacher_id']);
    }
}
