<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Timkerjaproject;

/**
 * TimkerjaprojectCari represents the model behind the search form of `app\models\Timkerjaproject`.
 */
class TimkerjaprojectCari extends Timkerjaproject
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'timkerja'], 'integer'],
            [['project_name', 'project_description', 'start_date', 'finish_date'], 'safe'],
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
        $query = Timkerjaproject::find();

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
            'id_project' => $this->id_project,
            'timkerja' => $this->timkerja,
            'start_date' => $this->start_date,
            'finish_date' => $this->finish_date,
        ]);

        $query->andFilterWhere(['like', 'project_name', $this->project_name])
            ->andFilterWhere(['like', 'project_description', $this->project_description]);

        return $dataProvider;
    }
}
