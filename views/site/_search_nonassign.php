<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
// use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Bulan;
use app\models\Dailyreport;

?>
<style>
    .searchflex {
        display: flex;
    }

    .bundarsearch {
        padding-left: 0.5rem;
        padding-bottom: 0.1rem;
        margin-right: 0.2rem;
    }
</style>
<div class="searchflex">
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => ['options' => ['class' => 'form-group mr-2']],
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]);
    ?>
    <?php
    // $form->field($model, 'status_selesai')->dropDownList(['1' => 'Selesai', '0' => 'Belum Selesai'], ['prompt' => 'Status ...'])
    ?>
    <div class="bg-white bundar bundarsearch shadow-sm">
        <?= $form->field($model, 'status_selesai')->radioList(
            [1 => 'Selesai', 0 => 'Belum Selesai'],
            ['custom' => true, 'inline' => true]
        ); ?>
    </div>
    <div class="bg-white bundar bundarsearch shadow-sm">
        <?= $form->field($model, 'priority')->radioList(
            [1 => 'Prioritas', 0 => 'Non Prioritas'],
            ['custom' => true, 'inline' => true]
        ); ?>
    </div>
    <div class="bg-white bundar bundarsearch shadow-sm">
        <?= $form->field($model, 'assigned_to')->radioList(
            [Yii::$app->user->identity->username => 'Dari Ketua Tim'],
            ['custom' => true, 'inline' => true]
        ); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Cari', ['class' => 'btn btn-outline bg-olive btn-xs mr-2 my-2']) ?>
        <?= Html::a('Reset Hasil', ['index'], ['class' => 'btn btn-info btn-xs mr-2 my-2']) ?>
        <?= Html::resetButton('Reset Form', ['class' => 'btn btn-default btn-xs mr-2 my-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>