<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Eombulanan;

/**
 * EombulananCari represents the model behind the search form of `app\models\Eombulanan`.
 */
class EombulananCari extends Eombulanan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_eombulanan', 'tahun', 'bulan', 'satker', 'ranking_sistem', 'ranking_voting', 'pilihan_pimpinan'], 'integer'],
            [['pegawai', 'timestamp', 'timestamp_lastupdated'], 'safe'],
            [['satu_persen', 'dua_persen', 'tiga_persen', 'empat_persen', 'lima_persen', 'enam_persen'], 'number'],
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
        $query = Eombulanan::find();

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
            'id_eombulanan' => $this->id_eombulanan,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'satker' => $this->satker,
            'ranking_sistem' => $this->ranking_sistem,
            'ranking_voting' => $this->ranking_voting,
            'satu_persen' => $this->satu_persen,
            'dua_persen' => $this->dua_persen,
            'tiga_persen' => $this->tiga_persen,
            'empat_persen' => $this->empat_persen,
            'lima_persen' => $this->lima_persen,
            'enam_persen' => $this->enam_persen,
            'pilihan_pimpinan' => $this->pilihan_pimpinan,
            'timestamp' => $this->timestamp,
            'timestamp_lastupdated' => $this->timestamp_lastupdated,
        ]);

        $query->andFilterWhere(['like', 'pegawai', $this->pegawai]);

        return $dataProvider;
    }
}
