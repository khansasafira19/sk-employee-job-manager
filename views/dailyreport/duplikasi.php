<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Dailyreport $model */

$this->title = 'Tambah Laporan Harian | Duplikasi';
$this->params['breadcrumbs'][] = ['label' => 'Laporan Pekerjaan Harian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dailyreport-create">

    <?= $this->render('_formduplikasi', [
        'model' => $model,
        'duplikat' => $duplikat
    ]) ?>

</div>
