<?php


namespace frontend\modules\document\models;


use frontend\modules\section\models\Section;

trait DocumentRelation
{
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
     * Gets query for [[Section]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Section::className(), ['id' => 'section_id']);
    }

    /**
     * Gets query for [[DocumentProperties]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentProperties()
    {
        return $this->hasMany(DocumentProperty::className(), ['document_id' => 'id']);
    }

    /**
     * Gets query for [[DocumentSections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentSections()
    {
        return $this->hasMany(DocumentSection::className(), ['document_id' => 'id']);
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
}