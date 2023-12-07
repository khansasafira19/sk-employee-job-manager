<?php

use app\models\Dailypresence;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
// use yii\grid\GridView;
use kartik\grid\GridView;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DailypresenceCari $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Presensi Harian';
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

    <div class="d-flex flex-row-reverse">
        <?php if (Yii::$app->user->identity->leveladmintu == true || Yii::$app->user->identity->levelsuperadmin == true) : ?>
            <div class="p-2">
                <?= Html::a('<i class="fas fa-check text-info"></i> Approve Presensi', ['approval'], ['class' => 'btn btn-outline-info bundar btn-sm']) ?>
            </div>
        <?php endif; ?>
        <div class="p-2">
            <?= Html::a('<i class="fas fa-plus text-info"></i> Tambah Presensi', ['create?from=dailypresence&date=' . date("Y-m-d")], ['class' => 'btn btn-outline-info bundar btn-sm']) ?>
        </div>
    </div>

    <?php
    $kolomTampil = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'tanggal',
            'label' => 'Tanggal',
            'value' => function ($data) {
                $fmt = new \IntlDateFormatter('id_ID', null, null);
                $fmt->setPattern('d MMMM yyyy');
                return $fmt->format(strtotime($data->tanggal));
                // return date('d F Y', strtotime($data->tanggal_kerja));
            },
            'hAlign' => 'center'
        ],
        // [
        //     'attribute' => 'pegawai',
        //     'label' => 'Pegawai',
        //     'format' => 'raw',
        //     'value' => function ($data) {
        //         return Html::a($data->penggunae->gelar_depan . $data->penggunae->nama . ', ' . $data->penggunae->gelar_belakang, Yii::$app->request->baseUrl . '/pengguna/view?id=' . $data['pegawai'], [
        //             'title' => 'Lihat Pegawai Ini', 'class' => 'modalButtonCkp', 'data-pjax' => 0
        //         ]);
        //     }
        // ],
        // 'jam_datang',
        [
            'attribute' => 'jam_datang',
            'label' => 'Jam Datang',
            'value' => function ($data) {
                if ($data['jam_datang'] == NULL)
                    return '-';
                else
                    return date('H:i', strtotime($data->jam_datang)) . " WIB";
            },
            'hAlign' => 'center'
        ],
        // 'jam_pulang',
        [
            'attribute' => 'jam_pulang',
            'label' => 'Jam Pulang',
            'value' => function ($data) {
                if ($data['jam_pulang'] == NULL)
                    return '-';
                else
                    return date('H:i', strtotime($data->jam_pulang)) . " WIB";
            },
            'hAlign' => 'center'
        ],
        [
            'attribute' => 'status_presensi',
            'label' => 'Status Presensi',
            'format' => 'raw',
            'value' => function ($data) {
                if ($data['status_presensi'] == NULL)
                    return '-';
                else {
                    return $data->dailypresencestatuse->keterangan_presensi;
                }
            }
        ],
        [
            'attribute' => 'is_setujuadmin',
            'label' => 'Disetujui Admin',
            'value' => function ($data) {
                if ($data['is_setujuadmin'] === 1)
                    return '<center><i class="fas fa-check"></i></center>';
                elseif ($data['is_setujuadmin'] === 0)
                    return '<center><i class="fas fa-times"></i></center>';
                else
                    return '<center>-</center>';
            },
            'format' => 'html',
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => 'Detail',
            'template' => '{view}&nbsp;&nbsp;&nbsp;{update}&nbsp;&nbsp;&nbsp;{delete}',
            'contentOptions' => ['class' => 'text-center'],
            'visibleButtons' => [
                'update' => function ($model, $key, $index) {
                    return ((Yii::$app->user->identity->username == $model['pegawai']
                        || Yii::$app->user->identity->levelsuperadmin == true) && $model->is_setujuadmin != 1)
                        ? true : false;
                },
                'delete' => function ($model, $key, $index) {
                    return ((Yii::$app->user->identity->username == $model['pegawai']
                        || Yii::$app->user->identity->levelsuperadmin == true) && $model->is_setujuadmin != 1)
                        ? true : false;
                },
            ],
            'buttons'  => [
                'view' => function ($key, $client) {
                    //$url = 'update/'.$key;
                    return Html::a('<i class="fa">&#xf06e;</i>', $key, ['title' => 'Lihat rincian presensi ini', 'class' => 'modalButton', 'data-pjax' => '0']);
                    //return Html::a('<button class="btn btn-sm tombol-biru"><i class="fa text-info">&#xf06e;</i></button>', $key, ['title' => 'Lihat rincian logbook ini', 'class' => 'modalButton', 'data-pjax' => '0']);
                },
                'update' => function ($url, $model, $key) {
                    $url = 'update/?from=dailypresence&id=' . $key;
                    return Html::a('<i class="fas fa-pencil-alt"></i>', $url, ['title' => 'Update rincian presensi ini', 'class' => 'modalButton', 'data-pjax' => '0']);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-trash-alt"></i>', $url, [
                        'data-method' => 'post',
                        'data-pjax' => 0,
                        'data-confirm' => 'Anda yakin ingin menghapus laporan ini? <br/><strong>' . $model['penggunae']['nama'] . '</strong> pada <strong>' . date('d F Y', strtotime($model['tanggal']))  . '</strong>'
                    ]);
                },
            ],
        ],
    ];
    ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
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
                                <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Bulan Ini</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Arsip</a>
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
                                    'dataProvider' => $dataProviderSelesai,
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
                        <p>Semangat mengirimkan laporan presensi Anda setiap hari, ya!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
Modal::begin([
    'title' => 'Rincian Presensi Harian Pegawai',
    'id' => 'modal',
    'size' => 'modal-lg'
]);

echo '<div id="modalContent"></div>';

Modal::end();
?>
<?php
Modal::begin([
    'title' => 'Rincian Data Pegawai',
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