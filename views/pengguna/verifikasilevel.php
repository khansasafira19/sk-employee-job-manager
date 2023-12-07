<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Level;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LevelpenggunaCari */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Verifikasi Level Pegawai';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper">
    <?php if (Yii::$app->session->hasFlash('success')) : ?>
        <div class="alert alert-warning alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Berhasil!</h4>
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->session->hasFlash('warning')) : ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Hai!</h4>
            <?= Yii::$app->session->getFlash('warning') ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <p>Daftar level pegawai yang berstatus aktif.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <?php Pjax::begin(['id' => 'some_pjax_id']); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'filterUrl' => Yii::$app->request->hostInfo . Yii::$app->request->url,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            //'id_levelpengguna',
                            [
                                'attribute' => 'username',
                                'format' => 'raw',
                                'label' => 'Pengguna',
                                //'value' => 'penggunae.nama',
                                'value' => function ($data) {
                                    return Html::a($data->penggunae->nama, Yii::$app->request->baseUrl . '/pengguna/view?id=' . $data['username'], [
                                        'title' => 'Lihat detail pegawai ini', 'class' => 'modalButton', 'data-pjax' => '1'
                                    ]);
                                }
                                // 'group' => true,
                            ],
                            // 'level',
                            [
                                'attribute' => 'level',
                                'value' => 'levele.nama_level',
                                'filter' => Html::activeDropDownList($searchModel, 'level', ArrayHelper::map(Level::find()->asArray()->all(), 'id_level', 'nama_level'), ['class' => 'form-control input', 'prompt' => 'Piih Level']),
                            ],
                            // 'autentikasi',
                            [
                                'attribute' => 'autentikasi',
                                'class' => 'kartik\grid\BooleanColumn',
                                'label' => 'Autentikasi',
                                'trueLabel' => 'YES!',
                                'falseLabel' => 'NOPE'
                                // 'format' => 'raw',
                                // 'value' => function ($data) {
                                //     return ($data['status'] == 0) ? '<center><i class="fa">&#xf00d;</i></center>' : '<center><i class="fa">&#xf00c;</i></center>';
                                // },
                            ],
                            // [
                            //     'header' => 'Approve/Revoke',
                            //     'format' => 'raw',
                            //     'value' => function ($data) {
                            //         return Html::a("<center><i class='fa'>&#xf35d;</i></center>", Yii::$app->request->baseUrl . '/pengguna/approverevoke?id=' . $data['username'], [
                            //             'title' => 'Lihat detail pengguna ini', 'class' => 'modalButton', 'data-pjax' => '0',
                            //             'data-confirm' => 'Anda yakin ingin meng-approve/revoke level pengguna ini? <br/><strong>' . $data->penggunae->nama . '</strong>'
                            //         ]);
                            //     }
                            //     // 'group' => true,
                            // ],
                            [
                                'class' => 'kartik\grid\ActionColumn',
                                'header' => 'Approve/Revoke',
                                'template' => '{approverevokelevel}',
                                'contentOptions' => ['class' => 'text-center'],
                                'buttons' => [
                                    'approverevokelevel' => function ($url, $model, $key) {
                                        return Html::a('<i class="fa text-danger">&#xf362;</i>', 'approverevokelevel?id=' . $key, [
                                            'data-method' => 'post',
                                            'data-pjax' => 0,
                                            'data-confirm' => 'Anda yakin ingin me-revoke/approve level pegawai ini? <br/><strong>'
                                                . $model->penggunae->nama . '</strong> sebagai <strong>' . $model->levele->nama_level . '</strong>'
                                        ]);
                                    },
                                ],
                                'visibleButtons' => [
                                    'approverevokelevel' => function ($model, $key, $index) {
                                        if (true === Yii::$app->user->identity->levelsuperadmin) {
                                            return ((Yii::$app->user->identity->username === $model['username'] //datanya sendiri
                                                && $model['level'] === 0) || $model['level'] === 5 //menghapus dirinya dari superadmin
                                            ) ? false : true;
                                        } else
                                            return false;
                                    },
                                ],
                            ],

                            //['class' => 'yii\grid\ActionColumn'],
                        ],
                        'layout' => '<span style="text-align:right">{summary}</span>{items}{pager}',
                        'showPageSummary' => true,
                        'pjax' => true,
                        'bordered' => true,
                        'striped' => true,
                        'condensed' => true,
                        'hover' => true,
                        'responsive' => true,
                        'persistResize' => false,
                        'toggleDataOptions' => ['minCount' => 10],
                        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                        'export' => [
                            'fontAwesome' => true,
                            'filename' => 'Daftar Pengguna ' . date('d-M-yy H:i:s'),
                            'label' => '<i class="fa">&#xf56d;</i> Unduh',
                        ],
                        'containerOptions' => ['style' => 'overflow-y:scroll; height:768px'],
                        'panel' => ['type' => 'info',],
                        'floatOverflowContainer' => true,
                        'floatHeader' => true,
                        'floatHeaderOptions' => [
                            'scrollingTop' => '0',
                            'position' => 'absolute',
                            'top' => 50
                        ],
                    ]); ?>
                    <?php Pjax::end() ?>
                </div>
                <div class="card-footer">
                    <p>Silahkan cek profil pegawai sebelum menyetujui verifikasi.</p>
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

echo '<div id="modalContent" style="background: #fff!important"></div>';

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