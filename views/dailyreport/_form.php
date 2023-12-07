<?php

use app\models\Eommaster;
use app\models\Pengguna;
use app\models\Timkerjamember;
use app\models\Timkerjaproject;
use yii\helpers\Html;
// use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use kartik\builder\Form;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use kartik\switchinput\SwitchInput;

/** @var yii\web\View $this */
/** @var app\models\Dailyreport $model */
/** @var yii\widgets\ActiveForm $form */
?>
<?php
//cek apakah dia ketua
$listtimketua = Timkerjamember::find()
    ->select('*')
    ->joinWith('timkerjae')
    ->where('anggota = "' . Yii::$app->user->identity->username . '"')
    ->andWhere('is_ketua = 1')
    ->andWhere('tahun = "' . date("Y") . '"')
    ->asArray()->all();

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
                        <div class="col-md-3">
                            <?= $form->field($model, 'owner')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(
                                    Pengguna::find()->select('*')
                                        ->where(['username' => $model->isNewRecord ? Yii::$app->user->identity->username : $model->owner])
                                        ->asArray()->all(),
                                    'username',
                                    function ($model) {
                                        return $model['gelar_depan'] . ' ' . $model['nama'] . ', ' . $model['gelar_belakang'];
                                    }
                                ),
                                'options' => ['placeholder' => 'Pilih Pegawai', 'value' => $model->isNewRecord ? Yii::$app->user->identity->username : $model->owner],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'readonly' => true
                                ],
                            ]);
                            ?>
                        </div>
                        <?php if (count($listtimketua) > 0) { //kalau ketua tim 
                        ?>
                            <div class="col-md-1">
                                <?= $form->field($model, 'lintas_tim')->checkbox(['onclick' => 'Lintastim()'])->label("YA");
                                ?>
                            </div>
                        <?php } ?>

                        <div class="col-md-4">
                            <?php
                            $listimkerja = Timkerjamember::find()
                                ->select('timkerja')
                                ->joinWith('timkerjae')
                                ->where('anggota = "' . Yii::$app->user->identity->username . '"')
                                ->andWhere('is_member = 1')
                                ->andWhere('tahun = "' . date("Y") . '"')
                                ->asArray()->all();
                            $items = [];
                            foreach ($listimkerja as $value) {
                                array_push($items, $value['timkerja']);
                            }
                            $listtimtrim = trim(json_encode($items), '[]');
                            ?>
                            <?= $form->field($model, 'timkerjaproject')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(
                                    Timkerjaproject::find()->select('*')
                                        ->joinWith(['timkerjae'])
                                        ->where(Yii::$app->user->identity->levelsuperadmin == true ? ['not', ['satker' => null]] : ['satker' => Yii::$app->user->identity->satker])
                                        ->andWhere('timkerja IN ' . str_replace($listtimtrim, "($listtimtrim)", $listtimtrim))
                                        ->asArray()->all(),
                                    'id_project',
                                    function ($model) {
                                        return  '#' . $model['project_name'] . ' (' . $model['nama_timkerja'] . ')';
                                    }
                                ),

                                'options' => ['placeholder' => 'Pilih Project'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ], ['id' => 'timkerjaproject-id']);
                            ?>
                        </div>

                        <div class="col-md-4">

                            <?php if (count($listtimketua) > 0) { //kalau ketua tim 
                            ?>
                                <div id="nonlintastim">

                                    <?=
                                    $form->field($model, 'assigned_to')->widget(DepDrop::classname(), [
                                        'options' => ['id' => 'assignedto-id',],
                                        'pluginOptions' => [
                                            'depends' => ['dailyreport-timkerjaproject'],
                                            'placeholder' => Yii::t('app', 'Delegasi...'),
                                            'url' => Yii::$app->request->hostInfo . '/sk-employee-job-management/' . Yii::$app->controller->id . '/tampildelegasi',
                                        ]
                                    ]);
                                    ?>

                                </div>

                                <div id="lintastim" style="display:none">
                                    <?php
                                    $itemsketua = [];
                                    foreach ($listtimketua as $value) {
                                        array_push($itemsketua, $value['timkerja']);
                                    }
                                    $listtimtrimketua = trim(json_encode($itemsketua), '[]');
                                    $listimmember = Timkerjamember::find()
                                        ->select('*')
                                        ->joinWith('timkerjae')
                                        ->andWhere('timkerja IN ' . str_replace($listtimtrimketua, "($listtimtrimketua)", $listtimtrimketua))
                                        ->andWhere('is_member = 1')
                                        ->andWhere('tahun = "' . date("Y") . '"')
                                        ->asArray()->all();

                                    $itemsmember = [];
                                    foreach ($listimmember as $value) {
                                        array_push($itemsmember, $value['anggota']);
                                    }
                                    $listtimtrimmember = trim(json_encode($itemsmember), '[]');
                                    ?>
                                    <?=
                                    $form->field($model, 'assigned_to')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(
                                            Pengguna::find()->select('*')
                                                ->where(Yii::$app->user->identity->levelsuperadmin == true ? ['not', ['satker' => null]] : ['satker' => Yii::$app->user->identity->satker])
                                                ->andWhere(['not', ['username' => $model->isNewRecord ? Yii::$app->user->identity->username : $model->owner]]) //tidak bisa delegasi ke diri sendiri
                                                ->andWhere('username IN ' . str_replace($listtimtrimmember, "($listtimtrimmember)", $listtimtrimmember))
                                                ->asArray()->all(),
                                            'username',
                                            function ($model) {
                                                return $model['gelar_depan'] . ' ' . $model['nama'] . ', ' . $model['gelar_belakang'];
                                            }
                                        ),
                                        'options' => ['placeholder' => 'Pilih Pegawai'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                    ]);
                                    ?>

                                    <div class="d-flex align-items-center row">
                                        <div class="p-2">
                                            <?= $form->field($model, 'is_izinlintastim')->widget(SwitchInput::classname(), [
                                                'pluginOptions' => [
                                                    'onText' => 'IZINKAN',
                                                    'offText' => 'TOLAK',
                                                ],
                                            ]); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'rincian_report')->textarea(['rows' => 3]) ?>

                    <div class="d-flex align-items-center row">
                        <div class="p-2">
                            <?= $form->field($model, 'status_selesai')->widget(SwitchInput::classname(), [
                                'pluginOptions' => [
                                    'onText' => 'SELESAI',
                                    'offText' => 'BELUM',
                                ],
                            ]); ?>
                        </div>
                        <div class="p-2">
                            <?= $form->field($model, 'priority')->widget(SwitchInput::classname(), [
                                'pluginOptions' => [
                                    'onText' => '<i class="fas fa-star"></i>',
                                    'offText' => '-',
                                ],
                            ]); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo '<label class="control-label">Tanggal Kerja</label>';
                            echo DatePicker::widget([
                                'model' => $model,
                                'attribute' => 'tanggal_kerja',
                                'language' => 'id',
                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                'name' => 'tanggal_kerja',
                                'options' => [
                                    'placeholder' => 'Piih tanggal ...', 'value' => $model->isNewRecord ? date("Y-m-d") : $model->tanggal_kerja
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
                            
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'ket')->textInput() ?>
                        </div>
                    </div>

                    <div class=" text-right">
                        <?= Html::submitButton(Yii::t('app', 'Simpan'), ['class' => 'btn btn-outline-success btn-sm bundar']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function Lintastim() {
        var checkBox = document.getElementById("dailyreport-lintas_tim");
        var text = document.getElementById("lintastim");
        var textnon = document.getElementById("nonlintastim");
        var textizin = document.getElementById("izinlintastim");
        if (checkBox.checked == true) {
            text.style.display = "block";
            textnon.style.display = "none";
            textizin.style.display = "block";
        } else {
            text.style.display = "none";
            textnon.style.display = "block";
            textizin.style.display = "none";
        }
    }
</script>