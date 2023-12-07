<?php

// $this->title = 'Ringkasan Eksekutif Pimpinan';

use kartik\grid\GridView;
use yii\helpers\Html;
?> 
<?php
$dataProvider->pagination = false;
$dataProviderFuture->pagination = false;
$dataProviderFinished->pagination = false;
?> 

<div class="wrapper">
  <h1 style='font-family: "Poppins", Helvetica, "sans-serif" !important;'>Ringkasan Eksekutif Pimpinan - SK-EJM</h1>
  <div class="row">
    <div class="col-lg-10">
      <div class="card card-info">
        <div class="card-header border-0">
          <div class="d-flex justify-content-between">
            <hr />
          </div>
        </div> 
        <?php
        $kolomTampil = [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'project_name',
                'label' => 'Nama Project',
                'value' => function ($data) {
                    return '#' . $data->project_name;
                },
                'enableSorting' => false,
            ],
            [
                'attribute' => 'timkerja',
                'value' => function ($data) {
                    return $data->timkerjae->nama_timkerja;
                },
                'label' => 'Tim Kerja',
                'enableSorting' => false,
            ],
            [
                'attribute' => 'start_date',
                'label' => 'Tanggal Dimulai',
                'value' => function ($data) {
                    $fmt = new \IntlDateFormatter('id_ID', null, null);
                    $fmt->setPattern('d MMMM yyyy');
                    return $fmt->format(strtotime($data->start_date));
                },
                'hAlign' => 'center',
                'enableSorting' => false,
            ],
            [
                'attribute' => 'finish_date',
                'label' => 'Tanggal Berakhir',
                'value' => function ($data) {
                    $fmt = new \IntlDateFormatter('id_ID', null, null);
                    $fmt->setPattern('d MMMM yyyy');
                    return $fmt->format(strtotime($data->finish_date));
                },
                'hAlign' => 'center',
                'enableSorting' => false,
            ],
            [
                'value' => function ($data) {
                    return number_format((float) $data->persentase, 2, '.', '') . '%';
                },
                'label' => 'Progress',

                'content' => function ($model) {
                    if ($model->persentase <= 0) {
                        return '-';
                    } else {
                        return '
                        <div class="progress">
                            <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: ' . $model->persentase . '%">'
                        . number_format((float) $model->persentase, 2, '.', '') . '%
                                                                </div>
                        </div>';
                    }

                },
                'headerOptions' => ['style' => 'width:20%'],
                'hAlign' => 'center',
            ],
        ];
        ?> 
        <div class="card-body">
          <div class="card card-info card-outline">
            <div class="card-header p-0 pt-1 border-bottom-0">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Projects Sedang Berjalan</h3>
              </div>
            </div>
            <div class="card-body"> <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $kolomTampil,
                'layout' => '{items}',
                'showPageSummary' => false,
                'pjax' => true,
                'bordered' => true,
                'striped' => true,
                'condensed' => true,
                'hover' => true,
                'responsive' => true,
                'export' => false,
                // 'panel' => ['type' => 'default',],
            ]);
            ?> 
            </div>
            </div>
            <div class="card card-info card-outline">
            <div class="card-header p-0 pt-1 border-bottom-0">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Projects Direncanakan</h3>
              </div>
            </div>
            <div class="card-body"> <?=
GridView::widget([
    'dataProvider' => $dataProviderFuture,
    'columns' => $kolomTampil,
    'layout' => '{items}',
    'showPageSummary' => false,
    'pjax' => true,
    'bordered' => true,
    'striped' => true,
    'condensed' => true,
    'hover' => true,
    'responsive' => true,
    'export' => false,
    // 'panel' => ['type' => 'default',],
]);
?> </div>
          </div>
          <div class="card card-info card-outline">
            <div class="card-header p-0 pt-1 border-bottom-0">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Projects Selesai</h3>
              </div>
            </div>
            <div class="card-body"> <?=
GridView::widget([
    'dataProvider' => $dataProviderFinished,
    'columns' => $kolomTampil,
    'layout' => '{items}',
    'showPageSummary' => false,
    'pjax' => true,
    'bordered' => true,
    'striped' => true,
    'condensed' => true,
    'hover' => true,
    'responsive' => true,
    'export' => false,
    // 'panel' => ['type' => 'default',],
]);
?> </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <p>* Dihitung berdasarkan tanggal dimulai/berakhirnya project.</p>
          <br />
          <br />
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
          <div class="alert alert-success bg-olive no-border">
            <h1 style="font-size:3rem; line-height:3rem; margin-top: -30px; margin-bottom:-15px"> <?php echo round($totaltarget, 0);
?> <span= style="font-size:1rem"> TASKS</span>
            </h1>
          </div>
          <small>[ Target Pekerjaan Harian di Tahun <?php echo date("Y")
?> ] </small>
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
            <h1 style="font-size:3rem; line-height:3rem; margin-top: -30px; margin-bottom:-15px"> <?php echo round($progress, 0); ?> <span= style="font-size:1.5rem">%</span>
            </h1>
          </div>
          <div class="progress">
            <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 
							<?php echo $progress ?>%">
            </div>
          </div>
          <small>[ Pekerjaan yang Selesai di Tahun <?php echo date("Y") ?> ] </small>
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
        labels: <?php echo json_encode($seriestanggal) ?> ,
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