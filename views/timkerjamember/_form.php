<?php

use app\models\Pengguna;
use app\models\Penggunasatker;
use app\models\Timkerja;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\models\Timkerjamember */
/* @var $form yii\widgets\ActiveForm */
?>
<script type="text/javascript">
    // $(document).ready(function() {
    //     $('#checkBtn').click(function() {
    //         checked = $("input[type=checkbox]:checked").length;

    //         if (!checked) {
    //             alert("Pilih minimal salah satu keterkaitan fenomena.");
    //             return false;
    //         }

    //     });
    // });
    // $(document).ready(function() {
    //     $('#checkBtn').click(function() {
    //         var card = document.getElementById("satker-id");
    //         if (card.selectedIndex == 0) {
    //             alert('select one answer');

    //         } else {
    //             var selectedText = card.options[card.selectedIndex].text;
    //             alert(selectedText);
    //         }

    //     });
    // });
    // $(document).ready(function() {
    $("#satker-id").change(function() {
        $('input[type="submit"]').removeAttr('disabled');
    });
    // });
</script>

<div class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Rincian Tim Kerja</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <p>Terdapat <?php echo $jumlahtim ?> Tim Kerja pada Satker <?php echo Yii::$app->user->identity->kantor ?>.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <?php echo Html::hiddenInput('selected_id', $model->isNewRecord ? date("Y") : $model->timkerjae->tahun, ['id' => 'selected_id']); ?>

                    <?= $form->field($model, 'tahune')->dropDownList(
                        [
                            date("Y") - 1 => date("Y") - 1,
                            date("Y") => date("Y"),
                            date("Y") + 1 => date("Y") + 1
                        ],
                        [
                            'id' => 'tahun-id',
                            'options' => [$model->isNewRecord ? date("Y") : $model->timkerjae->tahun => ['selected' => 'selected']],
                            // 'onchange' => 'javascript:$("#mydiv").toggle()'
                        ]
                    )->label('Tahun') ?>

                    <?php if (YIi::$app->user->identity->levelsuperadmin == true) : ?>
                        <?=
                        $form->field($model, 'satkere')->widget(DepDrop::classname(), [
                            'options' => ['id' => 'satker-id'],
                            'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                            'pluginOptions' => [
                                'depends' => ['tahun-id'],
                                'placeholder' => Yii::t('app', 'Pilih Satker'),
                                'url' => Yii::$app->request->hostInfo . '/sk-employee-job-management/' . Yii::$app->controller->id . '/tampilsatker?new='
                                    . ($model->isNewRecord ? "yes" : "no") . '&val='
                                    . ($model->isNewRecord ? "" : $model->timkerjae->satker . "-" . $model->timkerjae->tahun),
                                'params' => ['selected_id'],
                                'initialize' => true,
                                //'url' => Url::to(['pengguna/tampiltimkerja'])
                            ]
                        ])->label($model->isNewRecord ? 'Satuan Kerja' : 'Satuan Kerja [<i>Data Saat Ini: ' . $model->penggunasatkere->nama_satker . '</i>]');
                        ?>
                        <?=
                        $form->field($model, 'timkerja')->widget(DepDrop::classname(), [
                            'options' => ['id' => 'timkerja-id',],
                            'type' => DepDrop::TYPE_SELECT2,
                            'pluginOptions' => [
                                'depends' => ['satker-id'],
                                'placeholder' => Yii::t('app', 'Pilih Tim Kerja'),
                                'url' => Yii::$app->request->hostInfo . '/sk-employee-job-management/' . Yii::$app->controller->id . '/tampiltimkerja?new=yes&val=',
                                //'url' => Url::to(['pengguna/tampiltimkerja'])
                            ]
                        ])->label($model->isNewRecord ? 'Tim Kerja' : 'Tim Kerja [<i>Data Saat Ini: ' . $model->timkerjae->nama_timkerja . '</i>]');
                        ?>
                        <?=
                        $form->field($model, 'anggota')->widget(DepDrop::classname(), [
                            'options' => ['id' => 'anggota-id',],
                            'type' => DepDrop::TYPE_SELECT2,
                            'pluginOptions' => [
                                'depends' => ['satker-id'],
                                'placeholder' => Yii::t('app', 'Pilih Pegawai'),
                                'url' => Yii::$app->request->hostInfo . '/sk-employee-job-management/' . Yii::$app->controller->id . '/tampilanggota',
                                //'url' => Url::to(['pengguna/tampiltimkerja'])
                            ]
                        ])->label($model->isNewRecord ? 'Anggota' : 'Anggota [<i>Data Saat Ini: ' . $model->penggunae->nama . '</i>]');
                        ?>


                    <?php else : ?>
                        <?=
                        $form->field($model, 'timkerja')->widget(DepDrop::classname(), [
                            'options' => ['id' => 'timkerja-id',],
                            'pluginOptions' => [
                                'depends' => ['tahun-id'],
                                'placeholder' => Yii::t('app', 'Pilih Tim Kerja'),
                                'url' => Yii::$app->request->hostInfo . '/sk-employee-job-management/' . Yii::$app->controller->id . '/tampiltimkerja?new='
                                . ($model->isNewRecord ? "yes" : "no") . '&val='
                                . ($model->isNewRecord ? "" : $model->timkerja),
                                'allowClear' => true,
                                'params' => ['tahun-id'],
                                'initialize' => true,
                                //'url' => Url::to(['pengguna/tampiltimkerja'])
                            ]
                        ])->label($model->isNewRecord ? 'Tim Kerja' : 'Tim Kerja [<i>Data Saat Ini: ' . $model->timkerjae->nama_timkerja . '</i>]');
                        ?>
                        <?= $form->field($model, 'anggota')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                Pengguna::find()->select('*')->where(['satker' => Yii::$app->user->identity->satker])->orderBy(['pangkatgol' => SORT_DESC])->asArray()->all(),
                                'username',
                                function ($model) {
                                    return $model['gelar_depan'] . ' ' . $model['nama'] . ', ' . $model['gelar_belakang'];
                                }
                            ),
                            'options' => ['placeholder' => 'Pilih Anggota'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>

                    <?php endif; ?>

                    <?php // $form->field($model, 'anggota')->textInput(['maxlength' => true]) 
                    ?>

                    <?php
                    // $form->field($model, 'anggota')->widget(Select2::classname(), [
                    //     'data' => ArrayHelper::map(
                    //         Pengguna::find()->select('*')->where(['satker' => Yii::$app->user->identity->satker])->orderBy(['pangkatgol' => SORT_DESC])->asArray()->all(),
                    //         'username',
                    //         function ($model) {
                    //             return $model['gelar_depan'] . ' ' . $model['nama'] . ', ' . $model['gelar_belakang'];
                    //         }
                    //     ),
                    //     'options' => ['placeholder' => 'Pilih Anggota'],
                    //     'pluginOptions' => [
                    //         'allowClear' => true
                    //     ],
                    // ]);
                    ?>

                    <?= $form->field($model, 'is_ketua')->checkbox(); ?>

                    <div class="card-footer">
                        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success', 'id' => 'checkBtn', /*'disabled' => true*/]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>