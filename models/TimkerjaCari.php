<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Timkerja;
use Yii;

/**
 * TimkerjaCari represents the model behind the search form of `app\models\Timkerja`.
 */
class TimkerjaCari extends Timkerja
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_timkerja', 'tahun', 'satker', 'status'], 'integer'],
            [['nama_timkerja'], 'safe'],
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
        $query = Timkerja::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['satker' => SORT_ASC, 'id_timkerja' => SORT_ASC]]
        ]);

        $dataProvider->query->where('status = 1'); //tim aktif saja

        if (Yii::$app->user->identity->levelsuperadmin == false)
            $dataProvider->query->where('satker = ' . Yii::$app->user->identity->satker);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_timkerja' => $this->id_timkerja,
            'tahun' => $this->tahun,
            'satker' => $this->satker,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'nama_timkerja', $this->nama_timkerja]);

        return $dataProvider;
    }
}
