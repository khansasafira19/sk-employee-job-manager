<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

$this->beginPage();
\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);

$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

$theme = Yii::$app->user->identity->theme;
if ($theme == 0)
    $themechoice = '';
else
    $themechoice = 'dark-mode';

?>
<style>
    body {
        font-family: "Poppins", Helvetica, "sans-serif" !important;
        font-size: 14px!important;
        background: whitesmoke;
        color: rgba(0, 0, 0, 0.6);
        text-shadow: px 6px 4px rgba(0, 0, 0, 0.2), 0px -5px 16px rgba(255, 255, 255, 0.3);
    }
</style>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= Html::encode(Yii::$app->name) ?></title>
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>\images\favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="../library/css/fi-theme.css">
    <link rel="stylesheet" href="../library/css/bootstrap_4.6.1.min.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Poppins" />
    <script src="../library/js/jquery-3.6.0.min.js"></script>
    <script src="../library/js/bootstrap.min.js"></script>
    <script src="../library/js/Chart.min.js"></script>    

    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>

<body class="skin-blue <?php echo $themechoice; ?>">

    <?php $this->beginBody() ?>

    <div class="wrapper">
        <!-- Navbar -->
        <?= $this->render('navbar', ['assetDir' => $assetDir]) ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= $this->render('sidebar', ['assetDir' => $assetDir]) ?>

        <!-- Content Wrapper. Contains page content -->
        <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <?= $this->render('control-sidebar') ?>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <?= $this->render('footer') ?>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
<script>
    // $('.kv-panel-after').remove();
    $('.card-footer').find('.kv-panel-pager').parent().remove();
</script>