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

$this->title = 'Rekap Keanggotan Tim Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    /* .table-danger:hover {
        background-color: #FCFDED;
    } */

    .dark-mode .table-striped>tbody>tr:nth-of-type(even) {
        background-color: #343a40 !important;
    }

    /* .dark-mode table>tbody>tr>td.kv-row-select, */
    .dark-mode .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: #3a4047 !important;
    }
</style>

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
            'attribute' => 'nama',
            'format' => 'html',
            'value' => function ($data) {
                $external_link = Yii::$app->request->hostInfo . '/sk-employee-job-management/images/foto_pegawai/' . $data->foto;
                if (@getimagesize($external_link)) {
                    return Html::img(
                        \Yii::$app->request->baseUrl . '/images/foto_pegawai/' . $data->foto,
                        [
                            'width' => '40px',
                            'class' => 'img-circle'
                            //  'height' => '80px'
                        ]
                    ) . '&nbsp;' . $data->gelar_depan . '&nbsp;' . $data->nama . '&nbsp;' . $data->gelar_belakang;
                } else {
                    return Html::img(
                        \Yii::$app->request->baseUrl . '/images/user.png',
                        [
                            'width' => '40px',
                            'class' => 'img-circle'
                            //  'height' => '80px'
                        ]
                    ) . '&nbsp;' . $data->gelar_depan . '&nbsp;' . $data->nama . '&nbsp;' . $data->gelar_belakang;
                }
            },
            'mergeHeader' => true,
            'label' => 'Pegawai'
        ],
        [
            'value' => function ($data) {
                if ($data->jumlahtim > 0)
                    // return '<i class="fas fa-border-none"></i> ' . $data->jumlahtim;
                    return Html::a('<center><i class="fas fa-border-none"></i> ' . $data->jumlahtim . ' tim</center>', Yii::$app->request->baseUrl . '/timkerjamember/rekapmemberviewtim?id=' . $data['username'], [
                        'title' => 'Lihat list tim pegawai ini', 'class' => 'modalButton', 'data-pjax' => 0
                    ]);
                else
                    return '-';
            },
            'label' => 'Tim',
            'format' => 'html',
            'hAlign' => 'center',
        ],
        [
            'value' => function ($data) {
                if ($data->jumlahproject > 0)
                    // return '<i class="fas fa-border-none"></i> ' . $data->jumlahtim;
                    return Html::a('<center><i class="fas fa-paperclip"></i> ' . $data->jumlahproject . ' projects</center>', Yii::$app->request->baseUrl . '/timkerjamember/rekapmemberviewtim?id=' . $data['username'], [
                        'title' => 'Lihat list projects pegawai ini', 'class' => 'modalButton', 'data-pjax' => 0
                    ]);
                else
                    return '-';
            },
            'label' => 'Projects',
            'format' => 'html',
            'hAlign' => 'center',
        ],
        // [
        //     'value' => function ($data) {
        //         if ($data->jumlahproject > 0)
        //             return '<i class="fas fa-paperclip"></i> ' . $data->jumlahproject;
        //         else
        //             return '-';
        //     },
        //     'label' => 'Projects',
        //     'format' => 'html',
        //     'hAlign' => 'center',
        // ],
        [
            'value' => function ($data) {
                if ($data->jumlahtugas > 0)
                    return '<i class="far fa-copy"></i> ' . $data->jumlahtugas .' tugas harian';
                else
                    return '-';
            },
            // 'value' => function ($data) {
            //     if ($data->jumlahtugas > 0)
            //         // return '<i class="fas fa-border-none"></i> ' . $data->jumlahtim;
            //         return Html::a('<center><i class="fas fa-border-none"></i> ' . $data->jumlahtugas . '</center>', Yii::$app->request->baseUrl . '/rekapmemberviewtugas?id=' . $data['username'], [
            //             'title' => 'Lihat list tugas pegawai ini', 'class' => 'modalButtonTugas', 'data-pjax' => 0
            //         ]);
            //     else
            //         return '-';
            // },
            'label' => 'Tugas Harian',
            'format' => 'html',
            'hAlign' => 'center',
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
            <div class="card">
                <div class="card-header">
                    <?php Pjax::begin(['id' => 'some_pjax_id']); ?>
                </div>
                <div class="card-body table-responsive p-0">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        // 'filterModel' => false,                      
                        'columns' => $kolomTampil,
                        'id' => 'tabel-hari-ini',
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
            </div>
        </div>
    </div>
</div>
<?php
Modal::begin([
    'title' => 'Rincian Data Keanggotaan Tim',
    'id' => 'modal',
    'size' => 'modal-lg'
]);

echo '<div id="modalContent"></div>';

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