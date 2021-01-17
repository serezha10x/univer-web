<?php

namespace backend\modules\document\models;

use backend\modules\section\models\Section;
use Yii;

/**
 * This is the model class for table "document_section".
 *
 * @property int $id
 * @property int|null $document_id
 * @property int|null $section_id
 * @property float|null $similarity
 * @property float|null $soft_similarity
 * @property boolean is_soft_similarity_chosen
 *
 * @property Document $document
 * @property Section $section
 */
class DocumentSection extends \yii\db\ActiveRecord
{
    const SOFT_SIMILARITY_TYPE = 'Мягкий косинус';
    const COMMON_SIMILARITY_TYPE = 'Обычный косинус';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_id', 'section_id'], 'integer'],
            [['similarity', 'soft_similarity'], 'number'],
            [['is_soft_similarity_chosen'], 'boolean'],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => Section::className(), 'targetAttribute' => ['section_id' => 'id']],
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
            'section_id' => 'Section ID',
            'similarity' => 'Similarity',
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
     * Gets query for [[Section]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Section::className(), ['id' => 'section_id']);
    }

    public function setSoftSimilar(bool $isSoftSimilarityChosen)
    {
        $this->is_soft_similarity_chosen = $isSoftSimilarityChosen;
        $this->save();
    }
}
