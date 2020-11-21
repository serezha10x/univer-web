<?php

namespace frontend\modules\document\models;

use frontend\modules\document\services\reader\ReaderCreator;
use frontend\modules\literature\Literature;
use Yii;

/**
 * This is the model class for table "document".
 *
 * @property int $id
 * @property string|null $document_name
 * @property int|null $document_type_id
 * @property string|null $file_name_before
 * @property string|null $file_name_after
 * @property string|null $doc_source
 *
 * @property DocumentType $documentType
 * @property DocumentTeacher[] $documentTeachers
 * @property Keyword[] $keywords
 * @property Literature[] $literatures
 */
class Document extends \yii\db\ActiveRecord
{
    const NUM_CHARS_FILE_NAME = 50;

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
            [['document_type_id'], 'integer'],
            [['document_name', 'file_name_before', 'file_name_after'], 'string', 'max' => 255],
            [['document_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentType::className(), 'targetAttribute' => ['document_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_name' => 'Название документа',
            'document_type_id' => 'Document Type ID',
            'file_name_before' => 'File Name Before',
            'file_name_after' => 'Название файла',
            'doc_source' => 'Источник файла',
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
     * Gets query for [[DocumentTeachers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentTeachers()
    {
        return $this->hasMany(DocumentTeacher::className(), ['document_id' => 'id']);
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


    public function read(string $filename): string
    {
        $extension = substr($filename, strripos($filename, '.') + 1);
        $reader = ReaderCreator::factory($extension);
        return $reader->read($filename, Yii::$app->params['docs.path']);
    }

    public function parse(string $text)
    {

    }

    public function addKeyWords(array $keyWords)
    {
        foreach ($keyWords as $keyWord) {
            $keyword = new Keyword();
            $keyword->document_id = $this->id;
            $keyword->key = 'Часто встречаемые';
            $keyword->value = $keyWord;
            $keyword->save();
        }
    }

    public function addDocumentProperty(int $propertyId, array $properties)
    {
        foreach ($properties as $property) {
            $keyword = new DocumentProperty();
            $keyword->document_id = $this->id;
            $keyword->property_id = $propertyId;
            $keyword->value = $property;
            $keyword->save();
        }
    }

    public static function getFileNameAfter(string $file_ext)
    {
        return (Yii::$app->getSecurity()->generateRandomString(self::NUM_CHARS_FILE_NAME)) . '.' . $file_ext;
    }
}
