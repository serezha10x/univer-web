<?php

namespace frontend\modules\teacher\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\teacher\models\Teacher;

/**
 * TeacherSearch represents the model behind the search form of `frontend\modules\teacher\models\Teacher`.
 */
class TeacherSearch extends Teacher
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'google_scholar_id', 'science_index_id', 'sciverse_scopus_id'], 'integer'],
            [['name', 'fathername', 'surname', 'position', 'google_scholar', 'science_index', 'spin_code', 'sciverse_scopus', 'scopus_author_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Teacher::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'google_scholar_id' => $this->google_scholar_id,
            'science_index_id' => $this->science_index_id,
            'sciverse_scopus_id' => $this->sciverse_scopus_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'fathername', $this->fathername])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'google_scholar', $this->google_scholar])
            ->andFilterWhere(['like', 'science_index', $this->science_index])
            ->andFilterWhere(['like', 'spin_code', $this->spin_code])
            ->andFilterWhere(['like', 'sciverse_scopus', $this->sciverse_scopus])
            ->andFilterWhere(['like', 'scopus_author_id', $this->scopus_author_id]);

        return $dataProvider;
    }
}
