<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Timkerja */

$this->title = 'Tambah Tim Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Tim Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timkerja-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    
</div>