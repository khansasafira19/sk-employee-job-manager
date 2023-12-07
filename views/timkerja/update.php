<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Timkerja */

$this->title = 'Update Tim Kerja # ' . $model->id_timkerja;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Tim Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_timkerja, 'url' => ['view', 'id_timkerja' => $model->id_timkerja]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="timkerja-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
