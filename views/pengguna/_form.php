<?php

use app\models\Fungsi;
use app\models\Jabatan;
use app\models\Jenjang;
use app\models\Kabupaten;
use yii\helpers\Html;
use app\models\Kantor;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use app\models\Pangkatgol;
use app\models\Pengguna;
use app\models\Penggunaapprover;
use app\models\Penggunafungsi;
use app\models\Penggunajabatan;
use app\models\Penggunapangkatgol;
use app\models\Penggunasatker;
use app\models\Penggunasubfungsi;
use app\models\Provinsi;
use app\models\Subfungsi;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use kartik\checkbox\CheckboxX;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Pengguna */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$gol = 'Unknown';
$gol_id = 1;
if (isset($key) && $key >= 0) {
    //var_dump($profil[$key]['attributes']['attribute-jabatan'][0]);
    if (isset($profil[$key]['attributes']['attribute-golongan'][0])) {
        $jab = $profil[$key]['attributes']['attribute-golongan'][0];
        switch ($jab) {
            case 'II/a':
                $gol = 'Pengatur Muda/II-a';
                $gol_id = 1;
                break;
            case 'II/b':
                $gol = 'Pengatur Muda Tingkat I/II-b';
                $gol_id = 2;
                break;
            case 'II/c':
                $gol = 'Pengatur/II-c';
                $gol_id = 3;
                break;
            case 'II/d':
                $gol = 'Pengatur Tingkat I/II-d';
                $gol_id = 4;
                break;
            case 'III/a':
                $gol = 'Penata Muda/III-a';
                $gol_id = 5;
                break;
            case 'III/b':
                $gol = 'Penata Muda Tingkat I/III-b';
                $gol_id = 6;
                break;
            case 'III/c':
                $gol = 'Penata/III-c';
                $gol_id = 7;
                break;
            case 'III/d':
                $gol = 'Penata Tingkat I/III-d';
                $gol_id = 8;
                break;
            case 'IV/a':
                $gol = 'Pembina/IV-a';
                $gol_id = 9;
                break;
            case 'IV/b':
                $gol = 'Pembina Tingkat I/IV-b';
                $gol_id = 10;
                break;
            case 'IV/c':
                $gol = 'Pembina Utama Muda/IV-c';
                $gol_id = 11;
                break;
            case 'IV/d':
                $gol = 'Pembina Utama Madya/IV-d';
                $gol_id = 12;
                break;
            case 'IV/e':
                $gol = 'Pembina Utama/IV-e';
                $gol_id = 13;
                break;
            default:
                $gol = 'Unknown';
                $gol_id = 1;
                break;
        }
    }
    if (isset($profil[$key]['attributes']['attribute-nama'][0])) {
        $nama = $profil[$key]['attributes']['attribute-nama'][0];
        $gelardepan = '';
        $nama = $nama;
        $gelarbelakang = '';
        // if (str_contains($nama, 'SST'))
        //     echo 'ada SST';
        if (strpos($nama, '.') || str_contains($nama, 'SST')) {
            // $nama = substr($nama, strpos($nama, ".") + 2);
            $listgelardepan = ['Ir.', 'Drs.', 'Dr.'];
            foreach ($listgelardepan as $value) {
                // echo "$value <br>";
                if (str_contains($nama, $value)) {
                    $gelardepan = $value;
                    $nama = str_replace($value . ' ', '', $nama);
                    break;
                }
            }

            $listgelarbelakang = ['A.Md', 'A.Md.Kb.N', 'S.Ak', 'SST', 'S.Stat', 'S.Tr.Stat', 'S.Sos', 'S.Si', 'S.E', 'SE', 'S.H', 'SH', 'S.P', 'M.T', 'M.E', 'M.M', 'M.N', 'M.Si', 'M.Sc', 'M.Stat', 'M.H', 'M.Ec.Dev'];
            $convertgelarbelakang = [];
            foreach ($listgelarbelakang as $value) {
                // echo "$value <br>";
                if (str_contains($nama, $value)) {
                    // $gelarbelakang = $value;
                    array_push($convertgelarbelakang, $value);
                    $gelarbelakang =   implode(", ", $convertgelarbelakang);
                    $nama = str_replace($value, '', $nama);
                    // break;
                }
            }
            $nama = str_replace('.', '', $nama);
            $nama = str_replace(',', '', $nama);
        } else {
            $gelardepan = '';
            $nama = $nama;
            $gelarbelakang = '';
        }
    }

    if (isset($profil[$key]['attributes']['attribute-kabupaten'][0])) {
        $kantor = $profil[$key]['attributes']['attribute-kabupaten'][0];
        if (str_contains($kantor, 'Kab. '))
            $kantor = str_replace('Kab.', 'Kabupaten', $kantor);
        elseif (str_contains($kantor, 'Prov. '))
            $kantor = str_replace('Prov.', 'Provinsi', $kantor);
        $cari = Penggunasatker::find()
            ->select('*')
            ->where(['LIKE', 'nama_satker', '%' . $kantor . '%', false])
            ->one();
        if (isset($cari))
            $kantornya = $cari->id_satker;
        else
            $kantornya = '';
    }

    $jabatannya = '';
    if (isset($profil[$key]['attributes']['attribute-jabatan'][0])) {
        $jabatan = $profil[$key]['attributes']['attribute-jabatan'][0];
        $jabatannya = '';
        $case1 = 'Kepala BPS Propinsi';
        // if (trim($jabatan) == trim($case1) || trim($jabatan) == 'Kepala BPS Kabupaten/Kota')
        if (str_contains($jabatan, 'Kepala BPS'))
            $jabatannya = 1;
        elseif (str_contains($jabatan, 'Kepala') && str_contains($jabatan, 'Umum'))
            $jabatannya = 2;
        else
            $jabatannya = '';
    }
}
?>
<div class="wrapper">
    <?php if (Yii::$app->session->hasFlash('success')) : ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Disimpan!</h4>
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-6">
            <?php if (Yii::$app->controller->action->id == 'create') : ?>
                <div class="alert alert-success">

                    <!--<div class="col-md-8" style="padding-left: 0px!important">-->
                    <?php

                    $formnip = ActiveForm::begin(['id' => 'EmailForm', 'type' => ActiveForm::TYPE_INLINE, 'fieldConfig' => ['options' => ['class' => 'form-group mr-2']]]);
                    //$fldConfig = ['options' => ['class' => 'form-group mt-2 mb-2 mr-2']];
                    echo Form::widget([
                        'model' => $modelusername,
                        'form' => $formnip,
                        'attributes' => [
                            'email' => ['type' => Form::INPUT_TEXT, ['placeholder' => 'email@bps.go.id'], ['style' => 'width:100px']],
                            //'password' => ['type' => Form::INPUT_PASSWORD, 'options' => ['placeholder' => 'Enter password...'], 'fieldConfig' => $fldConfig],
                            //'rememberMe' => ['type' => Form::INPUT_CHECKBOX],
                            'actions' => ['type' => Form::INPUT_RAW, 'value' => Html::submitButton('Ambil Data', ['class' => 'btn btn-secondary ml-2'])]
                        ]
                    ]);
                    //echo Html::button('Submit', ['type'=>'button', 'class'=>'btn btn-success']);
                    ActiveForm::end();
                    ?>

                    <!--</div>-->
                </div>
            <?php endif; ?>
            <?php if ($ada == 'YA') : ?>
                <div class="alert alert-secondary alert-dismissible">
                    <p>
                        Pegawai dengan email tersebut | <b><?= Html::a($namasat->nama, ['view', 'id' => $namasat->username]) ?> | </b> sudah masuk ke dalam sistem.
                    </p>
                </div>
            <?php elseif ($ada == '') : ?>
                <div class="alert alert-success alert-dismissable">
                    <p>
                        Silahkan masukkan alamat email BPS pengguna yang akan ditambahkan.
                    </p>
                </div>
            <?php elseif ($bengkulu == 'TIDAK') : ?>
                <div class="alert alert-secondary alert-dismissable">
                    <p>
                        Mohon maaf, pegawai yang dapat ditambahkan ke sistem SK-EJM hanya pegawai yang pada Community BPS tercatat di Provinsi Bengkulu.
                    </p>
                </div>
            <?php elseif ($ada == 'TIDAK') : ?>
                <div class="wrapper">
                    <?php $form = ActiveForm::begin([
                        'enableClientValidation' => true,
                        'options' => [
                            'name' => 'Form'
                        ]
                    ]); ?>
                    <?= $form->errorSummary($model) ?>
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Identitas</h3>
                        </div>
                        <div class="card-body">
                            <?php //echo $this->context->action->id
                            ?>

                            <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'style' => 'text-transform: lowercase', 'readonly' => true, 'value' => $model->isNewRecord ? $profil[$key]['username'] : $model->username]) ?>
                            <?php if ($model->isNewRecord) { ?>
                                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                                <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true, 'value' => $model->password]) ?>
                            <?php } ?>

                            <?= $form->field($model, 'nip')->textInput(['readonly' => true, 'value' => $model->isNewRecord ? $profil[$key]['attributes']['attribute-nip'][0] : $model->nip]) ?>
                            <?= $form->field($model, 'gelar_depan')->textInput(['maxlength' => true, 'value' => $model->isNewRecord ? $gelardepan : $model->gelar_depan]) ?>
                            <?= $form->field($model, 'nama')->textInput(['maxlength' => true, 'value' => $model->isNewRecord ? $nama : $model->nama]) ?>
                            <?= $form->field($model, 'gelar_belakang')->textInput(['maxlength' => true, 'value' => $model->isNewRecord ?  $gelarbelakang : $model->gelar_belakang]) ?>
                            <?=
                            $form->field($model, 'satker')->dropDownList(ArrayHelper::map(
                                Penggunasatker::find()->orderBy('id_satker')->all(),
                                'id_satker',
                                function ($model) {
                                    return $model->nama_satker;
                                }
                            ), ['prompt' => 'Pilih Satker', 'options' => [
                                $model->isNewRecord ? $kantornya : $model->satker => ['selected' => true],
                            ]])
                            ?>

                            <?php if (Yii::$app->user->identity->levelsuperadmin == true) { ?>
                                <?=
                                $form->field($model, 'fungsi_pengguna')->dropDownList(ArrayHelper::map(Penggunafungsi::find()
                                    ->orderBy('id_fungsi')
                                    ->all(), 'id_fungsi', function ($model) {
                                    return $model->nama_fungsi;
                                }), ['id' => 'fungsipengguna-id', 'prompt' => 'Pilih Fungsi'])
                                ?>

                                <?=
                                $form->field($model, 'subfungsi_pengguna')->widget(DepDrop::classname(), [
                                    'options' => ['id' => 'subfungsipengguna-id',],
                                    'pluginOptions' => [
                                        'depends' => ['fungsipengguna-id'],
                                        'placeholder' => Yii::t('app', 'Sub Fungsi...'),
                                        'url' => Yii::$app->request->hostInfo . '/pacakplus/' . Yii::$app->controller->id . '/tampilsubfungsi',
                                    ]
                                ]);
                                ?>
                            <?php } elseif (Yii::$app->user->identity->leveladmin == true && Yii::$app->user->identity->satker == 1700) { ?>
                                <?=
                                $form->field($model, 'fungsi_pengguna')->dropDownList(ArrayHelper::map(Penggunafungsi::find()
                                    ->where(['id_fungsi' => Yii::$app->user->identity->fungsi_pengguna])
                                    ->orderBy('id_fungsi')
                                    ->all(), 'id_fungsi', function ($model) {
                                    return $model->nama_fungsi;
                                }), ['prompt' => 'Pilih Fungsi'])
                                ?>

                                <?=
                                $form->field($model, 'subfungsi_pengguna')->dropDownList(ArrayHelper::map(Penggunasubfungsi::find()
                                    ->where(['id_fungsi' => Yii::$app->user->identity->fungsi_pengguna])
                                    ->orderBy('id_subfungsi')
                                    ->all(), 'id_subfungsi', function ($model) {
                                    return $model->nama_subfungsi;
                                }), ['prompt' => 'Pilih Sub Fungsi'])
                                ?>
                            <?php } else { ?>
                                <?=
                                $form->field($model, 'fungsi_pengguna')->dropDownList(ArrayHelper::map(Penggunafungsi::find()
                                    ->orderBy('id_fungsi')
                                    ->all(), 'id_fungsi', function ($model) {
                                    return $model->nama_fungsi;
                                }), ['prompt' => 'Pilih Fungsi'])
                                ?>
                            <?php } ?>
                            <?php // $form->field($model, 'is_ckp_approver')->checkbox(); 
                            ?>
                            <?= $form->field($model, 'approved_ckp_by')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(
                                    Penggunaapprover::find()->select('*')
                                        ->joinWith(['penggunae'])
                                        ->where(['penggunaapprover.satker' => $model->isNewRecord ? $kantornya : $model->satker])
                                        ->andWhere(['autentikasi' => 1])
                                        ->orderBy(['pengguna.pangkatgol' => SORT_DESC])->asArray()->all(),
                                    'id_approver',
                                    function ($model) {
                                        return $model['gelar_depan'] . ' ' . $model['nama'] . ', ' . $model['gelar_belakang'];
                                    }
                                ),
                                'options' => ['placeholder' => 'Pilih Penanggung Jawab CKP'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                            <?php if ($jabatannya == '' && $model->isNewRecord) { ?>
                                <?= $form->field($model, 'jabatan')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(
                                        Penggunajabatan::find()->where('id_jabatan <> 1')->andWhere('id_jabatan <> 2')->orderBy('id_jabatan')->all(),
                                        'id_jabatan',
                                        function ($model) {
                                            return $model['nama_jabatan'];
                                        }
                                    ),
                                    'options' => ['placeholder' => 'Pilih Jabatan'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                                ?>
                            <?php } else { ?>
                                <?=
                                $form->field($model, 'jabatan')->dropDownList(ArrayHelper::map(
                                    Penggunajabatan::find()->orderBy('id_jabatan')->all(),
                                    'id_jabatan',
                                    function ($model) {
                                        return $model->nama_jabatan;
                                    }
                                ), ['prompt' => 'Pilih Jabatan', 'readonly' => true, 'options' => [
                                    $jabatannya => ['selected' => true],
                                ]])
                                ?>
                            <?php } ?>

                            <?php
                            // $form->field($model, 'jabatan')->dropDownList(ArrayHelper::map(Penggunajabatan::find()->orderBy('id_jabatan')->all(), 'id_jabatan', function ($model) {
                            //     return $model->nama_jabatan;
                            // }), ['prompt' => 'Pilih Jabatan...',])
                            ?>
                            <?=
                            $form->field($model, 'pangkatgol')->dropDownList(ArrayHelper::map(Penggunapangkatgol::find()->orderBy('id_pangkatgol')->all(), 'id_pangkatgol', function ($model) {
                                return $model->nama_pangkatgol;
                            }), ['prompt' => 'Pilih Pangkat/Golongan...', 'options' => [$gol_id => ['selected' => 'selected']]])
                            ?>

                            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly' => true, 'value' => $model->isNewRecord ? $profil[$key]['email'] : $model->email]) ?>

                            <?php // '<label class="control-label">Upload Foto Pegawai </label>'; 
                            ?>
                            <?php
                            // $form->field($model, 'filefoto')->widget(FileInput::classname(), [
                            //     'pluginOptions' => [
                            //         'showPreview' => false,
                            //         'showUpload' => false,
                            //         'browseClass' => 'btn btn-success btn-block',
                            //         'browseIcon' => '<i class="glyphicon glyphicon-save-file"></i> ',
                            //         'browseLabel' => 'Pilih Foto'
                            //     ]
                            // ]);
                            ?>
                            <?=
                            $form->field($model, 'filefoto')
                                ->widget(\fv\yii\croppie\Widget::class, [
                                    'rotateCcwLabel' => '<i class="fas fa-undo-alt"></i>',
                                    'rotateCwLabel' => '<i class="fas fa-redo-alt"></i>',
                                    'format' => 'jpg',
                                    'clientOptions' => [
                                        // 'enableOrientation'=>false
                                    ]
                                ])
                            ?>

                            <?php if ($model->isNewRecord) { ?>
                                <?= 'Dapat diunduh di: <a href="' . $profil[$key]['attributes']['attribute-foto'][0] . '" target="_blank">Foto Community (Via VPN)</a>'; ?>
                            <?php } ?>
                        </div>
                        <div class="card-footer text-right">
                            <?= Html::submitButton('Simpan', ['class' => 'btn btn-success checkBtn']) ?>
                        </div>
                    </div>
                </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php endif; ?>
</div>