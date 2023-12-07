<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Levelpengguna;

/**
 * LevelpenggunaCari represents the model behind the search form of `app\models\Levelpengguna`.
 */
class LevelpenggunaCari extends Levelpengguna
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_levelpengguna', 'level', 'autentikasi'], 'integer'],
            [['username'], 'safe'],
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
        $query = Levelpengguna::find();

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
            'id_levelpengguna' => $this->id_levelpengguna,
            'level' => $this->level,
            'autentikasi' => $this->autentikasi,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username]);

        return $dataProvider;
    }
}
