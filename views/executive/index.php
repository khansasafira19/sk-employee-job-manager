<?php

$this->title = 'Ringkasan Eksekutif Pimpinan';

use app\models\Timkerjaproject;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
// use yii\grid\GridView;
use kartik\grid\GridView;
use yii\bootstrap4\Modal;
use yii\jui\ProgressBar;
use yii\widgets\Pjax;

?>
<style>
    .table-striped>thead>tr>th,
    .table-striped>thead>tr>th>a,
    tbody>tr>td>a {
        color: white !important;
    }
</style>

<div class="wrapper">
    <div class="d-flex flex-row-reverse">
        <!-- <div class="p-2"><?php // Html::a('<i class="fas fa-download"></i> Unduh PDF', ['createpdf'], ['class' => 'btn btn-outline-success bundar btn-sm']) 
                                ?></div> -->
        <div class="p-2"><?= Html::a('<i class="fas fa-print"></i> Cetak PDF', ['createpdf'], ['class' => 'btn btn-outline-success bundar btn-sm']) ?></div>
    </div>
    <div class="row">

        <div class="col-lg-10">
            <div class="card card-info">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Ringkasan Project Tim Kerja</h3>
                    </div>
                </div>
                <?php
                $kolomTampil = [
                    ['class' => 'yii\grid\SerialColumn'],

                    // 'project_name',
                    [
                        'attribute' => 'project_name',
                        'label' => 'Nama Project',
                        'value' => function ($data) {
                            return '#' . $data->project_name;
                        },
                    ],
                    // 'timkerja',
                    [
                        'attribute' => 'timkerja',
                        'value' => function ($data) {
                            return $data->timkerjae->nama_timkerja;
                        },
                        'label' => 'Tim Kerja'
                    ],
                    [
                        'attribute' => 'start_date',
                        'label' => 'Tanggal Dimulai',
                        'value' => function ($data) {
                            $fmt = new \IntlDateFormatter('id_ID', null, null);
                            $fmt->setPattern('d MMMM yyyy');
                            return $fmt->format(strtotime($data->start_date));
                            // return date('d F Y', strtotime($data->start_date));
                        },
                        'hAlign' => 'center'
                    ],
                    [
                        'attribute' => 'finish_date',
                        'label' => 'Tanggal Berakhir',
                        'value' => function ($data) {
                            $fmt = new \IntlDateFormatter('id_ID', null, null);
                            $fmt->setPattern('d MMMM yyyy');
                            return $fmt->format(strtotime($data->finish_date));
                        },
                        'hAlign' => 'center'
                    ],
                    // 'start_date',
                    // 'finish_date',
                    [
                        'value' => function ($data) {
                            // return $data['dailyreporte'][0]['tanggal_kerja'];
                            return number_format((float)$data->persentase, 2, '.', '') . '%';
                        },
                        'label' => 'Progress',
                        // 'content' => function ($model) {
                        //     return ProgressBar::widget([
                        //         'clientOptions' => [
                        //             // 'value' => $model->getDownloadingStatus(),
                        //             'value' => 50
                        //         ],
                        //     ]);
                        // },
                        'content' => function ($model) {
                            if ($model->persentase <= 0) {
                                return '-';
                            } else {
                                return '<div class="progress">
                                        <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: ' . $model->persentase . '%">'
                                    . number_format((float)$model->persentase, 2, '.', '') . '%
                                        </div>
                                    </div>';
                            }
                        },
                        'headerOptions' => ['style' => 'width:20%'],
                        'hAlign' => 'center'
                    ],
                ];
                ?>
                <div class="card card-info card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Sedang Berjalan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Direncanakan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Selesai</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-three-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                <?=
                                GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    // 'filterModel' => false,
                                    // 'filterUrl' => Yii::$app->request->hostInfo . Yii::$app->request->url,
                                    'columns' => $kolomTampil,
                                    'layout' => '{items}{pager}<span style="text-align:right">{summary}</span>',
                                    'showPageSummary' => false,
                                    'pjax' => true,
                                    'bordered' => true,
                                    'striped' => true,
                                    'condensed' => true,
                                    'hover' => true,
                                    'responsive' => true,
                                    // 'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                                    'export' => false,
                                    'panel' => ['type' => 'default',],
                                ]);
                                ?>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                                <?=
                                GridView::widget([
                                    'dataProvider' => $dataProviderFuture,
                                    // 'filterModel' => false,
                                    // 'filterUrl' => Yii::$app->request->hostInfo . Yii::$app->request->url,
                                    'columns' => $kolomTampil,
                                    'layout' => '{items}{pager}<span style="text-align:right">{summary}</span>',
                                    'showPageSummary' => false,
                                    'pjax' => true,
                                    'bordered' => true,
                                    'striped' => true,
                                    'condensed' => true,
                                    'hover' => true,
                                    'responsive' => true,
                                    // 'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                                    'export' => false,
                                    'panel' => ['type' => 'default',],
                                ]);
                                ?>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
                                <?=
                                GridView::widget([
                                    'dataProvider' => $dataProviderFinished,
                                    // 'filterModel' => false,
                                    // 'filterUrl' => Yii::$app->request->hostInfo . Yii::$app->request->url,
                                    'columns' => $kolomTampil,
                                    'layout' => '{items}{pager}<span style="text-align:right">{summary}</span>',
                                    'showPageSummary' => false,
                                    'pjax' => true,
                                    'bordered' => true,
                                    'striped' => true,
                                    'condensed' => true,
                                    'hover' => true,
                                    'responsive' => true,
                                    // 'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                                    'export' => false,
                                    'panel' => ['type' => 'default',],
                                ]);
                                ?>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <p>Dihitung berdasarkan tanggal dimulai/berakhirnya project.</p>
                    </div>
                </div>
            </div>
            <div class="card card-info samatinggi">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Tingkat Penyelesaian Tugas Harian 2 (Dua) Minggu Terakhir</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="dua-minggu" height="200" style="display: block; width: 764px; height: 200px;" width="764" class="chartjs-render-monitor"></canvas>
                    </div>
                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fas fa-square" style="color:#dff0d8"></i> Target
                        </span>
                        <span>
                            <i class="fas fa-square" style="color:rgba(34, 126, 34, 1)"></i> Selesai
                        </span>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-2">
            
            <div class="card card-info samatinggi">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Target Bulanan</h3>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="alert alert-success bg-olive no-border" style="padding:0px; border-radius:120px">
                        <h1 style="font-size:5rem; line-height:5rem; margin-bottom: 0px; margin-top:1.5rem">
                            <?php echo round($totaltarget, 0);
                            ?><span= style="font-size:1rem"> TASKS</span>
                        </h1>
                    </div>
                    <small>Target Pekerjaan Harian di Tahun <?php echo date("Y")
                                                            ?></small>
                </div>
            </div>
            <div class="card card-info samatinggi">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Progress Bulanan</h3>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="alert alert-success bg-olive no-border" style="padding:0px; border-radius:120px">
                        <h1 style="font-size:5rem; line-height:5rem; margin-bottom: 0px; margin-top:1.5rem">
                            <?php echo round($progress, 0); ?><span= style="font-size:1.5rem">%</span>
                        </h1>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress ?>%">
                        </div>
                    </div>
                    <small>Pekerjaan yang Selesai di Tahun <?php echo date("Y") ?></small>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(function() {
        'use strict'
        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        }
        var mode = 'index'
        var intersect = true
        var $salesChart = $('#dua-minggu')
        var salesChart = new Chart($salesChart, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($seriestanggal) ?>,
                datasets: [{
                    backgroundColor: '#dff0d8',
                    borderColor: '#dff0d8',
                    data: <?php echo json_encode($seriestarget) ?>
                }, {
                    backgroundColor: 'rgba(34, 126, 34, 1)',
                    borderColor: 'rgba(34, 126, 34, 1)',
                    data: <?php echo json_encode($seriesselesai) ?>
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                            callback: function(value) {
                                if (value >= 1000) {
                                    value /= 1000
                                    value += 'k'
                                }
                                return '' + value + ''
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                }
            }
        })
    })
</script>