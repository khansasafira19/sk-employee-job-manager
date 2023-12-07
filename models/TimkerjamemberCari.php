<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Timkerjamember;
use Yii;

/**
 * TimkerjamemberCari represents the model behind the search form of `app\models\Timkerjamember`.
 */
class TimkerjamemberCari extends Timkerjamember
{
    /**
     * {@inheritdoc}
     */
    public $penggunae, $timkerjae, $satkere;
    public function rules()
    {
        return [
            [['id_timkerjamember', 'timkerja', 'is_ketua', 'is_member'], 'integer'],
            [['anggota', 'penggunae', 'timkerjae', 'satkere'], 'safe'],
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
        $query = Timkerjamember::find();

        // add conditions that should always apply here
        $query->joinWith(['penggunae', 'timkerjae', 'penggunasatkere']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['satkere' => SORT_ASC, 'timkerja' => SORT_ASC, 'is_ketua' => SORT_DESC, 'penggunae' => SORT_DESC]]
        ]);

        $dataProvider->query->where('is_member = 1'); //pengguna yang aktif saja, 0 artinya TIDAK aktif

        $anggota = Timkerjamember::find()->where('anggota = "' . Yii::$app->user->identity->username . '"')->andWhere('is_member = 1')->all();

        $listtim = [];
        // foreach ($data as $value) {
        foreach ($anggota as $tim) {
            array_push($listtim, $tim->timkerja);
        }
        $listtimtrim = trim(json_encode($listtim), '[]');

        if (
            Yii::$app->user->identity->levelsuperadmin == false
            && Yii::$app->user->identity->leveladmin == false
            && Yii::$app->user->identity->levelpimpinan == false
            && Yii::$app->user->identity->levelketuatim == false
            && Yii::$app->user->identity->leveladmintu == false
            && Yii::$app->user->identity->levelpegawai == true
        )
            $dataProvider->query->where('timkerjamember.timkerja IN ' . str_replace($listtimtrim, "($listtimtrim)", $listtimtrim)); //member aktif saja 

        if (Yii::$app->user->identity->levelsuperadmin == false)
            $dataProvider->query->andWhere('timkerja.satker = ' . Yii::$app->user->identity->satker);

        $dataProvider->sort->attributes['penggunae'] = [
            'asc' => ['pengguna.pangkatgol' => SORT_ASC],
            'desc' => ['pengguna.pangkatgol' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['satkere'] = [
            'asc' => ['timkerja.satker' => SORT_ASC],
            'desc' => ['timkerja.satker' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_timkerjamember' => $this->id_timkerjamember,
            'timkerja' => $this->timkerja,
            'is_ketua' => $this->is_ketua,
            'is_member' => $this->is_member,
            'timkerja.satker' => $this->satkere
        ]);

        // $query->andFilterWhere(['like', 'anggota', $this->anggota]);
        $query->andFilterWhere(['like', 'pengguna.nama', $this->anggota])
            ->andFilterWhere(['like', 'timkerja.nama_timkerja', $this->timkerjae]);

        return $dataProvider;
    }

    public function searchs($params)
    {
        $query = Timkerjamember::find();

        // add conditions that should always apply here
        $query->joinWith(['penggunae', 'timkerjae', 'penggunasatkere']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['satkere' => SORT_ASC, 'timkerja' => SORT_ASC, 'is_ketua' => SORT_DESC, 'penggunae' => SORT_DESC]]
        ]);

        // $anggota = Timkerjamember::find()->where('anggota = ' . Yii::$app->user->identity->username)->asArray();

        // $dataProvider->query->where('is_member = 0'); //member aktif saja
        $dataProvider->query->where('status_pengguna = 1');
        // $dataProvider->query->andWhere('anggota = ' . Yii::$app->user->identity->username); //member aktif saja

        // if (
        //     Yii::$app->user->identity->levelsuperadmin == false
        //     && Yii::$app->user->identity->leveladmin == false
        //     && Yii::$app->user->identity->levelpimpinan == false
        //     && Yii::$app->user->identity->levelketuatim == false
        //     && Yii::$app->user->identity->leveladmintu == false
        //     && Yii::$app->user->identity->levelpegawai == true
        // )
        //     $dataProvider->query->where('timkerja IN ' . $anggota); //member aktif saja

        if (Yii::$app->user->identity->levelsuperadmin == false)
            $dataProvider->query->where('timkerja.satker = ' . Yii::$app->user->identity->satker);

        $dataProvider->sort->attributes['penggunae'] = [
            'asc' => ['pengguna.pangkatgol' => SORT_ASC],
            'desc' => ['pengguna.pangkatgol' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['satkere'] = [
            'asc' => ['timkerja.satker' => SORT_ASC],
            'desc' => ['timkerja.satker' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_timkerjamember' => $this->id_timkerjamember,
            'timkerja' => $this->timkerja,
            'is_ketua' => $this->is_ketua,
            'is_member' => $this->is_member,
            'timkerja.satker' => $this->satkere
        ]);

        // $query->andFilterWhere(['like', 'anggota', $this->anggota]);
        $query->andFilterWhere(['like', 'pengguna.nama', $this->anggota])
            ->andFilterWhere(['like', 'timkerja.nama_timkerja', $this->timkerjae]);

        return $dataProvider;
    }
}
