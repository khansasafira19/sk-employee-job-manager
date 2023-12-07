<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Dailyreport $model */

$this->title = 'Ubah Laporan Harian: ' . $model->id_keg;
$this->params['breadcrumbs'][] = ['label' => 'Laporan Pekerjaan Harian', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_keg, 'url' => ['view', 'id_keg' => $model->id_keg]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dailyreport-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
