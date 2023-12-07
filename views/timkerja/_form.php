<?php

use app\models\Penggunasatker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Timkerja */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wrapper">
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-success">
                <div class="card-header">
                    <!-- <h3 class="card-title">Rincian Tim Kerja</h3> -->
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <?php if ($model->isNewRecord)
                                echo Html::a('Import Excel Data Tim Kerja', ['import'], ['class' => 'btn btn-default text-success bundar']) ?>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <?php if (Yii::$app->controller->action->id == 'update') : ?>
                        <?= $form->field($model, 'tahun')->textInput(['disabled' => true]) ?>
                    <?php else : ?>
                        <?= $form->field($model, 'tahun')->textInput(['value' => date("Y")]) ?>
                    <?php endif; ?>

                    <?php if (YIi::$app->user->identity->levelsuperadmin == true) : ?>
                        <?php if (Yii::$app->controller->action->id == 'update') : ?>
                            <?=
                            $form->field($model, 'satker')->dropDownList(ArrayHelper::map(Penggunasatker::find()->orderBy('id_satker')->all(), 'id_satker', function ($model) {
                                return $model->id_satker . ' ' . $model->nama_satker;
                            }), ['id' => 'satker-id', 'prompt' => 'Pilih Satker', 'options' => [$model->satker => ['selected' => 'selected']], 'disabled' => true])
                            ?>
                        <?php else : ?>
                            <?=
                            $form->field($model, 'satker')->dropDownList(ArrayHelper::map(Penggunasatker::find()->orderBy('id_satker')->all(), 'id_satker', function ($model) {
                                return $model->id_satker . ' ' . $model->nama_satker;
                            }), ['id' => 'satker-id', 'prompt' => 'Pilih Satker', 'options' => [Yii::$app->user->identity->satker => ['selected' => 'selected']]])
                            ?>
                        <?php endif; ?>
                    <?php else : ?>
                        <?= $form->field($model, 'satker')->hiddenInput(['value' => Yii::$app->user->identity->satker]) ?>

                    <?php endif; ?>

                    <?= $form->field($model, 'nama_timkerja')->textInput(['maxlength' => true]) ?>


                    <div class="card-footer">
                        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
                    </div>
                    <br />
                    <p style="text-align: right;"><i>Data Tim Kerja harus diperbaharui setiap tahunnya.</i></p>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>