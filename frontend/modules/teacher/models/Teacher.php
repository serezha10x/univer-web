<?php

namespace frontend\modules\teacher\models;

use common\exceptions\NotFoundTeacherException;
use Yii;

/**
 * This is the model class for table "teacher".
 *
 * @property int $id
 * @property string $name
 * @property string $fathername
 * @property string $surname
 * @property string|null $position
 * @property string|null $google_scholar
 * @property int $google_scholar_id
 * @property string|null $science_index
 * @property int|null $science_index_id
 * @property string|null $spin_code
 * @property string|null $sciverse_scopus
 * @property int|null $sciverse_scopus_id
 * @property string|null $scopus_author_id
 *
 * @property TeacherIndicator $googleScholar
 * @property TeacherIndicator $scienceIndex
 * @property TeacherIndicator $sciverseScopus
 */
class Teacher extends \yii\db\ActiveRecord
{
    const GOOGLE_SCHOLAR = 101;
    const SCIENCE_INDEX = 102;
    const SCIVERSE_SCOPUS = 103;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'fathername', 'surname', 'google_scholar_id'], 'required'],
            [['position', 'google_scholar', 'science_index', 'spin_code', 'sciverse_scopus', 'scopus_author_id'], 'string'],
            [['google_scholar_id', 'science_index_id', 'sciverse_scopus_id'], 'integer'],
            [['name', 'fathername', 'surname'], 'string', 'max' => 255],
            [['google_scholar_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeacherIndicator::className(), 'targetAttribute' => ['google_scholar_id' => 'id']],
            [['science_index_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeacherIndicator::className(), 'targetAttribute' => ['science_index_id' => 'id']],
            [['sciverse_scopus_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeacherIndicator::className(), 'targetAttribute' => ['sciverse_scopus_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'fathername' => 'Отчество',
            'surname' => 'Фамилия',
            'position' => 'Должность',
            'google_scholar' => 'Google Scholar',
            'google_scholar_id' => 'Google Scholar ID',
            'science_index' => 'Science Index',
            'science_index_id' => 'Science Index ID',
            'spin_code' => 'Spin Code',
            'sciverse_scopus' => 'Sciverse Scopus',
            'sciverse_scopus_id' => 'Sciverse Scopus ID',
            'scopus_author_id' => 'Scopus Author ID',
        ];
    }

    /**
     * Gets query for [[GoogleScholar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGoogleScholar()
    {
        return $this->hasOne(TeacherIndicator::className(), ['id' => 'google_scholar_id']);
    }

    /**
     * Gets query for [[ScienceIndex]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getScienceIndex()
    {
        return $this->hasOne(TeacherIndicator::className(), ['id' => 'science_index_id']);
    }

    /**
     * Gets query for [[SciverseScopus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSciverseScopus()
    {
        return $this->hasOne(TeacherIndicator::className(), ['id' => 'sciverse_scopus_id']);
    }

    public function getIndications(int $service)
    {
        switch ($service)
        {
            case self::GOOGLE_SCHOLAR:
                $url = $this->google_scholar;
                $parser = new GoogleScholarParser();
                return $parser->getInfo($url);
            case self::SCIENCE_INDEX:


            default:
                ;
        }
    }

    public function createOrUpdateIndications(int $service)
    {
        $teacher_indicator = null;
        switch ($service)
        {
            case self::GOOGLE_SCHOLAR:
                $url = $this->google_scholar;
                $parser = new GoogleScholarParser();
                $info = $parser->getInfo($url);
                if ($this->google_scholar_id === null) {
                    $teacher_indicator = new TeacherIndicator();
                } else {
                    $teacher_indicator = $this->googleScholar;
                }
                $teacher_indicator->num_citations = (int)$info['counts'][0]; // all citations
                $teacher_indicator->index_hirsha = (int)$info['counts'][2]; // index hirsha
                $teacher_indicator->save();
                $this->google_scholar_id = $teacher_indicator->id;
                $this->save();
                break;
            case self::SCIENCE_INDEX:
                $url = $this->science_index;
                $parser = new ELibraryParser();
                $info = $parser->getInfo($url);
                if ($this->science_index_id === null) {
                    $teacher_indicator = new TeacherIndicator();
                } else {
                    $teacher_indicator = $this->scienceIndex;
                }
                $teacher_indicator->num_publication = (int)$info['counts_at_elibrary'][0];
                $teacher_indicator->num_citations = (int)$info['num_citations'][0]; // all citations
                $teacher_indicator->index_hirsha = (int)$info['hirsh_index'][0]; // index hirsha
                $teacher_indicator->save();
                $this->science_index_id = $teacher_indicator->id;
                $this->save();
                break;
            default:
                ;
        }
    }

    public static function isIssetTeacher(int $id): bool
    {
        $teacher = Teacher::findOne(['id' => $id]);
        if ($teacher === null) {
            return false;
        }
        return true;
    }

    public static function getTeachersIdsBySurname(array $surnames): array
    {
        $ids = [];
        foreach ($surnames as $surname) {
            $ids[] = Teacher::findOne(['surname' => $surname])->id;
        }

        return $ids;
    }

}
