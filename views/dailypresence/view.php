<?php

use yii\helpers\Html;
// use yii\widgets\DetailView;
use kartik\detail\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Dailypresence $model */

$this->title = $model->id_dailypresence;
$this->params['breadcrumbs'][] = ['label' => 'Presensi Harian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="wrapper">

    <?php if (Yii::$app->session->hasFlash('success')) : ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Disimpan!</h4>
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <p>
        <?php //if ((Yii::$app->user->identity->username == $model['pegawai']
                        //|| Yii::$app->user->identity->levelsuperadmin == true) && $model->is_setujuadmin != 1) { ?>
            <?php // Html::a('Update', ['update', 'id' => $model->id_dailypresence, 'from' => 'dailypresence'], ['class' => 'btn btn-outline-primary bundar btn-sm']) ?>
       
            <?php // Html::a('Hapus', ['delete', 'id' => $model->id_dailypresence], [
            //     'class' => 'btn btn-outline-danger bundar btn-sm',
            //     'data' => [
            //         'confirm' => 'Anda yakin ingin presensi di tanggal ini?<br/><strong>' . $model->penggunae->nama . '</strong> pada <strong>' . date('d F Y', strtotime($model->tanggal)) . '</strong>',
            //         'method' => 'post',
            //     ],
            // ]) ?>
        <?php // } ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'panel' => [
            'heading' => 'Presensi Harian # ' . $model->id_dailypresence,
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'hAlign' => 'left',
        'buttons1' => '',
        'attributes' => [
            'id_dailypresence',
            // 'id_ckp',            
            [
                'attribute' => 'pegawai',
                'value' => $model->penggunae->gelar_depan . $model->penggunae->nama . ', ' . $model->penggunae->gelar_belakang,
                'label' => 'Pegawai',
            ],
            [
                'attribute' => 'tanggal',
                'label' => 'Tanggal',
                'value' => date('d F Y', strtotime($model->tanggal)),
            ],
            [
                'attribute' => 'jam_datang',
                'value' => ($model->jam_datang != NULL) ? date('H:i', strtotime($model->jam_datang)) . " WIB" : '-',
                'label' => 'Jam Datang',
            ],
            [
                'attribute' => 'jam_pulang',
                'value' => ($model->jam_pulang != NULL) ? date('H:i', strtotime($model->jam_pulang)) . " WIB" : '-',
                'label' => 'Jam Pulang',
            ],
            [
                'attribute' => 'is_setujuadmin',
                'value' => ($model->is_setujuadmin != NULL) ? (($model->is_setujuadmin != NULL) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>') : '-',
                'label' => 'Disetujui Admin',
                'format' => 'html'
            ],
            [
                'attribute' => 'status_presensi',
                'value' => $model->dailypresencestatuse->keterangan_presensi,
                'label' => 'Status Presensi',
                'format' => 'html'
            ],
            [
                'attribute' => 'timestamp',
                'value' => \Yii::$app->formatter->asDatetime(strtotime($model->timestamp), "d MMMM y 'pada' H:mm a"),
                'label' => 'Diinput Pada',
            ],
            [
                'attribute' => 'timestamp_lastupdated',
                'value' => \Yii::$app->formatter->asDatetime(strtotime($model->timestamp_lastupdated), "d MMMM y 'pada' H:mm a"),
                'label' => 'Terakhir Diupdate Pada',
            ],
        ],
    ]) ?>

</div>