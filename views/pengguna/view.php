<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\models\Levelpengguna;

/* @var $this yii\web\View */
/* @var $model app\models\Pengguna */

$this->title = 'Pengguna SK-EJM';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="wrapper">
    <?php if (Yii::$app->session->hasFlash('success')) : ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Disimpan!</h4>
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php

    function getImg($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    function konversi_nip($nip, $batas = " ")
    {
        $nip = trim($nip, " ");
        $panjang = strlen($nip);

        if ($panjang == 18) {
            $sub[] = substr($nip, 0, 8); // tanggal lahir
            $sub[] = substr($nip, 8, 6); // tanggal pengangkatan
            $sub[] = substr($nip, 14, 1); // jenis kelamin
            $sub[] = substr($nip, 15, 3); // nomor urut

            return $sub[0] . $batas . $sub[1] . $batas . $sub[2] . $batas . $sub[3];
        } else {
            return $nip;
        }
    } ?>
    <div class="row">
        <div class="col-12">
            <div class="card card-info">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title"></h3>
                    </div>
                </div>
                <div class="card card-info card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-three-beranda-tab" data-toggle="pill" href="#custom-tabs-three-beranda" role="tab" aria-controls="custom-tabs-three-beranda" aria-selected="true">Akun</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-profil-tab" data-toggle="pill" href="#custom-tabs-three-profil" role="tab" aria-controls="custom-tabs-three-profil" aria-selected="false">Profil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-pekerjaan-tab" data-toggle="pill" href="#custom-tabs-three-pekerjaan" role="tab" aria-controls="custom-tabs-three-pekerjaan" aria-selected="false">Pekerjaan</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-three-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-three-beranda" role="tabpanel" aria-labelledby="custom-tabs-three-beranda-tab">
                                <?=
                                DetailView::widget([
                                    'model' => $model,
                                    'condensed' => true,
                                    'hover' => true,
                                    // 'mode' => DetailView::MODE_VIEW,
                                    'panel' => [
                                        'heading' =>  $model->nama . ' | Akun',
                                        'type' => DetailView::TYPE_SUCCESS,
                                    ],
                                    'hAlign' => 'left',
                                    'buttons1' => '',
                                    'attributes' => [
                                        [
                                            'attribute' => 'foto',
                                            'value' => function ($data) {
                                                $external_link = Yii::$app->request->hostInfo . '/sk-employee-job-management/images/foto_pegawai/' . $data->foto;
                                                if (@getimagesize($external_link)) {
                                                    return Html::img(
                                                        \Yii::$app->request->baseUrl . '/images/foto_pegawai/' . $data->foto,
                                                        [
                                                            'width' => '80px',
                                                            'class' => 'img-circle'
                                                            //  'height' => '80px'
                                                        ]
                                                    );
                                                } else {
                                                    return Html::img(
                                                        \Yii::$app->request->baseUrl . '/images/user.png',
                                                        [
                                                            'width' => '80px',
                                                            'class' => 'img-circle'
                                                            //  'height' => '80px'
                                                        ]
                                                    );
                                                }
                                            },
                                            'value' => @getimagesize(Yii::$app->request->hostInfo . '/sk-employee-job-management/images/foto_pegawai/' . $model->foto) ? \Yii::$app->request->baseUrl . '/images/foto_pegawai/' . $model->foto : \Yii::$app->request->baseUrl . '/images/user.png',
                                            'format' => ['image', ['height' => '200', 'class' => 'img-circle']],
                                        ],
                                        'username',
                                        [
                                            'attribute' => 'password',
                                            'value' => '******',
                                        ],
                                        [
                                            'label' => 'Levels',
                                            'value' => $levels,
                                            'format' => 'html'
                                        ],
                                        [
                                            'label' => 'Menilai CKP Pegawai',
                                            'value' => $ckps,
                                            'format' => 'html'
                                        ],
                                        [
                                            'attribute' => 'approved_ckp_by',
                                            'value' => $model->approved_ckp_by != 0 ?  $model->namapenggunaapprovere->gelar_depan . $model->namapenggunaapprovere->nama . ', ' . $model->namapenggunaapprovere->gelar_belakang : '-',
                                            // 'format' => 'raw'
                                        ],
                                        [
                                            'attribute' => 'tgl_daftar',
                                            'value' => \Yii::$app->formatter->asDatetime(strtotime($model->tgl_daftar), "d MMMM y 'pada' H:mm a"),
                                        ],

                                        [
                                            'attribute' => 'status_pengguna',
                                            'value' => (($model->status_pengguna == '') ? '<i class="fa">&#xf00d;</i> [NON AKTIF]' : '<i class="fa">&#xf00c;</i> [AKTIF]'),
                                            'format' => 'raw'
                                        ],
                                    ],
                                ])
                                ?>
                                <div class="d-flex flex-row-reverse">
                                    <?php if (
                                        //superadmin edit data siapa saja
                                        Yii::$app->user->identity->levelsuperadmin == true
                                        ||
                                        //admin daerah edit data provinsinya saja
                                        (Yii::$app->user->identity->leveladmin == true && $model['fungsi_pengguna'] == Yii::$app->user->identity->fungsi_pengguna)
                                    ) : ?>
                                        <div class="p-2">
                                            <?= Html::a('Update', ['update', 'id' => $model->username], ['class' => 'btn btn-outline-success bundar btn-sm']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (
                                        //superadmin tidak bs hapus datanya sendiri
                                        (Yii::$app->user->identity->username != $model['username'] && (Yii::$app->user->identity->levelsuperadmin == true)
                                            ||
                                            //admin daerah tidak bs hapus datanya sendiri tapi boleh hapus data di provinsinya saja
                                            (Yii::$app->user->identity->leveladmin == true && $model['fungsi_pengguna'] == Yii::$app->user->identity->fungsi_pengguna && Yii::$app->user->identity->username != $model['username']))
                                    ) : ?>
                                        <div class="p-2">
                                            <?=
                                            Html::a('Non Aktifkan', ['delete', 'id' => $model->username], [
                                                'class' => 'btn btn-outline-warning bundar btn-sm',
                                                'data' => [
                                                    'confirm' => 'Anda yakin ingin menonaktifkan pengguna ini?<br/><strong>' . $model->nama . '</strong>',
                                                    'method' => 'post',
                                                ],
                                            ])
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="p-2">
                                        <?= Html::a('Ganti Password', ['ubahpassword', 'id' => $model->username], ['class' => 'btn btn-outline-info bundar btn-sm']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-three-profil" role="tabpanel" aria-labelledby="custom-tabs-three-profil-tab">
                                <?=
                                DetailView::widget([
                                    'model' => $model,
                                    'condensed' => true,
                                    'hover' => true,
                                    // 'mode' => DetailView::MODE_VIEW,
                                    'panel' => [
                                        'heading' =>  $model->nama . ' | Profil',
                                        'type' => DetailView::TYPE_SUCCESS,
                                    ],
                                    'hAlign' => 'left',
                                    'buttons1' => '',
                                    'attributes' => [
                                        [
                                            'attribute' => 'gelar_depan',
                                            'value' => $model->gelar_depan != NULL ? $model->gelar_depan : '-'
                                        ],
                                        [
                                            'attribute' => 'nama',
                                            'value' => $model->nama,
                                        ],

                                        [
                                            'attribute' => 'gelar_belakang',
                                            'value' => $model->gelar_belakang,
                                        ],
                                        'email',
                                        [
                                            'attribute' => 'nip',
                                            'value' => konversi_nip($model->nip)
                                        ],
                                        [
                                            'attribute' => 'satker',
                                            'value' => 'BPS ' . $model->satkere->nama_satker,
                                        ],
                                        [
                                            'attribute' => 'fungsi_pengguna',
                                            'value' => $model->fungsie->nama_fungsi,
                                        ],
                                        [
                                            'attribute' => 'subfungsi_pengguna',
                                            'value' => $model->subfungsi_pengguna == NULL ? '-' : $model->subfungsie->nama_subfungsi,
                                        ],
                                        [
                                            'attribute' => 'pangkatgol',
                                            'value' => $model->pangkatgole->nama_pangkatgol,
                                        ],
                                        [
                                            'attribute' => 'jabatan',
                                            'value' => $model->jabatane->nama_jabatan,
                                        ],

                                    ],
                                ])
                                ?>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-three-pekerjaan" role="tabpanel" aria-labelledby="custom-tabs-three-pekerjaan-tab">
                                <?=
                                DetailView::widget([
                                    'model' => $model,
                                    'condensed' => true,
                                    'hover' => true,
                                    // 'mode' => DetailView::MODE_VIEW,
                                    'panel' => [
                                        'heading' =>  $model->nama . ' | Pengalaman',
                                        'type' => DetailView::TYPE_SUCCESS,
                                    ],
                                    'hAlign' => 'left',
                                    'buttons1' => '',
                                    'attributes' => [
                                        [
                                            'label' => 'Tim Kerja',
                                            'value' => '<strong>' . $model->jumlahtim . '</strong> tim kerja.',
                                            'format' => 'raw'
                                        ],
                                        [
                                            'label' => 'Project',
                                            'value' => '<strong>' . $model->jumlahproject . '</strong> project.',
                                            'format' => 'raw'
                                        ],
                                        [
                                            'label' => 'Tugas',
                                            'value' => '<strong>' . $model->jumlahtugas . '</strong> tugas.',
                                            'format' => 'raw'
                                        ],
                                    ],
                                ])
                                ?>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <p>Silahkan laporkan kepada Admin jika terdapat ketidaksesuaian <br />dengan data di BPS Community atau data terbaru Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>