<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Dailyreport;
use Yii;

/**
 * DailyreportCari represents the model behind the search form of `app\models\Dailyreport`.
 */
class DailyreportCari extends Dailyreport
{
    /**
     * {@inheritdoc}
     */
    public $globalSearch;

    public function rules()
    {
        return [
            [['id_keg', 'timkerjaproject', 'is_setujuketuatim', 'status_selesai', 'priority'], 'integer'],
            [['owner', 'assigned_to', 'rincian_report', 'tanggal_kerja', 'timestamp', 'timestamp_lastupdated', 'ket'], 'safe'],
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
        $query = Dailyreport::find();

        // add conditions that should always apply here
        $query->joinWith(['ownere', 'assignedtoe']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $terms = explode(" ", $this->globalSearch);

        $condition = ['or'];
        foreach ($terms as $key) {
            $condition[] = ['like', 'id_keg', $key];
            $condition[] = ['like', 'timkerjaproject', $key];
            $condition[] = ['like', 'is_setujuketuatim', $key];
            $condition[] = ['like', 'status_selesai', $key];
            $condition[] = ['like', 'tanggal_kerja', $key];
            $condition[] = ['like', 'timestamp', $key];
            $condition[] = ['like', 'timestamp_lastupdated', $key];
            $condition[] = ['like', 'priority', $key];
            $condition[] = ['like', 'owner', $key];
            $condition[] = ['like', 'assigned_to', $key];
            $condition[] = ['like', 'rincian_report', $key];
            $condition[] = ['like', 'ket', $key];
        }
        $query->andWhere($condition);

        return $dataProvider;
    }

    public function searchLintastim($params)
    {
        $query = Dailyreport::find();

        // add conditions that should always apply here
        $query->joinWith(['ownere', 'assignedtoe', 'timkerjae', 'timkerjaprojecte']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }
        return $dataProvider;
    }


    public function searchIndex($params) // khusus untuk site/index
    {
        $query = Dailyreport::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->query->where(['assigned_to' => Yii::$app->user->identity->username])
            ->orWhere(['owner' => Yii::$app->user->identity->username])
            ->orderBy([
                'status_selesai' => SORT_ASC,
                'priority' => SORT_DESC,
                'tanggal_kerja' => SORT_DESC,
                'assigned_to' => SORT_DESC,
            ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }

        // grid filtering 
        /* QUERY DEFAULT DARI Yii2 */
        $query->andFilterWhere([
            'id_keg' => $this->id_keg,
            'timkerjaproject' => $this->timkerjaproject,
            'is_setujuketuatim' => $this->is_setujuketuatim,
            'status_selesai' => $this->status_selesai,
            'tanggal_kerja' => $this->tanggal_kerja,
            'timestamp' => $this->timestamp,
            'timestamp_lastupdated' => $this->timestamp_lastupdated,
            'priority' => $this->priority,
        ]);

        $query->andFilterWhere(['like', 'owner', $this->owner])
            ->andFilterWhere(['like', 'assigned_to', $this->assigned_to])
            ->andFilterWhere(['like', 'rincian_report', $this->rincian_report])
            ->andFilterWhere(['like', 'ket', $this->ket]);

        return $dataProvider;
    }
}
