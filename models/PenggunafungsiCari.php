<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fungsi;

/**
 * FungsiCari represents the model behind the search form of `app\models\Fungsi`.
 */
class PenggunafungsiCari extends Penggunafungsi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_fungsi'], 'integer'],
            [['nama_fungsi', 'koordinator'], 'safe'],
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
        $query = Penggunafungsi::find();

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
            'id_fungsi' => $this->id_fungsi,
        ]);

        $query->andFilterWhere(['like', 'nama_fungsi', $this->nama_fungsi])
            ->andFilterWhere(['like', 'koordinator', $this->koordinator]);

        return $dataProvider;
    }
}
