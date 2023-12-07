<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Dailypresence $model */

$this->title = 'Tambah Presensi Harian';
$this->params['breadcrumbs'][] = ['label' => 'Presensi Harian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dailypresence-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
