<?php

use yii\helpers\Html;
// use yii\widgets\DetailView;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Timkerjamember */

$this->title = 'Detail Anggota Tim Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Tim Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="wrapper">
    <?php if (Yii::$app->session->hasFlash('warning')) : ?>
        <div class="alert alert-warning alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Hai!</h4>
            <?= Yii::$app->session->getFlash('warning') ?>
        </div>
    <?php endif; ?>

    <p>
        <?php // Html::a('Update', ['update', 'id' => $model->id_timkerjamember], ['class' => 'btn btn-primary']) ?>
        <?php // Html::a('Delete', ['delete', 'id_timkerjamember' => $model->id_timkerjamember], [
        //     'class' => 'btn btn-danger',
        //     'data' => [
        //         'confirm' => 'Anda yakin ingin membatalkan membership anggota ini?<br/><strong>' . $model->penggunae->nama . '</strong> dalam ' . $model->timkerjae->nama_timkerja,
        //         'method' => 'post',
        //     ],
        // ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        // 'mode' => DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $model->timkerjae->nama_timkerja . ' | Anggota',
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'hAlign' => 'left',
        'buttons1' => '',
        'attributes' => [
            'id_timkerjamember',
            // 'timkerja',
            [
                'attribute' => 'timkerja',
                'value' => $model->timkerjae->nama_timkerja,
            ],
            // 'anggota',
            [
                'attribute' => 'anggota',
                'value' => $model->penggunae->gelar_depan . ' '.$model->penggunae->nama . ', '. $model->penggunae->gelar_belakang,
            ],
            [
                'attribute' => 'is_ketua',
                'value' => ($model->is_ketua)? 'KETUA': '-',
            ],
            // 'is_ketua',
            // 'is_member',
        ],
    ]) ?>

</div>