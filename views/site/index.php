<?php

$this->title = 'Beranda';

use yii2fullcalendar\yii2fullcalendar;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap4\Modal;
use yii\web\JsExpression;

?>
<style>
    .table-striped>thead>tr>th,
    .table-striped>thead>tr>th>a,
    tbody>tr>td>a {
        color: white !important;
    }

    .bundar {
        border-radius: 16px !important;
    }

    .lebihbundar {
        border-radius: 32px !important;
    }

    .samatinggi {
        height: calc(100% - 1rem) !important;
    }

    .pagar {
        font-size: 1.5rem !important;
        margin-right: 0.5rem !important;
    }

    input[type=checkbox] {
        /* Double-sized Checkboxes */
        -ms-transform: scale(2);
        /* IE */
        -moz-transform: scale(2);
        /* FF */
        -webkit-transform: scale(2);
        /* Safari and Chrome */
        -o-transform: scale(2);
        /* Opera */
        padding: 10px;
    }
</style>
<?php
$JSCode = <<<EOF
function(calEvent) {
    var tanggal = calEvent.format(); 
    idnya = "proyek-" + tanggal;
    //console.log(idnya);
    //$('#proyek-'+tanggal).show();
    $('#load-pertama').hide();
    $('.carikalender[id*="' + idnya + '"]').fadeIn();;//tampilkan yang tanggal dipilih
    $('.carikalender[id!="' + idnya + '"]').hide();;//hide yang tidak dipilih
    //Cek kalau ada datanya
    var cek = document.getElementById(idnya);
    if (cek == null)
        $('#load-kedua').fadeIn()();
    else
        $('#load-kedua').hide();
    //console.log(cek);
    //$(".carikalender").show();//jalan, 
    return false;
    //$('#w0').fullCalendar('unselect');
}
EOF;

$JSDropEvent = <<<EOF
function(date) {
    alert("Dropped on " + date.format());
    if ($('#drop-remove').is(':checked')) {
        // if so, remove the element from the "Draggable Events" list
        $(this).remove();
    }
}
EOF;

$JSEventClick = <<<EOF
function(calEvent, jsEvent, view) {
    alert('Event: ' + calEvent.title);
    alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
    alert('View: ' + view.name);
    // change the border color just for fun
    $(this).css('border-color', 'red');
}
EOF;

?>
<?php
if (isset($presensi)) {
    // $jamdatang = $presensi['jam_datang'];
    // $jampulang = $presensi['jam_pulang'];
    // if (in_array(date("l"), ["Saturday", "Sunday"])) {
    //     $jamdatang = 'Libur';
    //     $jampulang = 'Libur';
    // } 
    if ($presensi['status_presensi'] == 1) {
        if ($presensi['jam_datang'] == NULL)
            $jamdatang = '-';
        else
            $jamdatang = date('H:i', strtotime($presensi['jam_datang'])) . ' WIB';

        if ($presensi['jam_pulang'] == NULL)
            $jampulang = '-';
        else
            $jampulang = date('H:i', strtotime($presensi['jam_pulang'])) . ' WIB';
    } elseif ($presensi['status_presensi'] == 2) {
        $jamdatang = 'CUTI';
        $jampulang = 'CUTI';
    } elseif ($presensi['status_presensi'] == 3) {
        $jamdatang = 'DL';
        $jampulang = 'DL';
    } elseif ($presensi['status_presensi'] == 4) {
        if (in_array(date("l"), ["Saturday", "Sunday"])) {
            $jamdatang = 'Libur';
            $jampulang = 'Libur';
        } else {
            if ($presensi['jam_datang'] == NULL)
                $jamdatang = '-';
            else
                $jamdatang = date('H:i', strtotime($presensi['jam_datang'])) . ' WIB';

            if ($presensi['jam_pulang'] == NULL)
                $jampulang = '-';
            else
                $jampulang = date('H:i', strtotime($presensi['jam_pulang'])) . ' WIB';
        }
    }
} else {
    $jamdatang = '-';
    $jampulang = '-';
}
?>

<div class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <?php if (Yii::$app->session->hasFlash('success')) : ?>
                <div class="alert alert-primary alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i>Berhasil!</h4>
                    <?= Yii::$app->session->getFlash('success') ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::$app->session->hasFlash('warning')) : ?>
                <div class="alert alert-primary alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i>Hai!</h4>
                    <?= Yii::$app->session->getFlash('warning') ?>
                </div>
            <?php endif; ?>
            <?= \hail812\adminlte\widgets\Callout::widget([
                'type' => 'info',
                'head' => '<h3>SK-EJM</h3>',
                'body' => 'Safira Khansa\'s Employees\' Jobs Management'
            ])
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-5">
                    <div class="card card-info samatinggi">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Presensi</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>Status Kehadiran</p>
                            <div class="row">
                                <div class="col-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info lebihbundar"><i class="far fa-clock"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Jam Datang</span>
                                            <span class="info-box-number">
                                                <?php echo ($jamdatang == '-' ? Html::a('Input', ['dailypresence/create?from=site&date=' . date("Y-m-d")], ['class' => 'btn btn-xs btn-info bundar modalButtonPresensi', 'data-pjax' => 0]) : $jamdatang) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning lebihbundar"><i class="far fa-clock"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Jam Pulang</span>
                                            <span class="info-box-number">
                                                <span class="info-box-number"><?php echo ($jampulang == '-'
                                                                                    ?
                                                                                    ($presensi == NULL
                                                                                        ?
                                                                                        Html::a('Input', ['dailypresence/create?from=site&date=' . date("Y-m-d")], ['class' => 'btn btn-xs btn-info bundar modalButtonPresensi', 'data-pjax' => 0])
                                                                                        :
                                                                                        Html::a('Input', ['dailypresence/update?from=site&id=' . $presensi['id_dailypresence']], ['class' => 'btn btn-xs btn-warning bundar modalButtonPresensi', 'data-pjax' => 0])
                                                                                    )
                                                                                    :
                                                                                    $jampulang) ?></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <?php //Html::a('Saya Cuti', ['dailyreport/create?date=' . date("Y-m-d")], ['class' => 'btn btn-block btn-sm btn-default bundar']) 
                                    ?>
                                    <?php
                                    $fmt = new \IntlDateFormatter('id_ID', null, null);
                                    $fmt->setPattern('d MMMM yyyy');
                                    // echo $fmt->format(strtotime(date("d F Y")));
                                    ?>
                                    <?= Html::a('Saya Cuti', ['dailypresence/createcuti?date=' . date("Y-m-d")], [
                                        'class' => 'btn btn-block btn-sm btn-default bundar',
                                        'data' => [
                                            'confirm' => 'Anda yakin Anda cuti pada hari ini? ' . $fmt->format(strtotime(date("d F Y"))) . '',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-6">
                                    <?php // Html::a('Saya DL', ['dailyreport/create?date=' . date("Y-m-d")], ['class' => 'btn btn-block btn-sm btn-default bundar']) 
                                    ?>
                                    <?= Html::a('Saya DL', ['dailypresence/createdl?date=' . date("Y-m-d")], [
                                        'class' => 'btn btn-block btn-sm btn-default bundar',
                                        'data' => [
                                            'confirm' => 'Anda yakin Anda DL pada hari ini? ' . $fmt->format(strtotime(date("d F Y"))) . '',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card card-info samatinggi">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Progress</h3>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div class="alert alert-success bg-olive no-border" style="padding:0px; border-radius:120px">
                                <h1 style="font-size:5rem; line-height:5rem; margin-bottom: 0px; margin-top:1.5rem">
                                    <?php echo $progress; ?><span= style="font-size:1.5rem">%</span>
                                </h1>
                            </div>
                            <small>Pekerjaan yang Selesai Menurut CKP Bulan Ini</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card card-info samatinggi">
                        <?php Pjax::begin(['id' => 'some_pjax_id']); ?>
                        <?=
                        ListView::widget([
                            'dataProvider' => $listDataProvider,
                            'options' => [
                                'tag' => 'div',
                            ],
                            'layout' => "
                                <div class='card-header border-0 bg-info'>
                                    <div class='d-flex justify-content-between'>
                                        <h3 class='card-title'>Notifikasi</h3>
                                        <div class='card-tools' style='margin-bottom: -1.2rem';>
                                            {pager}
                                        </div>
                                    </div>
                                </div>
                                \n<div class='card-body'>{items}</div>",
                            'itemView' => function ($model, $key, $index, $widget) {
                                return $this->render('_listjob_assign', ['model' => $model]);
                            },
                            'emptyText' => '<div class="alert alert-info">[Belum Ada Notifikasi]</div>',
                            'pager' => [
                                'firstPageLabel' => '',
                                'lastPageLabel' => '',
                                // 'prevPageLabel' => '<i class="fa fa-chevron-circle-left pagar"></i>',
                                'prevPageLabel' => '<button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>',
                                'nextPageLabel' => '<button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>',
                                'maxButtonCount' => 0,
                            ],
                        ]);
                        Pjax::end();
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-info">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Pekerjaan Hari Ini</h3>
                            </div>
                        </div>
                        <div class="card-body">

                            <?php Pjax::begin(['id' => 'some_pjax_id2']); ?>

                            <?php echo $this->render('_search_nonassign', ['model' => $searchModelNonAssign]) ?>

                            <div class="text-right">
                                <button id="tombolSelesai" type="button" onclick="submit()" class="btn btn-success btn-xs mb-2">Tandai Selesai</button>
                            </div>

                            <?=
                            ListView::widget([
                                'dataProvider' => $listDataProviderNonAssign,
                                'options' => [
                                    'tag' => 'div',
                                ],
                                'layout' => "{pager}\n{items}\n{summary}",
                                'summary' => 'Menampilkan <b>{totalCount}</b> pekerjaan.',
                                'emptyText' => '[Belum ada pekerjaan]',
                                'itemView' => function ($model, $key, $index, $widget) {
                                    return $this->render('_listjob_nonassign', ['model' => $model]);
                                },
                                'pager' => [
                                    'firstPageLabel' => '',
                                    'lastPageLabel' => '',
                                    'prevPageLabel' => '<button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>',
                                    'nextPageLabel' => '<button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>',
                                    'maxButtonCount' => 0,
                                ],
                            ]);
                            Pjax::end();
                            ?>
                        </div>
                        <div class="card-footer">
                            <?= Html::a('<i class="fas fa-plus-circle"></i> Tambah Pekerjaan Harian Saya...', ['dailyreport/create?date=' . date("Y-m-d")], ['class' => 'btn btn-outline-info btn-block btn-lg text-left modalButtonInputDP', 'data-pjax' => 0]) ?>
                            <!-- <form action="#" method="post">
                                <div class="input-group">
                                    <input type="text" name="message" placeholder="Tambah Pekerjaan Harian Saya..." class="form-control">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-info">Kirim</button>
                                    </span>
                                </div>
                            </form> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <?= yii2fullcalendar::widget(array(
                        'events' => $eventsKalender,
                        'options' => [
                            'themeSystem' => 'bootstrap',
                            'lang' => 'id',
                        ],
                        'clientOptions' => [
                            'selectable' => true,
                            'header' => [
                                'right' => ''
                            ],
                            'height' => 'auto',
                            'html' => true,
                            // 'selectHelper' => true,
                            // 'droppable' => true,
                            // 'editable' => true,
                            // 'drop' => new JsExpression($JSDropEvent),
                            'select' => new JsExpression($JSCode),
                            // 'eventClick' => new JsExpression($JSEventClick),
                            'defaultDate' => date('Y-m-d')
                        ],
                        // 'ajaxEvents' => Url::to(['/timetrack/default/jsoncalendar'])
                    )); ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Pekerjaan Harian Anda di <?php echo Yii::$app->user->identity->kantor ?> </b></h3>
                    </div>
                    <br />
                    <div class="callout callout-info">
                        <p>Silahkan Pilih Tanggal pada Kalender di Atas.</p>
                    </div>
                    <div class="callout callout-warning" id="load-pertama">
                        <h5>Belum Ada Pekerjaan di Hari ini</h5>
                    </div>
                    <div class="callout callout-danger" id="load-kedua" style="display:none">
                        <h5>Belum Ada Pekerjaan di Tanggal Tersebut</h5>
                        <?= Html::a('<i class="fas fa-plus-circle"></i> Tambah Pekerjaan Harian Saya...', ['dailyreport/create?date=' . date("Y-m-d")], ['class' => 'btn btn-info btn-block btn-sm text-left modalButtonInputDP', 'data-pjax' => 0, 'style' => 'text-decoration: none;']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php Pjax::begin(['id' => 'some_pjax_id3']); ?>

                    <?=
                    ListView::widget([
                        'dataProvider' => $listdataProviderKalender,
                        'options' => [
                            'tag' => 'div',
                        ],
                        'layout' => "{pager}\n{items}",
                        'summary' => 'Menampilkan <b>{totalCount}</b> projects.',
                        'emptyText' => '[Belum ada project]',
                        'itemView' => function ($model, $key, $index, $widget) {
                            return $this->render('_listjob_kalender', ['model' => $model]);
                        },
                        'pager' => [
                            'firstPageLabel' => '',
                            'lastPageLabel' => '',
                            'prevPageLabel' => '<button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>',
                            'nextPageLabel' => '<button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>',
                            'maxButtonCount' => 0,
                        ],
                    ]);
                    Pjax::end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
Modal::begin([
    'title' => 'Rincian Pekerjaan',
    'id' => 'modal',
    'size' => 'modal-lg'
]);

echo '<div id="modalContent"></div>';

Modal::end();
?>
<?php
Modal::begin([
    'title' => 'Input Pekerjaan',
    'id' => 'modalInputDP',
    'size' => 'modal-lg'
]);

echo '<div id="modalContentInputDP"></div>';

Modal::end();
?>
<?php
Modal::begin([
    'title' => 'Input Presensi Harian',
    'id' => 'modalPresensi',
    'size' => 'modal-lg'
]);

echo '<div id="modalContentPresensi"></div>';

Modal::end();
?>
<script>
    $(function() {
        // $(document).on('click', '.fc-day', function() 
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
        // $(document).on('click', '.fc-day', function() 
        $('.modalButtonPresensi').click(function() {
            $.get($(this).attr('href'), function(data) {
                $('#modalPresensi').modal('show').find('#modalContentPresensi').html(data)
            });
            return false;
        });
    });
</script>
<script>
    $(function() {
        // $(document).on('click', '.fc-day', function() 
        $('.modalButtonInputDP').click(function() {
            $.get($(this).attr('href'), function(data) {
                $('#modalInputDP').modal('show').find('#modalContentInputDP').html(data)
            });
            return false;
        });
    });
</script>
<script>
    var checkBoxes = $('.tandaselesai');
    checkBoxes.change(function() {
        $('#tombolSelesai').prop('disabled', checkBoxes.filter(':checked').length < 1);
    });
    $('.tandaselesai').change();

    // Ubah jadi selesai
    function submit() {
        var dialog = confirm("Apakah Anda sudah yakin ingin menandai kegiatan ini selesai?");
        if (dialog == true) {
            var checkboxes =
                document.getElementsByName('selesai');

            var result = [];

            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    result.push(checkboxes[i].value);
                }
            }

            var ajax = new XMLHttpRequest();
            $.ajax({
                type: "POST",
                url: 'dp_markselesai', // Your controller action
                data: {
                    keylist: result
                },
                success: console.log('SUKSES')
            });
        }
    }
</script>