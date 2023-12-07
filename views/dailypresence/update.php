<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Dailypresence $model */

$this->title = 'Ubah Presensi Harian: ' . $model->id_dailypresence;
$this->params['breadcrumbs'][] = ['label' => 'Presensi Harian', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_dailypresence, 'url' => ['view', 'id_dailypresence' => $model->id_dailypresence]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dailypresence-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
