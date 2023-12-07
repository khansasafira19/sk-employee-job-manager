<?php

namespace app\controllers;

use app\models\Ckp;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Dailypresence;
use app\models\Dailyreport;
use app\models\DailyreportCari;
use app\models\Eombulananpegawai;
use app\sso\SSOBPS;
use app\models\User;
use app\models\Pengguna;
use app\models\ThemeForm;
use app\models\Timkerjaproject;
use app\models\TimkerjaprojectCari;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use kartik\mpdf\Pdf;
use Dompdf\Dompdf; //untuk di webapps
use Dompdf\Options;

class ExecutiveController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'createpdf'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return !\Yii::$app->user->isGuest && (\Yii::$app->user->identity->levelsuperadmin === true || \Yii::$app->user->identity->levelpimpinan === true);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        include_once('_contentindex.php');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderFuture' => $dataProviderFuture,
            'dataProviderFinished' => $dataProviderFinished,
            'progress' => $progress,
            'totaltarget' => $totaltarget,
            'seriesselesai' => $seriesselesai,
            'seriestarget' => $seriestarget,
            'seriestanggal' => $seriestanggal
        ]);
    }

    public function actionCreatepdf()
    {
        include_once('_contentindex.php');

        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $options->set('isRemoteEnabled', TRUE);
        $options->set('debugKeepTemp', TRUE);
        $options->set('isHtml5ParserEnabled', TRUE);
        $options->set('isJavascriptEnabled', TRUE);

        $dompdf = new DOMPDF($options);

        $html = $this->renderPartial('cetakpdf', [
            'dataProvider' => $dataProvider,
            'dataProviderFuture' => $dataProviderFuture,
            'dataProviderFinished' => $dataProviderFinished,
            'progress' => $progress,
            'totaltarget' => $totaltarget,
            'seriesselesai' => $seriesselesai,
            'seriestarget' => $seriestarget,
            'seriestanggal' => $seriestanggal
        ]);
        $html .= '<style>' . $style . '</style>';
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();
        ob_end_clean();
        $dompdf->stream("Ringkasan Eksekutif SK-EJM.pdf", array("Attachment" => 0));
    }
}
