<?php

namespace backend\modules\document\models;

use backend\modules\document\services\reader\IReader;
use backend\modules\document\services\reader\ReaderCreator;
use backend\modules\document\services\vsm\AvgSimilarity;
use backend\modules\document\services\vsm\ContextSimilarity;
use backend\modules\document\services\vsm\SoftCosineSimilarity;
use backend\modules\document\services\vsm\VsmSimilar;
use backend\modules\document\services\vsm\VsmSimilarity;
use backend\modules\literature\Literature;
use backend\modules\section\models\Section;
use backend\modules\settings\models\Settings;
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
 * @property float $tth
 * @property int $read_type
 * @property string $pages
 * @property string $theme
 * @property string $method_type
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

    public static function getSuitableDocuments(Section $section)
    {
        $similarDocuments = [];

        /** Document $document */
        foreach (Document::find()->all() as $document) {
            $class = "backend\modules\document\services\\vsm\\CosineSimilarity";
            $similar = new $class($document, $section, null, VsmSimilarity::GET_GENERAL_WORDS_BY_DOCUMENTS);
            $similarity = $similar->getSimilarity();
            if ($similarity !== 0) {
                $similarDocuments[$document->document_name] = $similar->getSimilarity();
            }
        }
        arsort($similarDocuments);

        return $similarDocuments;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_type_id', 'year'], 'integer'],
            [['document_name', 'file_name_before', 'file_name_after', 'description', 'theme'], 'string', 'max' => 255],
            [['document_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentType::className(), 'targetAttribute' => ['document_type_id' => 'id']],
            [['vsm', 'section_id', 'tth', 'read_type', 'pages'], 'safe'],
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
            'tth' => 'Время обработки'
        ];
    }

    public function read(string $filename, $pages = IReader::DEFAULT_PAGES, $typeReading = IReader::BEGIN_PAGES): string
    {
        $extension = substr($filename, strripos($filename, '.') + 1);
        $reader = ReaderCreator::factory($extension);
        $this->read_type = $typeReading;

        $text = $reader->read($filename, Settings::getSettings('DOC_PATH') . '/tmp', $pages, $typeReading);
        if (get_class($reader) === 'backend\modules\document\services\reader\PdfReader') {
            $this->pages = \common\helpers\ArrayHelper::toString($reader->getPages(), ', ');
        }

        return $text;
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
            $saveResult = $keyword->save();
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

    public function getSectionMap(string $type)
    {
        $result = [];
        $docSections = DocumentSection::find()
            ->joinWith('section', true)
            ->where(['document_id' => $this->id])
            ->andWhere(['method_chosen' => $type])
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
        return DocumentSection::findOne([
            'section_id' => $this->section_id,
            'document_id' => $this->id,
            'method_chosen' => $this->method_type
        ]);
    }

    public function setSection($section)
    {
        if ($section !== null) {
            $this->section_id = $section->section_id;
        }
        $this->save();
    }

    public function getMostSuitableSection()
    {
        return DocumentSection::find()->where(['document_id' => $this->id])->orderBy(['similarity' => SORT_DESC])->one();
    }

    public function getNumProperties()
    {
        return $this->getDocumentProperties()->count();
    }
}
