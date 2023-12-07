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

$this->title = 'Approval Pekerjaan Harian';
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
            'class' => '\kartik\grid\CheckboxColumn',
            'checkboxOptions' => function ($model) {
                if ($model->is_setujuketuatim == NULL || $model->is_setujuketuatim == 0) {
                    return [];
                } else {
                    return ['disabled' => true];
                }
            },
            'rowSelectedClass' => GridView::TYPE_INFO,
        ],
        // [
        //     'attribute' => 'id_ckp',
        //     'label' => 'Butir CKP',
        //     'format' => 'raw',
        //     'enableSorting' => false,
        //     'value' => function ($data) {
        //         if ($data->id_ckp != NULL) {
        //             return Html::a("<center><i class='fa'>&#xf35d;</i></center>", Yii::$app->request->baseUrl . '/ckp/view?id_butirckp=' . $data['id_ckp'], [
        //                 'title' => 'Lihat detail butir CKP ini', 'class' => 'modalButtonCkp', 'data-pjax' => 0
        //             ]);
        //         } else
        //             return '';
        //     }
        // ],
        // [
        //     'attribute' => 'owner',
        //     'label' => 'Pengusul',
        //     'format' => 'raw',
        //     'value' => function ($data) {
        //         return Html::a($data->ownere->gelar_depan . $data->ownere->nama . ', ' . $data->ownere->gelar_belakang, Yii::$app->request->baseUrl . '/pengguna/view?id=' . $data['owner'], [
        //             'title' => 'Lihat Pengusul Kegiatan Ini', 'class' => 'modalButton', 'data-pjax' => 0
        //         ]);
        //     }
        // ],
        [
            'attribute' => 'assigned_to',
            'label' => 'Delegasi',
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
            'label' => 'Tanggal',
            'value' => function ($data) {
                $fmt = new \IntlDateFormatter('id_ID', null, null);
                $fmt->setPattern('d MMMM yyyy');
                return $fmt->format(strtotime($data->tanggal_kerja));
                // return date('d F Y', strtotime($data->tanggal_kerja));
            },
            'hAlign' => 'center'
        ],
        
        [
            'attribute' => 'is_setujuketuatim',
            // 'class' => 'kartik\grid\BooleanColumn',
            'label' => 'Approval',
            // 'trueLabel' => 'Ya',
            // 'falseLabel' => 'Belum',
            // 'trueIcon' => '<center><i class="fas fa-check"></i></center>',
            // 'falseIcon' => '<center><i class="fas fa-times"></i></center>',
            'value' => function ($data) {
                if ($data['is_setujuketuatim'] === 1)
                    return '<center><i class="fas fa-check"></i></center>';
                elseif ($data['is_setujuketuatim'] === 0)
                    return '<center><i class="fas fa-times"></i></center>';
                else
                    return '<center><i class="fas fa-exclamation-circle"></i></center>';
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'status_selesai',
            'class' => 'kartik\grid\BooleanColumn',
            'label' => 'Completion Status',
            'trueLabel' => 'Selesai',
            'falseLabel' => 'Belum',
            'trueIcon' => '<center><i class="fas fa-check"></i></center>',
            'falseIcon' => '<center><i class="fas fa-times"></i></center>',
            'format' => 'html',
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => 'Detail',
            'template' => '{view}',
            'contentOptions' => ['class' => 'text-center'],
            'buttons'  => [
                'view' => function ($key, $client) {
                    //$url = 'update/'.$key;
                    return Html::a('<i class="fa">&#xf06e;</i>', $key, ['title' => 'Lihat rincian kegiatan ini', 'class' => 'modalButton', 'data-pjax' => '0']);
                    //return Html::a('<button class="btn btn-sm tombol-biru"><i class="fa text-info">&#xf06e;</i></button>', $key, ['title' => 'Lihat rincian logbook ini', 'class' => 'modalButton', 'data-pjax' => '0']);
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
            <div class="card">
                <div class="card-header">
                    
                    <?php Pjax::begin(['id' => 'some_pjax_id']); ?>
                    <div class="d-flex flex-row-reverse">
                    <div class="p-2">
                            <button id="submitButtonTolak" type="button" onclick="submitTolak()" class="btn btn-danger pull-right mb-2 bundar btn-sm">Tolak Laporan</button>
                       
                            <button id="submitButton" type="button" onclick="submit()" class="btn btn-info pull-right mb-2 bundar btn-sm">Approve Laporan</button>
                        </div>
                    </div>
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
                <div class="card-footer text-right">
                        <p>Menampilkan list pekerjaan yang telah diselesaikan oleh pegawai di tim Anda dan belum Anda approve.</p>
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
        $("#submitButtonTolak").prop("disabled", cbCount < 1);
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
    function submitTolak() {
        var dialog = confirm("Anda yakin akan menolak daftar laporan kegiatan tersebut?");
        if (dialog == true) {
            // var keys = $('input[name="checked"]').yiiGridView('getSelectedRows');
            var keys = $('#tabel-hari-ini').yiiGridView('getSelectedRows');
            var ajax = new XMLHttpRequest();

            $.ajax({
                type: "POST",
                url: 'bulkreject', // Your controller action
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