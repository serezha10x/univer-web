<?php

namespace frontend\modules\teacher\models;

use Yii;

/**
 * This is the model class for table "teacher_indicator".
 *
 * @property int $id
 * @property int|null $num_publication
 * @property int|null $num_citations
 * @property int|null $index_hirsha
 *
 * @property Teacher[] $teachers
 * @property Teacher[] $teachers0
 * @property Teacher[] $teachers1
 */
class TeacherIndicator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher_indicator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['num_publication', 'num_citations', 'index_hirsha'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'num_publication' => 'Num Publication',
            'num_citations' => 'Num Citations',
            'index_hirsha' => 'Index Hirsha',
        ];
    }

    /**
     * Gets query for [[Teachers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeachers()
    {
        return $this->hasMany(Teacher::className(), ['google_scholar_id' => 'id']);
    }

    /**
     * Gets query for [[Teachers0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeachers0()
    {
        return $this->hasMany(Teacher::className(), ['science_index_id' => 'id']);
    }

    /**
     * Gets query for [[Teachers1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeachers1()
    {
        return $this->hasMany(Teacher::className(), ['sciverse_scopus_id' => 'id']);
    }
}
