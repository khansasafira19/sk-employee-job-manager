<?php

use app\models\Dailypresencestatus;
use app\models\Pengguna;
use yii\helpers\Html;
// use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\switchinput\SwitchInput;
use kartik\time\TimePicker;

/** @var yii\web\View $this */
/** @var app\models\Dailyreport $model */
/** @var yii\widgets\ActiveForm $form */
?>
<div class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Rincian Butir Kegiatan</h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->errorSummary($model) ?>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo '<label class="control-label">Tanggal</label>';
                            echo DatePicker::widget([
                                'model' => $model,
                                'attribute' => 'tanggal',
                                'language'=>'id',
                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                'name' => 'tanggal',
                                'options' => [
                                    'placeholder' => 'Piih tanggal ...', 'value' => $model->isNewRecord ? date("Y-m-d") : $model->tanggal
                                ],
                                'pluginOptions' => [
                                    'todayHighlight' => true,
                                    'todayBtn' => true,
                                    'format' => 'yyyy-mm-dd',
                                    'autoclose' => true,
                                    'calendarWeeks' => true,
                                    // 'daysOfWeekDisabled' => [0, 6],
                                ]
                            ]);
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'pegawai')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(
                                    Pengguna::find()->select('*')
                                        ->where(['username' => $model->isNewRecord ? Yii::$app->user->identity->username : $model->pegawai])
                                        ->asArray()->all(),
                                    'username',
                                    function ($model) {
                                        return $model['gelar_depan'] . ' ' . $model['nama'] . ', ' . $model['gelar_belakang'];
                                    }
                                ),
                                'options' => ['placeholder' => 'Pilih Pegawai', 'value' => $model->isNewRecord ? Yii::$app->user->identity->username : $model->pegawai],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    // 'readonly' => true
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'status_presensi')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(
                                    Dailypresencestatus::find()->select('*')
                                        ->asArray()->all(),
                                    'id_dailypresencestatus',
                                    function ($model) {
                                        return $model['id_dailypresencestatus'] . ' ' . $model['keterangan_presensi'];
                                    }
                                ),
                                'options' => ['placeholder' => 'Pilih Status Presensi'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    // 'disabled' => true
                                ],
                            ]);
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?php // $form->field($model, 'jam_datang')->textInput() 
                            ?>
                            <?= $form->field($model, 'jam_datang')->widget(TimePicker::classname(), [
                                'pluginOptions' => [
                                    'showMeridian' => false,
                                    'defaultTime' => '07:00',
                                    'minuteStep' => 1,
                                ],
                                'addonOptions' => [
                                    'asButton' => true,
                                    'buttonOptions' => ['class' => 'btn btn-success']
                                ]
                            ]); ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'jam_pulang')->widget(TimePicker::classname(), [
                                'pluginOptions' => [
                                    'showMeridian' => false,
                                    'defaultTime' => ($model->isNewRecord) ? '' : '16:30',
                                    'minuteStep' => 1,
                                ],
                                'addonOptions' => [
                                    'asButton' => true,
                                    'buttonOptions' => ['class' => 'btn btn-success']
                                ]
                            ]); ?>
                        </div>
                    </div>

                    <?php //if (Yii::$app->user->identity->levelsuperadmin == true || Yii::$app->user->identity->leveladmintu == true) { ?>
                        <!-- <div class="d-flex align-items-center row"> -->
                            <!-- <div class="p-2"> -->
                                <?php //$form->field($model, 'is_setujuadmin')->widget(SwitchInput::classname(), [
                                //     'pluginOptions' => [
                                //         'onText' => 'SETUJU',
                                //         'offText' => 'TIDAK',
                                //     ],
                                // ]); ?>
                            <!-- </div> -->
                        <!-- </div> -->
                    <?php //} ?>

                    <div class=" text-right">
                        <?= Html::submitButton(Yii::t('app', 'Simpan'), ['class' => 'btn btn-outline-success btn-sm bundar']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>