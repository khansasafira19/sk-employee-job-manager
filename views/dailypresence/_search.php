<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DailypresenceCari $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="dailypresence-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_dailypresence') ?>

    <?= $form->field($model, 'tanggal') ?>

    <?= $form->field($model, 'pegawai') ?>

    <?= $form->field($model, 'jam_datang') ?>

    <?= $form->field($model, 'jam_pulang') ?>

    <?php // echo $form->field($model, 'status_presensi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
