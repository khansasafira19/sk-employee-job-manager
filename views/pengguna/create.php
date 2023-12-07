<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pengguna */

$this->title = 'Pendaftaran Pengguna';
$this->params['breadcrumbs'][] = ['label' => 'Pengguna', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <?=
    $this->render('_form', [
        'model' => $model,
        'profil' => $profil,
        'modelusername' => $modelusername,
        'ada' => $ada,
        'namasat' => $namasat,
        'key' => $key,
        'bengkulu' => $bengkulu
    ])
    ?>
