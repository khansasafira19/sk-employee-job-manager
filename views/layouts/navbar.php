<?php

use machour\yii2\notifications\widgets\NotificationsWidget;
use yii\helpers\Html;
use kartik\switchinput\SwitchInput;
use yii\widgets\ActiveForm;

?>
<style>
    .btn-bundar {
        border-radius: 30px !important;
        width: 35px;
        padding: 6px 0px;
    }
</style>
<nav class="main-header navbar navbar-expand navbar-blue navbar-dark" style="background-image: linear-gradient(45deg, #043277 33.33%, #DC7418 33.33%, #DC7418 66.66%, #00CC83 66.66%);">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <?= Html::a('Berita', ['/site/contact'], ['class' => 'nav-link']) ?>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <?= Html::a('Manual', ['/site/about'], ['class' => 'nav-link']) ?>
        </li>        
    </ul>
    <!-- Right navbar links -->
    <?php if (!Yii::$app->user->isGuest) : ?>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <?php
                if (Yii::$app->user->identity->theme == 1) {
                    echo Html::a('<i class="icon fa fa-moon"></i>', ['/site/theme?choice=0'], ['class' => 'btn btn-dark btn-bundar btn-lg']);
                } else {
                    echo Html::a('<i class="icon fa fa-sun"></i>', ['/site/theme?choice=1'], ['class' => 'btn btn-default btn-bundar btn-lg']);
                }
                ?>
            </li>
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="<?php echo Yii::$app->request->baseUrl . '/images/foto_pegawai/' .  Yii::$app->user->identity->foto; ?>" class="user-image img-circle elevation-2" alt="User Image">
                    <span class="d-none d-md-inline"><?php echo Yii::$app->user->identity->nama ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <li class="user-header bg-secondary">
                        <img src="<?php echo Yii::$app->request->baseUrl . '/images/foto_pegawai/' . Yii::$app->user->identity->foto;
                                    ?>" class="img-circle elevation-2" alt="User Image">
                        <p>
                            <?php echo Yii::$app->user->identity->nama
                            ?>

                        </p>
                    </li>
                    <li class="user-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <?= Html::a('Profil', ['/pengguna/view?id=' . Yii::$app->user->identity->username], ['class' => 'btn btn-secondary bundar']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= Html::a('Logout', ['/site/logout'], ['data-method' => 'post', 'class' => 'btn btn-info bundar float-right']) ?>
                            </div>
                        </div>


                    </li>
                </ul>
            </li>
        </ul>
    <?php else : ?>
        <ul class="navbar-nav ml-auto">

            <li class="nav-item">
                <?= Html::a('Login', ['/site/login'], ['class' => 'nav-link']) ?>
            </li>
        </ul>
    <?php endif; ?>
</nav>