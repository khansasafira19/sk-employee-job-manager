<?php
/* Progress Bulanan */

use app\models\Dailyreport;
use app\models\Eombulanan;
use app\models\Timkerjaproject;
use yii\data\ActiveDataProvider;

$persen = Timkerjaproject::find()->select('(count(case when status_selesai = 1 then 1 end))/(count(id_keg))*100 as persentase')->joinWith(['timkerjae', 'dailyreporte'])
    ->where(['satker' => Yii::$app->user->identity->satker])
    ->andWhere(['tahun' => date("Y")])->one();
$progress = $persen['persentase'];

/* Target Bulanan */
$target = Timkerjaproject::find()->select('count(id_keg) as totaltarget')->joinWith(['timkerjae', 'dailyreporte'])
    ->where(['satker' => Yii::$app->user->identity->satker])
    ->andWhere(['tahun' => date("Y")])->one();
$totaltarget = $target['totaltarget'];

/* Progress 2 Mingguan */
$duamingguan = Dailyreport::find()->select('tanggal_kerja, count(id_keg) as totaltarget, count(case when status_selesai = 1 then 1 end) as totalselesai')
    ->groupBy('tanggal_kerja')->orderBy('tanggal_kerja DESC')->all();

$seriestarget = [];
$seriesselesai = [];
$seriestanggal = [];

for ($x = 2; $x >= 0; $x--) {
    array_push($seriestarget, $duamingguan[$x]['totaltarget']);
    array_push($seriesselesai, $duamingguan[$x]['totalselesai']);
    array_push($seriestanggal, date('d M', strtotime($duamingguan[$x]['tanggal_kerja'])));
}

/* Jumlah tasks selesai dibagi jumlah tasks terkait project * 100% */
/* Ongoing */
$dataProvider = new ActiveDataProvider([
    'query' => Timkerjaproject::find()->select('timkerja, project_name, start_date, finish_date, status_selesai, count(case when status_selesai = 1 then 1 end) as totalselesai, 
count(id_keg) as totaltarget, 
(count(case when status_selesai = 1 then 1 end))/(count(id_keg))*100 as persentase')->joinWith(['timkerjae', 'dailyreporte'])
        ->where(['satker' => Yii::$app->user->identity->satker])
        ->andWhere(['tahun' => date("Y")])
        ->andWhere('finish_date > CURDATE()')
        ->andWhere('start_date < CURDATE()')
        ->groupBy('id_project'),

    'pagination' => [
        'pageSize' => 20,
    ],
]);

/* Future Plans */
$dataProviderFuture = new ActiveDataProvider([
    'query' => Timkerjaproject::find()->select('timkerja, project_name, start_date, finish_date, status_selesai, count(case when status_selesai = 1 then 1 end) as totalselesai, 
count(id_keg) as totaltarget, 
(count(case when status_selesai = 1 then 1 end))/(count(id_keg))*100 as persentase')->joinWith(['timkerjae', 'dailyreporte'])
        ->where(['satker' => Yii::$app->user->identity->satker])
        ->andWhere(['tahun' => date("Y")])
        ->andWhere('start_date > CURDATE()')
        ->groupBy('id_project'),

    'pagination' => [
        'pageSize' => 20,
    ],
]);

/* Finished Projects */
$dataProviderFinished = new ActiveDataProvider([
    'query' => Timkerjaproject::find()->select('timkerja, project_name, start_date, finish_date, status_selesai, count(case when status_selesai = 1 then 1 end) as totalselesai, 
count(id_keg) as totaltarget, 
(count(case when status_selesai = 1 then 1 end))/(count(id_keg))*100 as persentase')->joinWith(['timkerjae', 'dailyreporte'])
        ->where(['satker' => Yii::$app->user->identity->satker])
        ->andWhere(['tahun' => date("Y")])
        ->andWhere('finish_date < CURDATE()')
        ->groupBy('id_project'),

    'pagination' => [
        'pageSize' => 20,
    ],
]);

$style =
    '
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap");
body {
    font-family: "Poppins", sans-serif !important;
    font-size: 14px !important;
    background: whitesmoke;
    border-radius: 20px;
    padding: 10px;
    color: #15133C;
    text-shadow: px 6px 4px rgba(0, 0, 0, 0.2), 0px -5px 16px rgba(255, 255, 255, 0.3);
    box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
}
table, td, th {
    border: 1px solid black;
}

table {
    border-collapse: collapse;
    width: 100%;
}

.table-no-border tr td th {
    border: none;
}

td {
    height: 50px;
    vertical-align: middle;
    text-align: center;
}

td,
th {
    padding: 4px;
}


.table th,
.table td {
    vertical-align: middle !important;
}

.table {
    font-size: 14px !important;
}

.table>tbody>tr>td {
    padding: 4px !important;
}

.content-header {
    display: none !important;
}

';
