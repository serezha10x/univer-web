<?php

namespace frontend\modules\section\models;

use frontend\modules\document\models\Document;
use frontend\modules\document\models\DocumentSection;
use frontend\modules\document\services\vsm\VsmSimilar;
use Yii;

/**
 * This is the model class for table "section".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $sections
 *
 * @property DocumentSection[] $documentSections
 */
class
Section extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'sections'], 'required'],
            [['sections'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sections' => 'Sections',
        ];
    }

    /**
     * Gets query for [[DocumentSections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentSections()
    {
        return $this->hasMany(DocumentSection::className(), ['section_id' => 'id']);
    }

    public function getVsm()
    {
        return json_decode($this->sections, true, 512, JSON_UNESCAPED_UNICODE);
    }

    public static function getSectionsForDocument(Document $document)
    {
        $sections = self::find()->all();
        $similarSections = [];

        foreach ($sections as $section) {
            $similar = new VsmSimilar($document, $section);
            $similarSections[$section->name] = $similar->cosineSimilar();
        }

        return $similarSections;
    }

    public static function getIdByName(string $name)
    {
        return static::findOne(['name' => $name])->id;
    }
}
