<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Timkerjamember */

$this->title = 'Update Anggota Tim Kerja # ' . $model->id_timkerjamember;
$this->params['breadcrumbs'][] = ['label' => 'Rekap Tim Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_timkerjamember, 'url' => ['view', 'id_timkerjamember' => $model->id_timkerjamember]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="timkerjamember-update">

    <?= $this->render('_form', [
        'model' => $model,
        'jumlahtim' => $jumlahtim,
    ]) ?>

</div>
