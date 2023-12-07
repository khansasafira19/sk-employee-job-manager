<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Dailypresence;
use Yii;

/**
 * DailypresenceCari represents the model behind the search form of `app\models\Dailypresence`.
 */
class DailypresenceCari extends Dailypresence
{
    /**
     * {@inheritdoc}
     */
    public $globalSearch;
    public function rules()
    {
        return [
            [['id_dailypresence', 'status_presensi'], 'integer'],
            [['tanggal', 'pegawai', 'jam_datang', 'jam_pulang'], 'safe'],
            [['globalSearch'], 'safe'],
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
        $query = Dailypresence::find();

        $query->joinWith(['penggunae']);
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
        // $query->andFilterWhere([
        //     'id_dailypresence' => $this->id_dailypresence,
        //     'tanggal' => $this->tanggal,
        //     'jam_datang' => $this->jam_datang,
        //     'jam_pulang' => $this->jam_pulang,
        //     'status_presensi' => $this->status_presensi,
        // ]);

        // $query->andFilterWhere(['like', 'pegawai', $this->pegawai]);

        $terms = explode(" ", $this->globalSearch);

        $condition = ['or'];
        foreach ($terms as $key) {
            $condition[] = ['like', 'id_dailypresence', $key];
            $condition[] = ['like', 'tanggal', $key];
            $condition[] = ['like', 'jam_datang', $key];
            $condition[] = ['like', 'jam_pulang', $key];
            $condition[] = ['like', 'status_presensi', $key];
            $condition[] = ['like', 'pegawai', $key];
            $condition[] = ['like', 'is_setujuadmin', $key];
        }
        $query->andWhere($condition);

        return $dataProvider;
    }
}
