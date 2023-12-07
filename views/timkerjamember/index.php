<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
// use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TimkerjamemberCari */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rekap Tim Kerja Member';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper">

    <p>
        <?php // Html::a('Tambah Anggota Tim Kerja', ['create'], ['class' => 'btn btn-success']) 
        ?>
    </p>
    <?php
    // if (Yii::$app->user->identity->levelsuperadmin == true)
    //     echo $this->render('_search', ['model' => $searchModel]);
    ?>

    <div class="row">
        <div class="col-lg-12">
            <?php if (Yii::$app->session->hasFlash('warning')) : ?>
                <div class="alert alert-warning alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i>Hai!</h4>
                    <?= Yii::$app->session->getFlash('warning') ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::$app->user->identity->levelsuperadmin == true || Yii::$app->user->identity->leveladmin == true) : ?>
                <div class="d-flex justify-content-between">
                    <?php if (Yii::$app->user->identity->levelsuperadmin == true) : ?>
                        <div class="p-2">
                            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="p-2">
                        <?= Html::a('<i class="far fa-list-alt"></i> List Tim Kerja', ['timkerja/index?tahun=' . date("Y")], ['class' => 'btn btn-outline-info bundar btn-sm']) ?>
                        <?php if (date("n") == 1) { ?>
                            <?= Html::a('<i class="fas fa-plus-square"></i> Tambah Anggota Tim', ['timkerjamember/create'], ['class' => 'btn btn-outline-info bundar btn-sm']) ?>
                        <?php } ?>
                        <?= Html::a('<i class="far fa-list-alt"></i> Rekap Keanggotaan', ['rekapmemberindex'], ['class' => 'btn btn-outline-info bundar btn-sm']) ?>
                    </div>
                </div>
            <?php endif; ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
                'columns' => [
                    // ['class' => 'yii\grid\SerialColumn'],
                    ['class' => 'kartik\grid\SerialColumn',],
                    // 'id_timkerjamember',
                    // 'timkerja',
                    [
                        'attribute' => 'satkere',
                        'value' => 'penggunasatkere.nama_satker',
                        'group' => true,
                        'visible' => Yii::$app->user->identity->levelsuperadmin == true ? true : false
                    ],
                    [
                        'attribute' => 'timkerjae',
                        'value' => 'timkerjae.nama_timkerja',
                        'group' => true,
                        // 'enableSorting' => false
                    ],
                    // 'anggota',
                    [
                        'attribute' => 'anggota',
                        'value' => function ($data) {
                            return $data->penggunae->gelar_depan . ' ' .  $data->penggunae->nama . ', ' .  $data->penggunae->gelar_belakang;
                        },
                        'enableSorting' => false
                    ],
                    // 'is_ketua',
                    [
                        'attribute' => 'is_ketua',
                        'value' => function ($data) {
                            if ($data->is_ketua == 1)
                                return 'Ketua';
                            else
                                return '-';
                        },
                        'header' => 'Keterangan',
                        'enableSorting' => false,
                        'filter' => false
                    ],
                    // [
                    //     'attribute' => 'is_ketua',
                    //     'class' => 'kartik\grid\BooleanColumn',
                    //     'trueLabel' => 'Ketua',
                    //     'falseLabel' => 'Anggota',
                    //     'enableSorting' => false
                    // ]
                    // 'is_member',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'header' => 'Opsi',
                        'template' => '{update}&nbsp;&nbsp;&nbsp;{delete}',
                        'contentOptions' => ['class' => 'text-center'],
                        'visible' => true == (Yii::$app->user->identity->levelsuperadmin || true == Yii::$app->user->identity->leveladmin) && date("n") == 1 ? true : false,
                        'buttons'  => [
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-trash-alt"></i>', $url, [
                                    'data-method' => 'post',
                                    'data-pjax' => 0,
                                    'data-confirm' => 'Anda yakin ingin membatalkan membership pegawai ini? <br/><strong>' .
                                        $model['penggunae']['gelar_depan'] . ' ' . $model['penggunae']['nama'] . ', ' . $model['penggunae']['gelar_belakang'] . '</strong>'
                                ]);
                            },
                        ],
                    ],
                ],
                'layout' => '<span style="text-align:right">{summary}</span>{items}{pager}',
                'showPageSummary' => true,
                'responsive' => false,
                'persistResize' => false,
                'bordered' => true,
                'striped' => true,
                'condensed' => true,
                'hover' => true,
                'floatOverflowContainer' => true,
                'floatHeader' => true,
                'floatHeaderOptions' => [
                    'scrollingTop' => '0',
                    'position' => 'absolute',
                    'top' => 50
                ],
                'toggleDataOptions' => ['minCount' => 10],
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'export' => [
                    'fontAwesome' => true,
                    'filename' => 'Panduan Butir' . date('d-M-yy H:i:s'),
                    'label' => '<i class="fa">&#xf56d;</i> Unduh',
                ],
                'exportConfig' => [
                    GridView::CSV => ['label' => 'Unduh CSV', 'filename' => 'Anggota Tim Kerja -' . date('d-M-Y')],
                    GridView::HTML => ['label' => 'Unduh HTML', 'filename' => 'Anggota Tim Kerja -' . date('d-M-Y')],
                    GridView::EXCEL => ['label' => 'Unduh EXCEL', 'filename' => 'Anggota Tim Kerja -' . date('d-M-Y')],
                    GridView::TEXT => ['label' => 'Unduh TEXT', 'filename' => 'Anggota Tim Kerja -' . date('d-M-Y')],
                ],
                // 'export' => false,
                'responsiveWrap' => false,
                'containerOptions' => ['style' => 'overflow-y:scroll; height:768px'],
                'panel' => ['type' => 'info',],
            ]); ?>


        </div>
    </div>
</div>