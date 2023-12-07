<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
// use yii\grid\GridView;
use kartik\grid\GridView;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TimkerjaCari */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Tim Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper">

    <div class="d-flex justify-content-between">
        <div class="p-2">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
        <div class="p-2">
            <?php if (date("n") == 1) { ?>
                <?= Html::a('<i class="fas fa-plus-square"></i> Tambah Tim Kerja Baru', ['create'], ['class' => 'btn btn-outline-info bundar btn-sm']) ?>
            <?php } ?>
        </div>
    </div>

    <?php //echo $this->render('_search', ['model' => $searchModel]);
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
            <?php Pjax::begin(['id' => 'some_pjax_id']); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
                'columns' => [
                    // ['class' => 'yii\grid\SerialColumn'],
                    ['class' => 'kartik\grid\SerialColumn',],
                    // 'id_timkerja',
                    // 'tahun',
                    // [
                    //     'attribute' => 'tahun',
                    //     'group' => true,
                    // ],
                    // 'satker',
                    [
                        'attribute' => 'satker',
                        'value' => 'penggunasatkere.nama_satker',
                        'group' => true,
                        'visible' => Yii::$app->user->identity->levelsuperadmin == true ? true : false
                    ],
                    'nama_timkerja',
                    [
                        'header' => 'Ketua',
                        'value' => function ($data) {
                            return $data->ketua;
                        },
                        'contentOptions' => ['class' => 'text-center'],
                        'hAlign' => 'center',
                        'mergeHeader' => true
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'header' => 'Anggota',
                        'template' => '{view}',
                        'contentOptions' => ['class' => 'text-center'],
                        'visible' => true == Yii::$app->user->identity->levelsuperadmin || true == Yii::$app->user->identity->leveladmin ? true : false,
                        'buttons'  => [
                            'view' => function ($key, $client) {
                                return Html::a('<i class="fa">&#xf06e;</i>', $key, ['title' => 'Lihat daftar anggota tim ini', 'class' => 'modalButton', 'data-pjax' => 0]);
                            },
                        ],
                    ],
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
                                    'data-pjax' => 1,
                                    'data-confirm' => 'Anda yakin ingin menghapus Tim Kerja ini dari sistem? <br/><strong>' .
                                        $model['nama_timkerja'] . '</strong>'
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
            <?php Pjax::end() ?>
        </div>
    </div>
</div>
<?php
Modal::begin([
    'title' => 'Rincian Tim',
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