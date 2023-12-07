<?php

use app\models\Jabatan;
use app\models\Jenjang;
use app\models\Kabupaten;
use yii\helpers\Html;
use app\models\Kantor;
use app\models\Level;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use app\models\Pangkatgol;
use app\models\Provinsi;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model app\models\Pengguna */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Pendaftaran Level Pegawai';
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="wrapper">


    <div class="wrapper">
        <?php $form = ActiveForm::begin([
            'enableClientValidation' => true,
            'options' => [
                'onsubmit' => "return IsEmpty();",
                'name' => 'Form'
            ]
        ]); ?>

        <?= $form->errorSummary($model) ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Level Pegawai</h3>
                    </div>
                    <div class="card-body">

                        <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'value' => $usernamepengguna, 'readonly' => true]) ?>

                        <?php echo 'a.n. ', $namapengguna . '<hr/>'; ?>

                        <?php // $form->field($model, 'level')->textInput() 
                        ?>

                        <?php if (Yii::$app->user->identity->levelsuperadmin === true) : ?>
                            <?=
                            $form->field($model, 'level')->dropDownList(ArrayHelper::map(Level::find()
                                ->andWhere(['<>', 'id_level', '0'])
                                ->andWhere(['<>', 'id_level', '5'])
                                ->orderBy('id_level')
                                ->all(), 'id_level', function ($model) {
                                return $model->nama_level;
                            }), ['prompt' => 'Pilih Level',])
                            ?>
                        <?php else : ?>
                            <?=
                            $form->field($model, 'level')->dropDownList(ArrayHelper::map(Level::find()
                                ->where(['id_level' =>1])                                
                                ->all(), 'id_level', function ($model) {
                                return $model->nama_level;
                            }), ['prompt' => 'Pilih Level',])
                            ?>
                        <?php endif; ?>

                        <?php // $form->field($model, 'autentikasi')->textInput() 
                        ?>

                    </div>
                    <div class="card-footer">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary checkBtn']) ?>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>