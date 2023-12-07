<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DailyreportCari $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="dailyreport-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_keg') ?>

    <?= $form->field($model, 'id_ckp') ?>

    <?= $form->field($model, 'owner') ?>

    <?= $form->field($model, 'assigned_to') ?>

    <?= $form->field($model, 'timkerjaproject') ?>

    <?php // echo $form->field($model, 'is_setujuketuatim') ?>

    <?php // echo $form->field($model, 'rincian_report') ?>

    <?php // echo $form->field($model, 'status_selesai') ?>

    <?php // echo $form->field($model, 'tanggal_kerja') ?>

    <?php // echo $form->field($model, 'eomusulan') ?>

    <?php // echo $form->field($model, 'timestamp') ?>

    <?php // echo $form->field($model, 'timestamp_lastupdated') ?>

    <?php // echo $form->field($model, 'priority') ?>

    <?php // echo $form->field($model, 'ket') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
