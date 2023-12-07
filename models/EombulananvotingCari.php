<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Eombulananvoting;

/**
 * EombulananvotingCari represents the model behind the search form of `app\models\Eombulananvoting`.
 */
class EombulananvotingCari extends Eombulananvoting
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_eombulananvoting', 'eombulanan'], 'integer'],
            [['voter', 'timestamp', 'timestamp_lastupdated'], 'safe'],
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
        $query = Eombulananvoting::find();

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
            'id_eombulananvoting' => $this->id_eombulananvoting,
            'eombulanan' => $this->eombulanan,
            'timestamp' => $this->timestamp,
            'timestamp_lastupdated' => $this->timestamp_lastupdated,
        ]);

        $query->andFilterWhere(['like', 'voter', $this->voter]);

        return $dataProvider;
    }
}
