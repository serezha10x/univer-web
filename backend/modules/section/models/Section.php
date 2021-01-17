<?php

namespace backend\modules\section\models;

use backend\modules\document\models\Document;
use backend\modules\document\models\DocumentSection;
use backend\modules\document\services\vsm\VsmSimilar;
use Yii;

/**
 * This is the model class for table "section".
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $name
 * @property string|null $sections
 *
 * @property DocumentSection[] $documentSections
 */
class Section extends \yii\db\ActiveRecord
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
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Section::className(), 'targetAttribute' => ['parent_id' => 'id']],        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название раздела',
            'sections' => 'Ключевые слова',
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

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Section::className(), ['id' => 'parent_id']);
    }

    public function getVsm()
    {
        $sections = json_decode($this->sections, true, 512, JSON_UNESCAPED_UNICODE);
//        $parent = $this->parent_id;
//        $section = $this;
//
//        while ($parent !== null) {
//            $section = $section->getParent()->one();//self::findOne(['parent_id' => $parent]); //$this->getParent()->one()->sections
//            $parent = $section->parent_id;
//            $sections = array_merge($sections, json_decode($section->sections, true, 512, JSON_UNESCAPED_UNICODE));
//        }

        return $sections;
    }

    public static function getSectionsForDocument(Document $document)
    {
        $sections = self::find()->all();
        $similarSections = [];

        foreach ($sections as $section) {
            $similar = new VsmSimilar($document, $section);
            $similarSections[$section->name] = ['similarity' => $similar->cosineSimilar(),
                                                'soft_similarity' => $similar->cosineSoftSimilar()];
        }

        return $similarSections;
    }

    public static function getIdByName(string $name)
    {
        return static::findOne(['name' => $name])->id;
    }
}
