<?php

use yii\helpers\Html;
// use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\bootstrap4\Modal;

/** @var yii\web\View $this */
/** @var app\models\Dailyreport $model */

$this->title = $model->id_keg;
$this->params['breadcrumbs'][] = ['label' => 'Laporan Pekerjaan Harian', 'url' => ['index']];
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

    <?= DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'panel' => [
            'heading' => 'Laporan Harian # ' . $model->id_keg,
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'hAlign' => 'left',
        'buttons1' => '',
        'attributes' => [
            'id_keg',            
            [
                'attribute' => 'owner',
                'value' => $model->ownere->gelar_depan . $model->ownere->nama . ', ' . $model->ownere->gelar_belakang,
                'label' => 'Pengusul',
            ],
            [
                'attribute' => 'assigned_to',
                'value' => ($model->assigned_to != NULL) ? $model->assignedtoe->gelar_depan . $model->assignedtoe->nama . ', ' . $model->assignedtoe->gelar_belakang : '-',
                'label' => 'Delegasi',
            ],
            [
                'attribute' => 'timkerjaproject',
                'value' => ($model->timkerjaproject != NULL) ? "#" . $model->timkerjaprojecte->project_name : '-',
                'label' => 'Project',
            ],
            [
                'attribute' => 'rincian_report',
                'label' => 'Rincian Kegiatan',
            ],
            [
                'attribute' => 'priority',
                'value' => (($model->priority == 1) ? '<i class="fas fa-star"></i>' : '-'),
                'label' => 'Prioritas',
                'format' => 'html'
            ],
            [
                'attribute' => 'is_setujuketuatim',
                'value' => ($model->is_setujuketuatim == 1) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>',
                'label' => 'Disetujui Ketua Tim',
                'format' => 'html'
            ],
            // 'is_setujuketuatim',
            [
                'attribute' => 'status_selesai',
                'value' => ($model->status_selesai == 1) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>',
                'label' => 'Status',
                'format' => 'html'
            ],
            // 'rincian_report:ntext',
            // 'status_selesai',
            [
                'attribute' => 'tanggal_kerja',
                'label' => 'Tanggal',
                'value' => date('d F Y', strtotime($model->tanggal_kerja)),
            ],
            // 'tanggal_kerja',
            // 'timestamp',
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
            // 'timestamp_lastupdated',
            // 'ket:ntext',
            [
                'attribute' => 'ket',
                'value' => ($model->ket != NULL) ? $model->ket : '-',
                'label' => 'Keterangan',
            ],
        ],
    ]) ?>

</div>
<?php
Modal::begin([
    'title' => 'Rincian CKP Pegawai',
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