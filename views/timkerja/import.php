<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;

$this->title = 'Import Data Tim Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Tim Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="wrapper">
    <div class="container-fluid row">

        <div class="col-md-6">

            <p>
                <a href="<?= Yii::$app->homeUrl ?>library/excel/_template_timkerja.xlsx" style="text-decoration:none" class="btn btn-outline-success bundar">
                    <b>TEMPLATE UPLOAD</b>
                    <br />
                </a>
                <br />
            </p>

            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'mataanggaran',
                        'type' => ActiveForm::TYPE_VERTICAL,
                        'options' => ['encType' => 'multipart/form-data']
                    ]);

                    echo $form->errorSummary($model);

                    echo Form::widget([
                        'model' => $model,
                        'form' => $form,
                        'columns' => 2,
                        'attributes' => [
                            'importFile' => ['type' => Form::INPUT_FILE, 'options' => ['placeholder' => 'File']],
                        ]
                    ])
                    ?>
                    <div class="form-group text-right">
                        <?= Html::submitButton('Upload', ['class' => 'btn btn-success bundar']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>