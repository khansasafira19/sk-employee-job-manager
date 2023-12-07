<?php

use yii\helpers\Html;
// use yii\widgets\DetailView;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Timkerja */

$this->title = $model->nama_timkerja;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Tim Kerja', 'url' => ['index']];
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
        <?php // Html::a('Update', ['update', 'id' => $model->id_timkerja], ['class' => 'btn btn-primary']) ?>
        <?php // Html::a('Delete', ['delete', 'id' => $model->id_timkerja], [
        //     'class' => 'btn btn-danger',
        //     'data' => [
        //         'confirm' => 'Anda yakin ingin menghapus tim ini dari sistem?<br/><strong>' . $model->nama_timkerja . '</strong>',
        //         'method' => 'post',
        //     ],
        // ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'panel' => [
            'heading' => 'Rincian Data Tim',
            'type' => DetailView::TYPE_SUCCESS,
        ],
        'hAlign' => 'left',
        'buttons1' => '',
        'attributes' => [
            'id_timkerja',
            'tahun',
            [
                'attribute' => 'satker',
                'value' => $model->penggunasatkere->nama_satker,
            ],
            'nama_timkerja',
            [
                'label' => 'Anggota',
                'value' => $levels,
                'format' => 'html'
            ],
        ],
    ]) ?>

</div>