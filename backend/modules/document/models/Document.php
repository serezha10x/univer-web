<?php

namespace backend\modules\document\models;

use backend\modules\document\services\reader\ReaderCreator;
use backend\modules\document\services\vsm\VsmSimilar;
use backend\modules\literature\Literature;
use backend\modules\section\models\Section;
use Yii;

/**
 * This is the model class for table "document".
 *
 * @property int $id
 * @property string|null $document_name
 * @property string|null $description
 * @property int|null $document_type_id
 * @property int|null $section_id
 * @property string|null $file_name_before
 * @property string|null $file_name_after
 * @property int|null $year
 * @property string|null $doc_source
 * @property string|null $vsm
 *
 * @property DocumentType $documentType
 * @property Section $section
 * @property DocumentProperty[] $documentProperties
 * @property DocumentSection[] $documentSections
 * @property DocumentTeacher[] $documentTeachers
 * @property Keyword[] $keywords
 * @property Literature[] $literatures
 */
class Document extends \yii\db\ActiveRecord
{
    const NUM_CHARS_FILE_NAME = 50;
    use DocumentRelation;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    public static function getFileNameAfter(string $file_ext)
    {
        return (Yii::$app->getSecurity()->generateRandomString(self::NUM_CHARS_FILE_NAME)) . '.' . $file_ext;
    }

    public static function getDocumentsByIds(string $ids)
    {
        preg_match_all("@\d+@u", $ids, $matches);
        $ids = $matches[0];
        $ids = array_map(function ($item) {
            return ['id' => $item];
        }, $ids);

        return Document::find()->where(['IN', ['id'], $ids])->all();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_type_id', 'year'], 'integer'],
            [['document_name', 'file_name_before', 'file_name_after', 'description'], 'string', 'max' => 255],
            [['document_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentType::className(), 'targetAttribute' => ['document_type_id' => 'id']],
            [['vsm', 'section_id'], 'safe'],
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

    public function read(string $filename): string
    {
        $extension = substr($filename, strripos($filename, '.') + 1);
        $reader = ReaderCreator::factory($extension);
        return $reader->read($filename, Yii::$app->params['docs.path'] . '/tmp');
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
        if ($properties === null) {
            return;
        }
        foreach ($properties as $property) {
            $keyword = new DocumentProperty();
            $keyword->document_id = $this->id;
            $keyword->property_id = $propertyId;
            $keyword->value = $property;
            $keyword->save();
        }
    }

    public function updateProperties($newProperties, $property_id)
    {
        if ($newProperties === null) {
            DocumentProperty::deleteAll(['property_id' => $property_id]);
            return;
        }
        $oldProperties = DocumentProperty::find()
            ->select('id')
            ->where(['document_id' => $this->id, 'property_id' => $property_id])
            ->asArray()
            ->all();
        foreach ($oldProperties as $oldProperty) {
            if (!in_array($oldProperty['id'], $newProperties)) {
                DocumentProperty::deleteAll(['id' => $oldProperty]);
            }
        }
    }

    public function updateTeachers($teachersId)
    {
        DocumentTeacher::deleteAll(['document_id' => $this->id]);
        if ($teachersId === null) {
            return;
        }
        foreach ($teachersId as $teacherId) {
            $documentTeacher = new DocumentTeacher();
            $documentTeacher->document_id = $this->id;
            $documentTeacher->teacher_id = $teacherId;
            $documentTeacher->save();
        }
    }

    public function getVsm()
    {
        return json_decode($this->vsm, true) ?? [];
    }

    public function setVsm($vsm)
    {
        $this->vsm = json_encode($vsm, JSON_UNESCAPED_UNICODE);
    }

    public function getSectionMap()
    {
        $result = [];
        $docSections = DocumentSection::find()
            ->joinWith('section', true)
            ->where(['document_id' => $this->id])
            ->orderBy(['similarity' => SORT_DESC])
            ->all();
        foreach ($docSections as $docSection) {
            $result[$docSection->section->id] = $docSection->section->name . ': ' . $docSection->similarity;
        }

        return $result;
    }

    public function getSectionSoftMap()
    {
        $result = [];
        $docSections = DocumentSection::find()
            ->joinWith('section', true)
            ->where(['document_id' => $this->id])
            ->orderBy(['soft_similarity' => SORT_DESC])
            ->all();
        foreach ($docSections as $docSection) {
            $result[$docSection->section->id] = $docSection->section->name . ': ' . $docSection->soft_similarity;
        }

        return $result;
    }

    public function getDocumentSection()
    {
        return DocumentSection::findOne(['section_id' => $this->section_id, 'document_id' => $this->id]);
    }

    public function setSection($section)
    {
        if ($section !== null) {
            $this->section_id = $section->section_id;
        }
    }

    public function getMostSuitableSection()
    {
        return DocumentSection::find()->where(['document_id' => $this->id])->orderBy(['similarity' => SORT_DESC])->one();
    }

    public static function getSuitableDocuments(Section $section)
    {
        $similarDocuments = [];

        foreach (Document::find()->all() as $document) {
            $similar = new VsmSimilar($document, $section, VsmSimilar::GET_GENERAL_WORDS_BY_DOCUMENTS);
            $similarDocuments[] = [
                'doc' => $document,
                'similarity' => $similar->cosineSimilar(),
                'soft_similarity' => $similar->cosineSoftSimilar()
            ];
        }

//var_dump($similarDocuments);die;
        return $similarDocuments;
    }
}
