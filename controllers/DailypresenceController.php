<?php

namespace app\controllers;

use app\models\Dailypresence;
use app\models\DailypresenceCari;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DailypresenceController implements the CRUD actions for Dailypresence model.
 */
class DailypresenceController extends Controller
{
    /**
     * @inheritDoc
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
                        /* Untuk user yang login. */
                        'actions' => ['index', 'view', 'create', 'update', 'findModel', 'delete', 'createcuti', 'createdl'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        /* Untuk pj fungsi dan superadmin. */
                        'actions' => ['approval', 'bulkapprove', 'bulkreject'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return !\Yii::$app->user->isGuest && (\Yii::$app->user->identity->levelsuperadmin === true || \Yii::$app->user->identity->leveladmintu === true);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Dailypresence models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DailypresenceCari();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('pegawai = "' . Yii::$app->user->identity->username . '"');
        $dataProvider->query->andWhere('MONTH(tanggal) = ' . date("m"));
        $dataProvider->query->andWhere('deleted = 0');
        $dataProvider->pagination->pageSize = 20;

        $dataProviderSelesai = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderSelesai->query->andWhere('pegawai = "' . Yii::$app->user->identity->username . '"');
        $dataProviderSelesai->query->andWhere('tanggal < "' . date('Y-m-01') . '"');
        $dataProviderSelesai->query->andWhere('deleted = 0');
        $dataProviderSelesai->pagination->pageSize = 20;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderSelesai' => $dataProviderSelesai,
        ]);
    }

    /**
     * Displays a single Dailypresence model.
     * @param int $id_dailypresence Id Dailypresence
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Dailypresence model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($date, $from)
    {
        $model = new Dailypresence();
        if ($this->request->isPost) {
            // if ($model->load($this->request->post()) && $model->save()) {
            if ($model->load(Yii::$app->request->post())) {
                $cekduplikasi = Dailypresence::find()->select('*')->where('pegawai = "' . Yii::$app->user->identity->username . '"')->andWhere('tanggal = "' . $_POST['Dailypresence']['tanggal'] . '"')->andWhere('deleted = 0')->count();
                if ($cekduplikasi > 0) {
                    Yii::$app->session->setFlash('warning', "Maaf. Data presensi Anda pada tanggal tersebut telah terisi.<br/> Mohon perbaiki di Laman Presensi.");
                    return $this->redirect(['index']);
                }
                if ($_POST['Dailypresence']['jam_pulang'] == '00:00')
                    $model->jam_pulang = NULL;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Data berhasil ditambahkan.<br/> Terima kasih..");
                    if ($from == 'site')
                        return $this->redirect(['site/index']);
                    else
                        return $this->redirect(['view', 'id' => $model->id_dailypresence]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Dailypresence model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_dailypresence Id Dailypresence
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $from)
    {
        $model = $this->findModel($id);

        if ($model->pegawai != Yii::$app->user->identity->username && Yii::$app->user->identity->levelsuperadmin == false) {
            Yii::$app->session->setFlash('warning', "Maaf, Anda tidak diperbolehkan mengubah data presensi pegawai lain.");
            return $this->redirect(['index']);
        }

        if ($model->is_setujuadmin == 1) {
            Yii::$app->session->setFlash('warning', "Maaf. Data presensi yang telah disetujui Admin tidak dapat diubah kembali.");
            return $this->redirect(['index']);
        }

        if ($model->load(Yii::$app->request->post())) {
            $affected_rows = Dailypresence::updateAll(['timestamp_lastupdated' => date('Y-m-d H:i:s')], 'id_dailypresence = "' . $id . '"');
            if ($model->save() && $affected_rows != 0) {
                Yii::$app->session->setFlash('success', "Data berhasil diupdate.<br/> Terima kasih..");
                if ($from == 'site')
                    return $this->redirect(['site/index']);
                else
                    return $this->redirect(['view', 'id' => $model->id_dailypresence]);
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Dailypresence model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_dailypresence Id Dailypresence
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $affected_rows = Dailypresence::updateAll(['deleted' => 1, 'timestamp_lastupdated' => date("Y-m-d H:i:s")], 'id_dailypresence = "' . $id . '"');
        if ($affected_rows == 0) {
            Yii::$app->session->setFlash('success', "Gagal.");
            return $this->redirect('view', [
                'id' => $id,
                'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('success', "Presensi berhasil dihapus. Terima kasih.");
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Dailypresence model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_dailypresence Id Dailypresence
     * @return Dailypresence the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_dailypresence)
    {
        if (($model = Dailypresence::findOne(['id_dailypresence' => $id_dailypresence])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreatecuti($date)
    {
        $cekduplikasi = Dailypresence::find()->select('*')->where('pegawai = "' . Yii::$app->user->identity->username . '"')->andWhere('tanggal = "' . $date . '"')->andWhere('deleted = 0')->count();
        if ($cekduplikasi > 0) {
            Yii::$app->session->setFlash('warning', "Maaf. Data presensi Anda pada tanggal tersebut telah terisi.<br/> Mohon perbaiki di Laman Presensi.");
            return $this->redirect(['site/index']);
        }
        $model = new Dailypresence();
        $model->tanggal = $date;
        $model->pegawai = Yii::$app->user->identity->username;
        $model->status_presensi = 2;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', "Data cuti berhasil direkam.<br/> Terima kasih..");
            return $this->redirect(['site/index']);
        } else {
            Yii::$app->session->setFlash('warning', "Maaf. Data cuti gagal direkam.<br/> Mohon hubungi Admin.");
            return $this->redirect(['site/index']);
        }
    }

    public function actionCreatedl($date)
    {
        $cekduplikasi = Dailypresence::find()->select('*')->where('pegawai = "' . Yii::$app->user->identity->username . '"')->andWhere('tanggal = "' . $date . '"')->andWhere('deleted = 0')->count();
        if ($cekduplikasi > 0) {
            Yii::$app->session->setFlash('warning', "Maaf. Data presensi Anda pada tanggal tersebut telah terisi.<br/> Mohon perbaiki di Laman Presensi.");
            return $this->redirect(['site/index']);
        }
        $model = new Dailypresence();
        $model->tanggal = $date;
        $model->pegawai = Yii::$app->user->identity->username;
        $model->status_presensi = 3;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', "Data DL berhasil direkam.<br/> Terima kasih..");
            return $this->redirect(['site/index']);
        } else {
            Yii::$app->session->setFlash('warning', "Maaf. Data DL gagal direkam.<br/> Mohon hubungi Admin.");
            return $this->redirect(['site/index']);
        }
    }

    public function actionApproval()
    {
        $searchModel = new DailypresenceCari();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('deleted = 0')
            ->andWhere(['or', 'is_setujuadmin = 0', ['is', 'is_setujuadmin', new \yii\db\Expression('null')]]);
        $dataProvider->query->andWhere(['satker' => Yii::$app->user->identity->satker])
            ->orderBy([
                'tanggal' => SORT_ASC,
                'satker' => SORT_ASC,
                'pangkatgol' => SORT_DESC,
                'nip' => SORT_DESC,
            ]);
        $dataProvider->pagination->pageSize = 20;

        return $this->render('approval', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBulkapprove()
    {
        $id_approve = Yii::$app->request->post()["keylist"];

        if (sizeof($id_approve) > 0) {
            $cek = true;
            foreach ($id_approve as $key => $value) {

                $affected_rows = Dailypresence::updateAll(['is_setujuadmin' => 1, 'timestamp_lastupdated' => date("Y-m-d H:i:s")], 'id_dailypresence = "' . $value . '"');
                if ($affected_rows == 0) {
                    $cek = false;
                }
            }
            if ($cek = true) {
                Yii::$app->session->setFlash('success', "Approval presensi berhasil dilakukan. Terima kasih.");
                return $this->redirect(['approval']);
            } else {
                Yii::$app->session->setFlash('warning', "Approval presensi gagal dilakukan, terjadi kesalahan.");
                return $this->redirect(['approval']);
            }
        } else {
            Yii::$app->session->setFlash('warning', "Approval presensi gagal dilakukan, terjadi kesalahan.");
            return $this->redirect(['approval']);
        }
    }
    public function actionBulkreject()
    {
        $id_approve = Yii::$app->request->post()["keylist"];

        if (sizeof($id_approve) > 0) {
            $cek = true;
            foreach ($id_approve as $key => $value) {
                $affected_rows = Dailypresence::updateAll(['is_setujuadmin' => 0, 'timestamp_lastupdated' => date("Y-m-d H:i:s")], 'id_dailypresence = "' . $value . '"');
                if ($affected_rows == 0) {
                    $cek = false;
                }
            }
            if ($cek = true) {
                Yii::$app->session->setFlash('success', "Penolakan presensi berhasil dilakukan. Terima kasih.");
                return $this->redirect(['approval']);
            } else {
                Yii::$app->session->setFlash('warning', "Penolakan presensi gagal dilakukan, terjadi kesalahan.");
                return $this->redirect(['approval']);
            }
        } else {
            Yii::$app->session->setFlash('warning', "Penolakan presensi gagal dilakukan, terjadi kesalahan.");
            return $this->redirect(['approval']);
        }
    }
}
