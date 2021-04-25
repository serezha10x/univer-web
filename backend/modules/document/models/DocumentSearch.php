<?php

namespace backend\modules\document\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\document\models\Document;

/**
 * DocumentSearch represents the model behind the search form of `backend\modules\document\models\Document`.
 */
class DocumentSearch extends Document
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'document_type_id'], 'integer'],
            [['document_name', 'file_name_before', 'file_name_after'], 'safe'],
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
        $query = Document::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
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
            'document_type_id' => $this->document_type_id,
        ]);

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
            ->andFilterWhere(['like', 'file_name_before', $this->file_name_before])
            ->andFilterWhere(['like', 'file_name_after', $this->file_name_after]);

        return $dataProvider;
    }
}
