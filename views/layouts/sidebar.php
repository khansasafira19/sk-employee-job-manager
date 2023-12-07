<?php

use yii\helpers\Html;
?>
<?php
$theme = Yii::$app->user->identity->theme;
if ($theme == 0)
    $themechoice = 'sidebar-light-green';
else
    $themechoice = 'sidebar-dark-green';
?>
<aside class="main-sidebar elevation-4 <?php echo $themechoice; ?>">
    <!-- Brand Logo -->
    <a href="<?= \yii\helpers\Url::home() . 'site/index' ?>" class="brand-link">
        <img src="<?php echo Yii::$app->request->baseUrl; ?>\images\favicon.png" alt="Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= Html::encode(Yii::$app->name) ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">

            <?php
            $identity = Yii::$app->user->identity;
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    (!Yii::$app->user->isGuest) ?
                        ['label' => 'Beranda', 'url' => ['site/index'], 'icon' => 'home'] : (['label' => false, 'visible' => false]),

                    (!Yii::$app->user->isGuest) ?
                        [
                            'label' => 'Laporan Harian',
                            'url' => ['dailyreport/index'],
                            'icon' => 'edit',
                            'active' => Yii::$app->controller->id == 'dailyreport'
                        ] : (['label' => false, 'visible' => false]),

                    (!Yii::$app->user->isGuest) ?
                        [
                            'label' => 'Presensi',
                            'url' => ['dailypresence/index'],
                            'icon' => 'clock',
                            'active' => Yii::$app->controller->id == 'dailypresence'
                        ] : (['label' => false, 'visible' => false]),

                    (!Yii::$app->user->isGuest) ?
                        [
                            'label' => 'Tim Kerja',
                            'url' => ['timkerjamember/index'],
                            'icon' => 'people-carry',
                            'active' => Yii::$app->controller->id == 'timkerjamember' || Yii::$app->controller->id == 'timkerja'
                        ] : (['label' => false, 'visible' => false]),

                    (!Yii::$app->user->isGuest && ($identity->levelsuperadmin || $identity->levelpimpinan)) ?
                        [
                            'label' => 'Ringkasan Eksekutif',
                            'url' => ['/executive/index'],
                            'icon' => 'project-diagram',
                            'active' => Yii::$app->controller->id == 'executive'
                        ] : (['label' => false, 'visible' => false]),

                    (!Yii::$app->user->isGuest && ($identity->levelsuperadmin || $identity->leveladmin || $identity->levelpimpinan)) ?
                        [
                            'label' => 'Pegawai',
                            'url' => ['/pengguna/index'],
                            'icon' => 'users',
                            'active' => Yii::$app->controller->id == 'pengguna'
                        ] : (['label' => false, 'visible' => false]),
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>