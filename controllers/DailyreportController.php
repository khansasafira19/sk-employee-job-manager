<?php

namespace app\controllers;

use app\models\Dailyreport;
use app\models\DailyreportCari;
use app\models\Timkerjamember;
use app\models\Timkerjaproject;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * DailyreportController implements the CRUD actions for Dailyreport model.
 */
class DailyreportController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'findModel', 'delete', 'tampildelegasi', 'duplikasi'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        /* Untuk pj fungsi dan superadmin. */
                        'actions' => ['approval', 'bulkapprove', 'bulkreject', 'lintastim', 'izinlintastim', 'batalrequest'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return !\Yii::$app->user->isGuest && (\Yii::$app->user->identity->levelsuperadmin === true || \Yii::$app->user->identity->levelketuatim === true);
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
     * Lists all Dailyreport models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DailyreportCari();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['owner' => Yii::$app->user->identity->username])
            ->andWhere(['is', 'assigned_to', new \yii\db\Expression('null')])
            ->andWhere('deleted = 0')
            ->andWhere('(tanggal_kerja = DATE(NOW()) AND status_selesai = 0) OR (tanggal_kerja < DATE(NOW()) AND status_selesai = 0)')
            ->orderBy([
                'status_selesai' => SORT_ASC,
                'tanggal_kerja' => SORT_ASC,
                'priority' => SORT_DESC,
                'assigned_to' => SORT_DESC,
            ]);
        $dataProvider->pagination->pageSize = 20;
        $dataProviderRencana = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderRencana->query->where(['owner' => Yii::$app->user->identity->username])
            ->andWhere(['is', 'assigned_to', new \yii\db\Expression('null')])
            ->andWhere('deleted = 0')
            ->andWhere('tanggal_kerja > DATE(NOW())')
            ->orderBy([
                'status_selesai' => SORT_ASC,
                'priority' => SORT_DESC,
                'tanggal_kerja' => SORT_DESC,
                'assigned_to' => SORT_DESC,
            ]);
        $dataProviderRencana->pagination->pageSize = 20;

        $dataProviderSelesai = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderSelesai->query->where(['owner' => Yii::$app->user->identity->username])
            ->andWhere(['is', 'assigned_to', new \yii\db\Expression('null')])
            ->andWhere('deleted = 0')
            ->andWhere('tanggal_kerja < DATE(NOW()) AND status_selesai = 1')
            ->orderBy([
                'status_selesai' => SORT_ASC,
                'priority' => SORT_DESC,
                'tanggal_kerja' => SORT_DESC,
                'assigned_to' => SORT_DESC,
            ]);
        $dataProviderSelesai->pagination->pageSize = 20;

        $dataProviderDelegasi = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderDelegasi->query->where(['assigned_to' => Yii::$app->user->identity->username])
            ->andWhere('deleted = 0')
            ->orderBy([
                'status_selesai' => SORT_ASC,
                'priority' => SORT_DESC,
                'tanggal_kerja' => SORT_DESC,
                'assigned_to' => SORT_DESC,
            ]);
        $dataProviderDelegasi->pagination->pageSize = 20;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderDelegasi' => $dataProviderDelegasi,
            'dataProviderRencana' => $dataProviderRencana,
            'dataProviderSelesai' => $dataProviderSelesai
        ]);
    }

    /**
     * Displays a single Dailyreport model.
     * @param int $id_keg Id Keg
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
     * Creates a new Dailyreport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($date)
    {
        $model = new Dailyreport();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', "Data berhasil ditambahkan.<br/> Terima kasih..");
                return $this->redirect(['view', 'id' => $model->id_keg]);
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
     * Updates an existing Dailyreport model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_keg Id Keg
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (($model->owner != Yii::$app->user->identity->username && Yii::$app->user->identity->levelsuperadmin == false)
            && ($model->assigned_to != Yii::$app->user->identity->username && Yii::$app->user->identity->levelsuperadmin == false)
        ) {
            Yii::$app->session->setFlash('warning', "Maaf, Anda tidak diperbolehkan mengubah data kegiatan pegawai lain.");
            return $this->redirect(['index']);
        }

        if ($model->is_setujuketuatim == 1) {
            Yii::$app->session->setFlash('warning', "Maaf. Data pekerjaan yang telah disetujui Ketua Tim tidak dapat diubah kembali.");
            return $this->redirect(['index']);
        }

        if ($model->load(Yii::$app->request->post())) {
            $affected_rows = Dailyreport::updateAll(['timestamp_lastupdated' => date('Y-m-d H:i:s')], 'id_keg = "' . $id . '"');
            if ($model->save() && $affected_rows != 0) {
                Yii::$app->session->setFlash('success', "Data berhasil diupdate.<br/> Terima kasih..");
                return $this->redirect(['view', 'id' => $model->id_keg]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Dailyreport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_keg Id Keg
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $affected_rows = Dailyreport::updateAll(['deleted' => 1, 'timestamp_lastupdated' => date("Y-m-d H:i:s")], 'id_keg = "' . $id . '"');
        if ($affected_rows == 0) {
            Yii::$app->session->setFlash('success', "Gagal.");
            return $this->redirect('view', [
                'id' => $id,
                'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('success', "Kegiatan berhasil dihapus. Terima kasih.");
            return $this->redirect(['index']);
        }
    }
    /**
     * Finds the Dailyreport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_keg Id Keg
     * @return Dailyreport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_keg)
    {
        if (($model = Dailyreport::findOne(['id_keg' => $id_keg])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionTampildelegasi()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];


            if ($parents != null) {
                $cat_id = $parents[0];
                $timkerja = Timkerjaproject::find()
                    ->select('timkerja')
                    ->where('id_project = ' . $cat_id)
                    ->one();
                $member = Timkerjamember::find()
                    ->select('anggota')
                    ->joinWith('penggunae')
                    ->where(['timkerja' => $timkerja])
                    ->andWhere('is_member = 1')
                    ->asArray()
                    ->all();
                $jum = count($member);
                for ($i = 0; $i < $jum; $i++) {
                    $out[] = ['id' => $member[$i]['anggota'], 'name' => $member[$i]['penggunae']['nama']];
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                print_r($jum);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionApproval()
    {
        $searchModel = new DailyreportCari();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('deleted = 0')
            ->andWhere('status_selesai = 1')
            ->andWhere(['or', 'is_setujuketuatim = 0', ['is', 'is_setujuketuatim', new \yii\db\Expression('null')]]);
        $dataProvider->query->andWhere(['owner' => Yii::$app->user->identity->username])
            ->andWhere(['not', ['assigned_to' => null]])
            ->orderBy([
                'status_selesai' => SORT_ASC,
                'priority' => SORT_DESC,
                'tanggal_kerja' => SORT_DESC,
                'assigned_to' => SORT_DESC,
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

                $affected_rows = Dailyreport::updateAll(['is_setujuketuatim' => 1, 'timestamp_lastupdated' => date("Y-m-d H:i:s")], 'id_keg = "' . $value . '"');
                if ($affected_rows == 0) {
                    $cek = false;
                }
            }
            if ($cek = true) {
                Yii::$app->session->setFlash('success', "Approval laporan berhasil dilakukan. Terima kasih.");
                return $this->redirect(['approval']);
            } else {
                Yii::$app->session->setFlash('warning', "Approval laporan gagal dilakukan, terjadi kesalahan.");
                return $this->redirect(['approval']);
            }
        } else {
            Yii::$app->session->setFlash('warning', "Approval laporan gagal dilakukan, terjadi kesalahan.");
            return $this->redirect(['approval']);
        }
    }

    public function actionBulkreject()
    {
        $id_approve = Yii::$app->request->post()["keylist"];

        if (sizeof($id_approve) > 0) {
            $cek = true;
            foreach ($id_approve as $key => $value) {
                $affected_rows = Dailyreport::updateAll(['is_setujuketuatim' => 0, 'timestamp_lastupdated' => date("Y-m-d H:i:s")], 'id_keg = "' . $value . '"');
                if ($affected_rows == 0) {
                    $cek = false;
                }
            }
            if ($cek = true) {
                Yii::$app->session->setFlash('success', "Penolakan laporan berhasil dilakukan. Terima kasih.");
                return $this->redirect(['approval']);
            } else {
                Yii::$app->session->setFlash('warning', "Penolakan laporan gagal dilakukan, terjadi kesalahan.");
                return $this->redirect(['approval']);
            }
        } else {
            Yii::$app->session->setFlash('warning', "Penolakan laporan gagal dilakukan, terjadi kesalahan.");
            return $this->redirect(['approval']);
        }
    }

    public function actionLintastim()
    {
        $searchModel = new DailyreportCari();
        $dataProvider = $searchModel->searchLintastim(Yii::$app->request->queryParams);
        $listtimketua = Timkerjamember::find()
            ->select('*')
            ->joinWith('timkerjae')
            ->where('anggota = "' . Yii::$app->user->identity->username . '"')
            ->andWhere('is_ketua = 1')
            ->andWhere('tahun = "' . date("Y") . '"')
            ->asArray()->all();
        $itemsketua = [];
        foreach ($listtimketua as $value) {
            array_push($itemsketua, $value['timkerja']);
        }
        $listtimtrimketua = trim(json_encode($itemsketua), '[]');
        $dataProvider->query->where('deleted = 0')
            ->andWhere('lintas_tim = 1')
            ->andWhere(['not', ['owner' => Yii::$app->user->identity->username]])
            ->andWhere('timkerja IN ' . str_replace($listtimtrimketua, "($listtimtrimketua)", $listtimtrimketua))
            ->orderBy([
                'tanggal_kerja' => SORT_DESC,
                'timkerja' => SORT_ASC,
                'owner' => SORT_ASC,
                'assigned_to' => SORT_ASC,
            ]);
        $dataProvider->pagination->pageSize = 20;

        $dataProviderAnda = $searchModel->searchLintastim(Yii::$app->request->queryParams);
        $dataProviderAnda->query->where('deleted = 0')
            ->andWhere('lintas_tim = 1')
            ->andWhere('owner = "' . Yii::$app->user->identity->username . '"')
            ->orderBy([
                'tanggal_kerja' => SORT_DESC,
                'timkerja' => SORT_ASC,
                'owner' => SORT_ASC,
                'assigned_to' => SORT_ASC,
            ]);
        $dataProviderAnda->pagination->pageSize = 20;

        return $this->render('lintastim', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderAnda' => $dataProviderAnda,
        ]);
    }

    public function actionBatalrequest($id)
    {
        $affected_rows = Dailyreport::updateAll(['lintas_tim' => 2, 'timestamp_lastupdated' => date("Y-m-d H:i:s")], 'id_keg = "' . $id . '"');
        if ($affected_rows == 0) {
            Yii::$app->session->setFlash('success', "Gagal.");
        } else {
            Yii::$app->session->setFlash('success', "Request berhasil dibatalkan. Terima kasih.");
        }
        return $this->redirect(['lintastim']);
    }

    public function actionIzinlintastim($id, $izin)
    {
        $affected_rows = Dailyreport::updateAll(['is_izinlintastim' => $izin,  'timestamp_lastupdated' => date("Y-m-d H:i:s")], 'id_keg = "' . $id . '"');
        if ($affected_rows == 0) {
            Yii::$app->session->setFlash('success', "Gagal.");
        } else {
            Yii::$app->session->setFlash('success', "Request berhasil ditolak/setujui. Terima kasih.");
        }
        return $this->redirect(['lintastim']);
    }

    public function actionDuplikasi($id)
    {
        $model = new Dailyreport();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', "Data berhasil ditambahkan.<br/> Terima kasih..");
                return $this->redirect(['view', 'id' => $model->id_keg]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $duplikat = Dailyreport::findOne($id);

        if (($duplikat->owner != Yii::$app->user->identity->username || $duplikat->assigned_to != NULL) && Yii::$app->user->identity->levelsuperadmin == false) {
            Yii::$app->session->setFlash('warning', "Maaf, Anda tidak diperbolehkan menduplikasi kegiatan selain kegiatan pribadi Anda (non delegasi).");
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('duplikasi', [
                'model' => $model,
                'duplikat' => $duplikat
            ]);
        } else {
            return $this->render('duplikasi', [
                'model' => $model,
                'duplikat' => $duplikat
            ]);
        }
    }
}
