<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Pangkatgol;
use kartik\form\ActiveForm;

$this->title = 'Pegawai';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    tbody>tr>td>a {
        display: inline-block;
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

    <div class="d-flex flex-row-reverse">
        <div class="p-2">
            <?php if (Yii::$app->user->identity->levelsuperadmin == true) : ?>

                <?= Html::a('<i class="fas fa-check text-info"></i> Verifikasi Level', ['verifikasilevel'], ['class' => 'btn btn-outline-info bundar btn-sm']) ?>

            <?php endif; ?>
            <?php if (Yii::$app->user->identity->levelsuperadmin == true || Yii::$app->user->identity->leveladmin == true) : ?>

                <?= Html::a('<i class="fas fa-user-plus text-info"></i> Tambah Pegawai', ['create'], ['class' => 'btn btn-outline-info bundar btn-sm']) ?>

            <?php endif; ?>
        </div>
    </div>


    <?php // echo $this->render('_search', ['model' => $searchModel]);  
    ?>

    <?php

    function konversi_nip($nip, $batas = " ")
    {
        $nip = trim($nip, " ");
        $panjang = strlen($nip);

        if ($panjang == 18) {
            $sub[] = substr($nip, 0, 8); // tanggal lahir
            $sub[] = substr($nip, 8, 6); // tanggal pengangkatan
            $sub[] = substr($nip, 14, 1); // jenis kelamin
            $sub[] = substr($nip, 3, 3); // nomor urut

            return $sub[0] . $batas . $sub[1] . $batas . $sub[2] . $batas . $sub[3];
        } else {
            return $nip;
        }
    }

    // // Contoh penggunaan fungsi
    // // konversi nip 18 digit
    // // hasil: 19700518 200503 1 005
    // echo konversi_nip("197005182005031004");
    // echo "<br/>";

    ?>
    <?php
    function getImg($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }


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
            'label' => 'Nama Pegawai'
        ],
        'username',
        [
            'value' => function ($data) {
                if ($data->jumlahtim > 0)
                    return '<i class="fas fa-border-none"></i> ' . $data->jumlahtim;
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
                    return '<i class="fas fa-paperclip"></i> ' . $data->jumlahproject;
                else
                    return '-';
            },
            'label' => 'Projects',
            'format' => 'html',
            'hAlign' => 'center',
        ],
        [
            'value' => function ($data) {
                if ($data->jumlahtugas > 0)
                    return '<i class="far fa-copy"></i> ' . $data->jumlahtugas;
                else
                    return '-';
            },
            'label' => 'Tugas Harian',
            'format' => 'html',
            'hAlign' => 'center',
        ],
        // [
        //     'attribute' => 'nip',
        //     'value' => function ($data) {
        //         return konversi_nip($data->nip);
        //     },
        //     'hAlign' => 'right',
        //     'contentOptions' => ['style' => 'font-family: Courier New; font-weight:bold'],
        // ],
        // 'pangkatgol',
        // [
        //     'attribute' => 'pangkatgole',
        //     'value' => 'pangkatgole.nama_pangkatgol',
        //     'mergeHeader' => true,
        //     'visible' => (Yii::$app->controller->action->id == 'index') ? true : false,
        //     //'filter' => Html::activeDropDownList($searchModel, 'pangkatgole', ArrayHelper::map(Pangkatgol::find()->asArray()->all(), 'id_pangkatgol', 'nama_pangkatgol'), ['class' => 'form-control input', 'prompt' => 'Pilih Pangkat/Gol']),
        // ],
        [
            'attribute' => 'fungsie',
            'value' => function ($data) {
                return $data->fungsie->nama_fungsi;
            },
            // 'group' => true,
            'hAlign' => 'center',
            'vAlign' => 'middle'
        ],
        [
            'attribute' => 'jabatan',
            'value' => 'jabatane.nama_jabatan',
            'mergeHeader' => true,
            'visible' => (Yii::$app->controller->action->id == 'index') ? true : false,
        ],
        // [
        //     'attribute' => 'email',
        //     'mergeHeader' => true,
        // ],
        // 'email',
        [

            'label' => 'Level',
            'value' => function ($model) {
                $items = [];
                foreach ($model->levele as $key => $value) {
                    if ($model->levelpenggunae[$key]['autentikasi'] == 1) //level terautentikasi
                        $items[] = $value->nama_level;
                }
                if (!empty($items)) {
                    // return implode(', ', $items);
                    return  "<p>+ " . implode("<br/>+ ", $items) . "</p>";
                    // return "<ul><li>" . implode("</li><li>", $items) . "</li></ul>";
                } else
                    return '[BELUM ADA LEVEL]';
                //print_r($model->levelpenggunae[0]['autentikasi']);
            },
            'format' => 'html',
            'mergeHeader' => true,
            'visible' => (Yii::$app->user->identity->levelpegawai === false) ? true : false,
        ],
        // [
        //     'label' => 'Tambah Level',
        //     'format' => 'raw',
        //     // 'visible' => Yii::$app->controller->action->id == 'indexspd' ? true : false,
        //     'visible' => ((Yii::$app->user->identity->levelsuperadmin === true
        //         || Yii::$app->user->identity->leveladmin === true)) ? true : false,
        //     // 'value' => function ($data) {
        //     //     if (true == Yii::$app->user->identity->levelsuperadmin || (Yii::$app->user->identity->levelsuperadmin === false && $data->fungsi_pengguna == Yii::$app->user->identity->fungsi_pengguna)) {
        //     //         return Html::a(
        //     //             "<center><i class='fa'>&#xf044;</i></center>",
        //     //             Yii::$app->request->baseUrl . '/pengguna/createlevel/?username=' . $data['username'],
        //     //             ['title' => 'Update level pengguna ini', 'target' => '_blank', 'data-pjax' => 10]
        //     //         );
        //     //     } else {
        //     //         return '';
        //     //     }
        //     // },
        //     'value' => function ($model) {
        //         $items = [];
        //         foreach ($model->levele as $key => $value) {
        //             if ($model->levelpenggunae[$key]['autentikasi'] == 1) //level terautentikasi
        //                 $items[] = $value->nama_level;
        //         }
        //         if (true == Yii::$app->user->identity->levelsuperadmin || (Yii::$app->user->identity->levelsuperadmin === false && $model->fungsi_pengguna == Yii::$app->user->identity->fungsi_pengguna)) {
        //             if (count($items) > 2)
        //                 return '';
        //             else
        //                 return Html::a(
        //                     "<center><i class='fa'>&#xf067;</i></center>",
        //                     Yii::$app->request->baseUrl . '/pengguna/createlevel/?username=' . $model['username'],
        //                     ['title' => 'Update level pengguna ini', 'target' => '_blank', 'data-pjax' => 10]
        //                 );
        //         } else {
        //             return '';
        //         }


        //         //print_r($model->levelpenggunae[0]['autentikasi']);
        //     },
        //     'mergeHeader' => true,
        // ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Detail',
            'template' => '{view}&nbsp;&nbsp;&nbsp;{update}&nbsp;&nbsp;&nbsp;{delete}&nbsp;&nbsp;&nbsp;{tambahlevel}',
            'contentOptions' => ['class' => 'text-center'],
            'visible' => true == Yii::$app->user->identity->levelsuperadmin || true == Yii::$app->user->identity->leveladmin ? true : false,
            'visibleButtons' => [
                'update' => function ($model, $key, $index) {
                    return (true == Yii::$app->user->identity->levelsuperadmin || (true == Yii::$app->user->identity->leveladmin &&
                        $model['fungsi_pengguna'] == Yii::$app->user->identity->fungsi_pengguna))
                        ? true : false;
                },
                'delete' => function ($model, $key, $index) {
                    return ((true == Yii::$app->user->identity->levelsuperadmin && Yii::$app->user->identity->username != $model['username']) //tidak bisa menghapus datanya sendiri
                        ||
                        (true == Yii::$app->user->identity->leveladmin && $model['fungsi_pengguna'] == Yii::$app->user->identity->fungsi_pengguna && Yii::$app->user->identity->username != $model['username'])) //tidak bisa menghapus datanya fungsi orang lain
                        ? true : false;
                },
                'tambahlevel' => function ($model, $key, $index) {
                    $items = [];
                    foreach ($model->levele as $key => $value) {
                        if ($model->levelpenggunae[$key]['autentikasi'] == 1) //level terautentikasi
                            $items[] = $value->nama_level;
                    }
                    if (true == Yii::$app->user->identity->levelsuperadmin || (Yii::$app->user->identity->levelsuperadmin === false && $model->fungsi_pengguna == Yii::$app->user->identity->fungsi_pengguna)) {
                        if (count($items) > 2)
                            return false;
                        else
                            return true;
                    } else
                        return false;
                },
            ],
            'buttons'  => [
                'view' => function ($key, $client) {
                    //$url = 'update/'.$key;
                    return Html::a('<i class="fa">&#xf06e;</i>', $key, ['title' => 'Lihat rincian pengguna ini', 'class' => 'modalButton', 'data-pjax' => '0']);
                    //return Html::a('<button class="btn btn-sm tombol-biru"><i class="fa text-info">&#xf06e;</i></button>', $key, ['title' => 'Lihat rincian logbook ini', 'class' => 'modalButton', 'data-pjax' => '0']);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-trash-alt"></i>', $url, [
                        'data-method' => 'post',
                        'data-pjax' => 0,
                        'data-confirm' => 'Anda yakin ingin menonaktifkan pengguna ini? <br/><strong>' . $model['nama'] . '</strong>'
                    ]);
                },
                'tambahlevel' => function ($url, $model, $key) {
                    return Html::a(
                        "<center><i class='fa'>&#xf067;</i></center>",
                        Yii::$app->request->baseUrl . '/pengguna/createlevel/?username=' . $model['username'],
                        ['title' => 'Update level pengguna ini', 'target' => '_blank', 'data-pjax' => 10]
                    );
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
                        <h3 class="card-title">Daftar Pegawai BPS Provinsi Bengkulu</h3>
                    </div>
                </div>
                <div class="card card-info card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Pegawai Aktif</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Non-Aktif</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Selesai</a>
                            </li> -->
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-three-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">

                                <!-- <div class="d-flex justify-content-center"> -->
                                <!-- <div class="p-2">Flex item 1</div> -->
                                <!-- <div class="p-2"> -->
                                <?php
                                // $form = ActiveForm::begin([
                                //     'action' => ['index'],
                                //     'method' => 'get',
                                //     'type' => ActiveForm::TYPE_INLINE,
                                //     'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
                                //     'fieldConfig' => ['options' => ['class' => 'form-group']], // spacing field groups
                                //     'options' => ['style' => 'align-items: flex-start; width:100%!important'] // set style for proper tooltips error display
                                // ]); 
                                ?>

                                <?php //$form->field($searchModel, 'globalSearch', ['options' => [],]) 
                                ?>

                                <!-- <div class=" input-group-append"> -->
                                <?php // Html::submitButton('.<i class="fas fa-search fa-fw"></i>.', ['class' => 'btn btn-sidebar btn-success']) 
                                ?>
                                <!-- </div> -->
                                <?php // ActiveForm::end(); 
                                ?>
                                <!-- </div> -->
                                <!-- <div class="p-2">Flex item 1</div> -->
                                <!-- </div> -->

                                <?php Pjax::begin(['id' => 'some_pjax_id']); ?>

                                <?= GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    // 'filterModel' => false,
                                    'resizableColumns' => true,
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
                                    // 'replaceTags' => [
                                    //     '{custom}' => function ($widget) {
                                    //         // you could call other widgets/custom code here
                                    //         return '
                                    //         <div class="btn-group">
                                    //         ' .
                                    //             Html::a('<i class="fas fa-user-plus text-info" style="font-size: 1.5rem"></i>', 'create', ['title' => 'Tambah Pegawai Baru', 'data-pjax' => 0])
                                    //             . '&nbsp;' .
                                    //             Html::a('<i class="fas fa-trash-alt text-danger" style="font-size: 1.5rem"></i>', 'bulkdelete', ['title' => 'Nonaktifkan Pegawai', 'data-pjax' => 0])
                                    //             . '&nbsp;' .
                                    //             '
                                    //         </div>
                                    //         ';
                                    //     },

                                    // ]

                                ]); ?>
                                <?php Pjax::end() ?>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                                <?php Pjax::begin(['id' => 'some_pjax_id']); ?>
                                <?= GridView::widget([
                                    'dataProvider' => $dataProviderNonAktif,
                                    // 'filterModel' => false,
                                    'resizableColumns' => true,
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
                                    // 'replaceTags' => [
                                    //     '{custom}' => function ($widget) {
                                    //         // you could call other widgets/custom code here
                                    //         return '
                                    //         <div class="btn-group">
                                    //         ' .
                                    //             Html::a('<i class="fas fa-user-plus text-info" style="font-size: 1.5rem"></i>', 'create', ['title' => 'Tambah Pegawai Baru', 'data-pjax' => 0])
                                    //             . '&nbsp;' .
                                    //             Html::a('<i class="fas fa-trash-alt text-danger" style="font-size: 1.5rem"></i>', 'bulkdelete', ['title' => 'Nonaktifkan Pegawai', 'data-pjax' => 0])
                                    //             . '&nbsp;' .
                                    //             '
                                    //         </div>
                                    //         ';
                                    //     },

                                    // ]

                                ]); ?>
                                <?php Pjax::end() ?>
                            </div>
                            <!-- <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
                                
                            </div> -->

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
<?php
Modal::begin([
    'title' => 'Rincian Data Pegawai',
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