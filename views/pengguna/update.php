<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pengguna */

$this->title = 'Update Pengguna: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Pengguna', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->username]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pengguna-update">
    <?php
    $email = substr($model->email, -10);
    ?>

    <?= $this->render('_form', [
        'model' => $model,
        'profil' => $profil,
        'modelusername' => $modelusername,
        'ada' => $ada,
        'namasat' => $namasat,
        'key' => $key,
        'bengkulu' => $bengkulu
    ]) ?>
</div>