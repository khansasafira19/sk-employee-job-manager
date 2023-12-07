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
use app\sso\SSOBPS;
use app\models\User;
use app\models\Pengguna;
use app\models\ThemeForm;
use app\models\Timkerjaproject;
use app\models\TimkerjaprojectCari;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'contact', 'about', 'logout', 'theme', 'dp_markselesai', 'dp_markpriority'], // add all actions to take guest to login page
                        'allow' => true,
                        'roles' => ['@'],
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
        $presensi = Dailypresence::find()->select('*')->where(['pegawai' => Yii::$app->user->identity->username])->andWhere(['tanggal' => date("Y-m-d")])->one();

        $progress = 0;

        $dataProvider = new ActiveDataProvider([
            'query' => Dailyreport::find()->select('*')->joinWith('ownere')
                ->where(['assigned_to' => Yii::$app->user->identity->username])
                ->andWhere(['status_selesai' => 0])->orderBy([
                    'priority' => SORT_DESC,
                    'timestamp_lastupdated' => SORT_DESC
                ]),
            'pagination' => [
                'pageSize' => 1,
            ],
        ]);

        $searchModelNonAssign = new DailyreportCari();
        $dataProviderNonAssign = $searchModelNonAssign->searchIndex(Yii::$app->request->queryParams);
        $dataProviderNonAssign->query->andWhere('tanggal_kerja = DATE(NOW())');

        /*Untuk Kalender*/
        $events = Dailyreport::find()
            ->joinWith(['timkerjaprojecte', 'timkerjae'])
            ->where(['assigned_to' => Yii::$app->user->identity->username])
            ->orWhere(['owner' => Yii::$app->user->identity->username])
            ->all();
        $tasks = [];
        foreach ($events as $eve) {
            $event = new \yii2fullcalendar\models\Event();
            $event->id = $eve->id_keg;
            if ($eve->timkerjaproject != NULL)
                $event->title = "#" . $eve->timkerjaprojecte->project_name;
            else
                $event->title = '#Mandiri';
            $event->start = $eve->tanggal_kerja;
            $event->end = $eve->tanggal_kerja;
            $tasks[] = $event;
        }

        return $this->render('index', [
            'presensi' => $presensi,
            'progress' => $progress,
            'listDataProvider' => $dataProvider,
            'listDataProviderNonAssign' => $dataProviderNonAssign,
            'searchModelNonAssign' => $searchModelNonAssign,
            'listdataProviderKalender' => $dataProviderNonAssign,
            'eventsKalender' => $tasks,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'main-login-kiri';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionTheme($choice)
    {
        if ($choice == 0)
            $affected_rows = Pengguna::updateAll(['theme' => 0], 'username = "' . Yii::$app->user->identity->username . '"');
        else
            $affected_rows = Pengguna::updateAll(['theme' => 1], 'username = "' . Yii::$app->user->identity->username . '"');

        if ($affected_rows == 0)
            return $this->redirect('index');
        else
            return $this->redirect('index');
    }

    public function actionDp_markselesai()
    {
        $id_selesai = Yii::$app->request->post()["keylist"];
        $success = true;
        foreach ($id_selesai as $id) {
            $affected_rows = Dailyreport::updateAll(['status_selesai' => 1], 'id_keg = "' . $id . '"');
            if ($affected_rows == 0)
                $success = false;
        }

        if ($success == false) {
            Yii::$app->session->setFlash('warning', "Perintah gagal dieksekusi.");
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('warning', "Pekerjaan telah ditandai selesai. Terima kasih.");
            return $this->redirect(['index']);
        }
    }
    public function actionDp_markpriority($id, $value)
    {
        $affected_rows = Dailyreport::updateAll(['priority' => $value], 'id_keg = "' . $id . '"');
        if ($affected_rows == 0)
            return $this->redirect(['index']);
        else
            return $this->redirect(['index']);
    }
}
