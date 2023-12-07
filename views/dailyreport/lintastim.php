<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Pangkatgol;
use kartik\form\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DailyreportCari $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Request Pekerjaaan Lintas Tim';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wrapper">
    <?php if (Yii::$app->session->hasFlash('success')) : ?>
        <div class="alert alert-primary alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Berhasil!</h4>
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->session->hasFlash('warning')) : ?>
        <div class="alert alert-warning alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Hai!</h4>
            <?= Yii::$app->session->getFlash('warning') ?>
        </div>
    <?php endif; ?>

    <?php
    $kolomTampil = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'owner',
            'label' => 'Pengirim Request',
            'format' => 'raw',
            'value' => function ($data) {
                return Html::a($data->ownere->gelar_depan . $data->ownere->nama . ', ' . $data->ownere->gelar_belakang, Yii::$app->request->baseUrl . '/pengguna/view?id=' . $data['owner'], [
                    'title' => 'Lihat Pengusul Kegiatan Ini', 'class' => 'modalButton', 'data-pjax' => 0
                ]);
            }
        ],
        [
            'attribute' => 'assigned_to',
            'label' => 'Pegawai yang Ditugasi',
            'format' => 'raw',
            'value' => function ($data) {
                if ($data['assigned_to'] == NULL)
                    return '-';
                else {
                    return Html::a($data->assignedtoe->gelar_depan . $data->assignedtoe->nama . ', ' . $data->assignedtoe->gelar_belakang, Yii::$app->request->baseUrl . '/pengguna/view?id=' . $data['assigned_to'], [
                        'title' => 'Lihat Delegasi Kegiatan Ini', 'class' => 'modalButton', 'data-pjax' => 0
                    ]);
                }
            }
        ],
        [
            // 'attribute' => 'timkerjaproject',
            'label' => 'Tim Kerja',
            'value' => function ($data) {
                if ($data['timkerjaproject'] == NULL)
                    return '-';
                else {
                    return '#' . $data->timkerjae->nama_timkerja;
                }
            },
        ],
        [
            'attribute' => 'timkerjaproject',
            'label' => 'Project',
            'value' => function ($data) {
                if ($data['timkerjaproject'] == NULL)
                    return '-';
                else {
                    return '#' . $data->timkerjaprojecte->project_name;
                }
            },
        ],
        [
            'attribute' => 'rincian_report',
            'label' => 'Rincian Kegiatan',
        ],
        [
            'attribute' => 'tanggal_kerja',
            'label' => 'Tanggal Kerja',
            'value' => function ($data) {
                $fmt = new \IntlDateFormatter('id_ID', null, null);
                $fmt->setPattern('d MMMM yyyy');
                return $fmt->format(strtotime($data->tanggal_kerja));
                // return date('d F Y', strtotime($data->tanggal_kerja));
            },
            'hAlign' => 'center'
        ],
        [
            'attribute' => 'is_izinlintastim',
            'label' => 'Status Izin',
            'value' => function ($data) {
                if ($data['is_izinlintastim'] === 1)
                    return '<center><i class="fas fa-check"></i></center>';
                elseif ($data['is_izinlintastim'] === 0)
                    return '<center><i class="fas fa-times"></i></center>';
                else
                    return '<center><i class="fas fa-exclamation-circle"></i></center>';
            },
            'format' => 'html',
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => 'Setujui/Tolak',
            'template' => '{izinlintastim}',
            'contentOptions' => ['class' => 'text-center'],
            'buttons' => [
                'izinlintastim' => function ($url, $model, $key) {
                    if ($model->is_izinlintastim == 1) {
                        return Html::a('<i class="fa text-danger">&#xf0ab;</i>', 'izinlintastim?izin=0&id=' . $key, [
                            'data-method' => 'post',
                            'data-pjax' => 0,
                            'data-confirm' => 'Anda yakin ingin menolak request ini? <br/>Dari <strong>'
                                . $model->ownere->nama . '</strong> tugas kepada <strong>' . $model->assignedtoe->nama . '</strong>'
                        ]);
                    } else {
                        return Html::a('<i class="fa text-success">&#xf0aa;</i>', 'izinlintastim?izin=1&id=' . $key, [
                            'data-method' => 'post',
                            'data-pjax' => 0,
                            'data-confirm' => 'Anda yakin ingin menyetujui request ini? <br/>Dari <strong>'
                                . $model->ownere->nama . '</strong> tugas kepada <strong>' . $model->assignedtoe->nama . '</strong>'
                        ]);
                    }
                },
            ],
        ],
    ];
    $kolomTampilAnda = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'assigned_to',
            'label' => 'Pegawai yang Ditugasi',
            'format' => 'raw',
            'value' => function ($data) {
                if ($data['assigned_to'] == NULL)
                    return '-';
                else {
                    return Html::a($data->assignedtoe->gelar_depan . $data->assignedtoe->nama . ', ' . $data->assignedtoe->gelar_belakang, Yii::$app->request->baseUrl . '/pengguna/view?id=' . $data['assigned_to'], [
                        'title' => 'Lihat Delegasi Kegiatan Ini', 'class' => 'modalButton', 'data-pjax' => 0
                    ]);
                }
            }
        ],
        [
            // 'attribute' => 'timkerjaproject',
            'label' => 'Tim Kerja',
            'value' => function ($data) {
                if ($data['timkerjaproject'] == NULL)
                    return '-';
                else {
                    return '#' . $data->timkerjae->nama_timkerja;
                }
            },
        ],
        [
            'attribute' => 'timkerjaproject',
            'label' => 'Project',
            'value' => function ($data) {
                if ($data['timkerjaproject'] == NULL)
                    return '-';
                else {
                    return '#' . $data->timkerjaprojecte->project_name;
                }
            },
        ],
        [
            'attribute' => 'rincian_report',
            'label' => 'Rincian Kegiatan',
        ],
        [
            'attribute' => 'tanggal_kerja',
            'label' => 'Tanggal Kerja',
            'value' => function ($data) {
                $fmt = new \IntlDateFormatter('id_ID', null, null);
                $fmt->setPattern('d MMMM yyyy');
                return $fmt->format(strtotime($data->tanggal_kerja));
                // return date('d F Y', strtotime($data->tanggal_kerja));
            },
            'hAlign' => 'center'
        ],
        [
            'attribute' => 'is_izinlintastim',
            'label' => 'Status Izin',
            'value' => function ($data) {
                if ($data['is_izinlintastim'] === 1)
                    return '<center><i class="fas fa-check"></i></center>';
                elseif ($data['is_izinlintastim'] === 0)
                    return '<center><i class="fas fa-times"></i></center>';
                else
                    return '<center><i class="fas fa-exclamation-circle"></i></center>';
            },
            'format' => 'html',
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => 'Batalkan Request',
            'template' => '{batalrequest}',
            'contentOptions' => ['class' => 'text-center'],
            'buttons' => [
                'batalrequest' => function ($url, $model, $key) {
                    if ($model->is_izinlintastim == 0 || $model->is_izinlintastim == NULL ) {
                        return Html::a('<i class="fa text-danger">&#xf714;</i>', 'batalrequest?id=' . $key, [
                            'data-method' => 'post',
                            'data-pjax' => 0,
                            'data-confirm' => 'Anda yakin ingin membatalkan request ini? <br/>Dari <strong>'
                                . $model->ownere->nama . '</strong> tugas kepada <strong>' . $model->assignedtoe->nama . '</strong>'
                        ]);
                    }
                },
            ],
        ],
    ];
    ?>
    <?php
    $layout = '
        <div class="card-header bg-light text-dark">
            <div class="d-flex justify-content-between" style="margin-bottom: -0.8rem; margin-top:-0.5rem">
                <div class="p-2">
                                        
                </div>
                <div class="p-2" style="margin-top:0.5rem;">
                {summary}{pager}                    
                </div>
                <div class="p-2">                    
                    {toolbar}
                </div>
            </div>                            
        </div>  
        {items}
        ';
    ?>

    <div class="row">
        <div class="col-12">
            <div class="card card-info">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Laporan Presensi Harian</h3>
                    </div>
                </div>
                <div class="card card-info card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Request Tim Lain</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Request Anda</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Arsip</a>
                            </li> -->
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-three-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                <?php Pjax::begin(['id' => 'some_pjax_id']); ?>

                                <?= GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    // 'filterModel' => false,
                                    'columns' => $kolomTampil,
                                    'bordered' => true,
                                    'striped' => true,
                                    'condensed' => true,
                                    'hover' => true,
                                    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                                    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                                    'export' => [
                                        'fontAwesome' => true,
                                        'label' => '<i class="fa">&#xf56d;</i>',
                                    ],
                                    'exportConfig' => [
                                        GridView::CSV => ['label' => 'CSV', 'filename' => 'Laporan Harian ' . ' -' . date('d-M-Y')],
                                        GridView::HTML => ['label' => 'HTML', 'filename' => 'Laporan Harian ' . '-' . date('d-M-Y')],
                                        GridView::EXCEL => ['label' => 'EXCEL', 'filename' => 'Laporan Harian ' . '-' . date('d-M-Y')],
                                        GridView::TEXT => ['label' => 'TEXT', 'filename' => 'Laporan Harian ' . '-' . date('d-M-Y')],
                                    ],
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                        'options' => ['id' => 'some_pjax_id'],
                                    ],
                                    'pager' => [
                                        'firstPageLabel' => '<i class="fas fa-angle-double-left"></i>',
                                        'lastPageLabel' => '<i class="fas fa-angle-double-right"></i>',
                                        'prevPageLabel' => '<i class="fas fa-angle-left"></i>',   // Set the label for the "previous" page button
                                        'nextPageLabel' => '<i class="fas fa-angle-right"></i>',
                                        'maxButtonCount' => 1,
                                    ],
                                    'toggleDataOptions' => ['minCount' => 10],
                                    'floatOverflowContainer' => true,
                                    'floatHeader' => true,
                                    'floatHeaderOptions' => [
                                        'scrollingTop' => '0',
                                        'position' => 'absolute',
                                        'top' => 50
                                    ],
                                    'layout' => $layout,

                                ]); ?>
                                <?php Pjax::end() ?>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                                <?php Pjax::begin(['id' => 'some_pjax_id']); ?>
                                <?= GridView::widget([
                                    'dataProvider' => $dataProviderAnda,
                                    // 'filterModel' => false,
                                    'columns' => $kolomTampilAnda,
                                    'bordered' => true,
                                    'striped' => true,
                                    'condensed' => true,
                                    'hover' => true,
                                    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                                    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                                    'export' => [
                                        'fontAwesome' => true,
                                        'label' => '<i class="fa">&#xf56d;</i>',
                                    ],
                                    'exportConfig' => [
                                        GridView::CSV => ['label' => 'CSV', 'filename' => 'Pegawai ' . ' -' . date('d-M-Y')],
                                        GridView::HTML => ['label' => 'HTML', 'filename' => 'Pegawai ' . '-' . date('d-M-Y')],
                                        GridView::EXCEL => ['label' => 'EXCEL', 'filename' => 'Pegawai ' . '-' . date('d-M-Y')],
                                        GridView::TEXT => ['label' => 'TEXT', 'filename' => 'Pegawai ' . '-' . date('d-M-Y')],
                                    ],
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                        'options' => ['id' => 'some_pjax_id'],
                                    ],
                                    'pager' => [
                                        'firstPageLabel' => '<i class="fas fa-angle-double-left"></i>',
                                        'lastPageLabel' => '<i class="fas fa-angle-double-right"></i>',
                                        'prevPageLabel' => '<i class="fas fa-angle-left"></i>',   // Set the label for the "previous" page button
                                        'nextPageLabel' => '<i class="fas fa-angle-right"></i>',
                                        'maxButtonCount' => 1,
                                    ],
                                    'toggleDataOptions' => ['minCount' => 10],
                                    'floatOverflowContainer' => true,
                                    'floatHeader' => true,
                                    'floatHeaderOptions' => [
                                        'scrollingTop' => '0',
                                        'position' => 'absolute',
                                        'top' => 50
                                    ],
                                    'layout' => $layout,
                                ]); ?>
                                <?php Pjax::end() ?>
                            </div>
                            <!-- <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
                            </div> -->

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <p>Sistem menampilkan request lintas tim untuk Anda dan dari Anda sebagai Ketua Tim.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
Modal::begin([
    'title' => 'Rincian Kegiatan Harian Pegawai',
    'id' => 'modal',
    'size' => 'modal-lg'
]);

echo '<div id="modalContent"></div>';

Modal::end();
?>
<?php
Modal::begin([
    'title' => 'Rincian Butir CKP Pegawai',
    'id' => 'modalCkp',
    'size' => 'modal-lg'
]);

echo '<div id="modalContentCkp"></div>';

Modal::end();
?>
<script>
    $(function() {
        // changed id to class
        $('.modalButton').click(function() {
            $.get($(this).attr('href'), function(data) {
                $('#modal').modal('show').find('#modalContent').html(data)
            });
            return false;
        });
    });
</script>
<script>
    $(function() {
        // changed id to class
        $('.modalButtonCkp').click(function() {
            $.get($(this).attr('href'), function(data) {
                $('#modalCkp').modal('show').find('#modalContentCkp').html(data)
            });
            return false;
        });
    });
</script>
<script>
    //Cek apakah checkbox ada yang dicheck minimal satu
    function isDisabled() {
        var cbCount = $('.kv-row-checkbox').filter(':checked').length // check if checked
        $("#submitButton").prop("disabled", cbCount < 1);
    }

    $(function() {
        isDisabled(); // run it for the first time
        $('.kv-row-checkbox').on("change", isDisabled); // bind checkbox

    });
    // init_click_handlers(); //first run
    // $("#some_pjax_id").on("pjax:success", function() {
    //     init_click_handlers(); //reactivate links in grid after pjax update
    // });

    // Post ke action Bulkdiajukan jika submit
    function submit() {
        var dialog = confirm("Anda yakin akan menyetujui daftar laporan kegiatan tersebut?");
        if (dialog == true) {
            // var keys = $('input[name="checked"]').yiiGridView('getSelectedRows');
            var keys = $('#tabel-hari-ini').yiiGridView('getSelectedRows');
            var ajax = new XMLHttpRequest();

            $.ajax({
                type: "POST",
                url: 'bulkapprove', // Your controller action
                data: {
                    keylist: keys
                },
                success: function(result) {
                    console.log(result);
                }
            });
        }
    }
</script>