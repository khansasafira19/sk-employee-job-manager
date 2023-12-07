<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Timkerjamember */

$this->title = 'Tambah Anggota Tim Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Tim Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timkerjamember-create">

    <?= $this->render('_form', [
        'model' => $model,
        'jumlahtim'=> $jumlahtim,
    ]) ?>

</div>
