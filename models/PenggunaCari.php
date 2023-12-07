<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pengguna;

/**
 * PenggunaCari represents the model behind the search form of `app\models\Pengguna`.
 */
class PenggunaCari extends Pengguna
{

    /**
     * {@inheritdoc}
     */
    public $pangkatgole, $tingkatane, $provinsie, $kabupatene, $levele, $fungsie, $globalSearch;

    public function rules()
    {
        return [
            [['username', 'password', 'nama', 'pangkatgole', 'fungsie'], 'safe'],
            [['nip', 'status_pengguna'], 'integer'],
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
        $query = Pengguna::find();

        // add conditions that should always apply here
        $query->joinWith(['pangkatgole', 'fungsie', 'satkere', 'subfungsie', 'jabatane']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['satker' => SORT_ASC, 'pangkatgol' => SORT_DESC, 'nip'=>SORT_DESC]]
        ]);

        // $dataProvider->query->andWhere('status_pengguna = 1');
        // $dataProvider->query->where('status_pengguna = 1'); //pengguna yang aktif saja, 0 artinya TIDAK aktif

        $dataProvider->sort->attributes['fungsie'] = [
            'asc' => ['penggunafungsi.id_fungsi' => SORT_ASC],
            'desc' => ['penggunafungsi.id_fungsi' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // // grid filtering conditions
        // $query->andFilterWhere([
        //     //'nip' => $this->nip,
        //     'pangkatgol' => $this->pangkatgol,
        //     'status_pengguna' => $this->status_pengguna,
        // ]);

        // $query->andFilterWhere(['like', 'username', $this->username])
        //     ->andFilterWhere(['like', 'password', $this->password])
        //     ->andFilterWhere(['like', 'nama', $this->nama])
        //     ->andFilterWhere(['like', 'nip', $this->nip])
        //     ->andFilterWhere(['like', 'penggunafungsi.nama_fungsi', $this->fungsie]);
        // $query->andFilterWhere([
        //     'penggunapan' => $this->globalSearch,
        //     'status_pengguna' => $this->globalSearch,
        // ]);

        $query->orFilterWhere(['like', 'nama', $this->globalSearch])
            ->orFilterWhere(['like', 'nip', $this->globalSearch])
            ->orFilterWhere(['like', 'gelar_depan', $this->globalSearch])
            ->orFilterWhere(['like', 'gelar_belakang', $this->globalSearch])
            ->orFilterWhere(['like', 'penggunasatker.nama_satker', $this->globalSearch])
            ->orFilterWhere(['like', 'penggunafungsi.nama_fungsi', $this->globalSearch])
            ->orFilterWhere(['like', 'penggunasubfungsi.nama_subfungsi', $this->globalSearch])
            ->orFilterWhere(['like', 'penggunapangkatgol.nama_pangkatgol', $this->globalSearch])
            ->orFilterWhere(['like', 'penggunajabatan.nama_jabatan', $this->globalSearch])
            ->orFilterWhere(['like', 'email', $this->globalSearch]);

        return $dataProvider;
    }

    public function searchverifikasi($params)
    {
        $query = Pengguna::find();

        // add conditions that should always apply here
        //$query->joinWith(['jenjange', 'tingkatane', 'provinsie', 'kabupatene', 'levelpenggunae']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->query->where('status_pengguna = 0'); //pengguna yang aktif saja, 0 artinya TIDAK aktif
        //$dataProvider->query->andWhere('verifikasi_data = 1'); //pengguna yang datanya diverifikasi saja, 1 artinya SUDAH verifikasi

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'nip' => $this->nip,
            'pangkatgol' => $this->pangkatgol,
            'status_pengguna' => $this->status_pengguna,
            'penggunapangkatgol.id_pangkatgol' => $this->pangkatgole,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'nama', $this->nama]);

        return $dataProvider;
    }
}
