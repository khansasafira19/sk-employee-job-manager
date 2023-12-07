<?php

namespace app\controllers;

use app\models\Dailyreport;
use app\models\Levelpengguna;
use app\models\LevelpenggunaCari;
use Yii;
use app\models\Pengguna;
use app\models\Penggunaapprover;
use app\models\PenggunaCari;
use app\models\Timkerjamember;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\UploadedFile;
use Exception;

/**
 * PenggunaController implements the CRUD actions for Pengguna model.
 */
class PenggunaController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        /* Untuk guests */
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        /* Untuk user yang login. */
                        'actions' => ['ubahpassword', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        /* Untuk pj fungsi dan superadmin. */
                        'actions' => ['index', 'create', 'delete',  'update', 'findModel', 'createlevel', 'indexlevel'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return !\Yii::$app->user->isGuest && (\Yii::$app->user->identity->levelsuperadmin === true || \Yii::$app->user->identity->leveladmin === true);
                        },
                    ],
                    [
                        /* Untuk SUPER admin. */
                        'actions' => ['approverevokelevel', 'verifikasilevel', 'tampilsubfungsi'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            //return !\Yii::$app->user->isGuest && \Yii::$app->user->identity->role === 'editor';
                            return !\Yii::$app->user->isGuest && \Yii::$app->user->identity->levelsuperadmin === true;
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Pengguna models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PenggunaCari();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('status_pengguna = 1');
        $dataProvider->pagination->pageSize = 20;

        $dataProviderNonAktif = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderNonAktif->query->andWhere('status_pengguna = 0');
        $dataProviderNonAktif->pagination->pageSize = 20;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderNonAktif' => $dataProviderNonAktif,
        ]);
    }

    /**
     * Displays a single Pengguna model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * Creates a new Pengguna model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $model = new Pengguna();
        $modellevel = new Levelpengguna();
        if ($model->load(Yii::$app->request->post())) {
            $model->nama = ucwords($_POST['Pengguna']['nama']);
            $model->fungsi_pengguna = $_POST['Pengguna']['fungsi_pengguna'];
            if (isset($_POST['Pengguna']['subfungsi_pengguna']))
                $model->subfungsi_pengguna = $_POST['Pengguna']['subfungsi_pengguna'];
            $model->satker = $_POST['Pengguna']['satker'];
            $model->gelar_belakang = $_POST['Pengguna']['gelar_belakang'];
            $model->approved_ckp_by = $_POST['Pengguna']['approved_ckp_by'];
            $model->foto = $_POST['Pengguna']['nip'] . '-' . $_POST['Pengguna']['username'] . '.jpg';
            $model->filefoto =  \fv\yii\croppie\UploadedFile::getInstance($model, 'filefoto');
            $path = Yii::$app->params['uploadPath'] . '/' . $model->foto;
            if ($model->save()) {
                $modellevel->username = strtolower($_POST['Pengguna']['username']);
                $modellevel->level = 5; //level pegawai
                $modellevel->autentikasi = 1;
                $modellevel->save();
                if (isset($model->filefoto))
                    $model->filefoto->saveAs($path);
                Yii::$app->session->setFlash('success', "Data berhasil direkam.<br/> Terima kasih..");
                return $this->redirect(['pengguna/index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing Pengguna model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->user->identity->levelsuperadmin == false && $model->fungsi_pengguna != Yii::$app->user->identity->fungsi_pengguna) {
            Yii::$app->session->setFlash('error', "Maaf. Anda hanya diperbolehkan mengubah data Fungsi Anda sendiri.");
            return $this->redirect(['site/index']);
        }
        Yii::$app->params['uploadPath'] = Yii::getAlias("@app") . '/images/foto_pegawai';
        if ($model->load(Yii::$app->request->post())) {
            if ($_POST['Pengguna']['satker'] != $model->satker) {
                $cektugas = Dailyreport::find()
                    ->select('*')
                    ->where(['and', 'owner = "' . $model->username.'"', 'status_selesai = 0'])
                    ->orWhere(['and', 'assigned_to = "' . $model->username.'"', 'is_setujuketuatim = 0'])
                    ->count();
                if ($cektugas > 0) {
                    Yii::$app->session->setFlash('warning', "Maaf. Satker pengguna tidak dapat diganti jika masih ada tugas harian yang belum selesai.<br/> Terima kasih..");
                    return $this->redirect(['index']);
                }

                $cektim = Timkerjamember::find()
                    ->select('*')
                    ->joinWith('timkerjae')
                    ->where('anggota = "' . $model->username.'"')
                    ->andWhere(['or', 'is_member = 1', 'is_ketua = 1'])
                    ->count();
                if ($cektim > 0) {
                    Yii::$app->session->setFlash('warning', "Maaf. Satker pengguna tidak dapat diganti jika pengguna terkait belum dikeluarkan dari tim satker asalnya.<br/> Terima kasih..");
                    return $this->redirect(['index']);
                }
            }
            $model->satker = $_POST['Pengguna']['satker'];
            $model->fungsi_pengguna = $_POST['Pengguna']['fungsi_pengguna'];            
            $model->filefoto =  \fv\yii\croppie\UploadedFile::getInstance($model, 'filefoto');
            $path = Yii::$app->params['uploadPath'] . '/' . $model->foto;
            if (isset($_POST['Pengguna']['subfungsi_pengguna']))
                $model->subfungsi_pengguna = $_POST['Pengguna']['subfungsi_pengguna'];
            if ($model->save()) {
                if (isset($model->filefoto))
                    $model->filefoto->saveAs($path);
                Yii::$app->session->setFlash('success', "Data berhasil direkam.<br/> Terima kasih..");
                return $this->redirect(['view', 'id' => $model->username]);
            }
        }


        return $this->render('update', [
            'model' => $model,            
        ]);
    }

    /**
     * Deletes an existing Pengguna model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $affected_rows = Pengguna::updateAll(['status_pengguna' => 0], 'username = "' . $id . '"');
        if ($affected_rows == 0) {
            Yii::$app->session->setFlash('success', "Gagal.");
            return $this->redirect('view', [
                'id' => $id,
                'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('success', "Pengguna berhasil di-nonaktifkan. Terima kasih.");
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Pengguna model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Pengguna the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pengguna::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionView($id)
    {
        $level = Levelpengguna::find()->select('*, level.nama_level as namalevel')->joinWith('levele')->where(['username' => $id])->andWhere(['autentikasi' => 1])->orderBy('level')->all();
        $items = [];
        foreach ($level as $value) {
            array_push($items, $value['namalevel']);
        }
        if (!empty($items))
            $levels =   "<p>+ " . implode("<br/>+ ", $items) . "</p>";
        else
            $levels = '-';

        $isapprover = Penggunaapprover::find()->where(['approver' => $id])->andWhere(['autentikasi' => 1])->one();
        $lists = [];
        if (!empty($isapprover)) {
            $ckp = Pengguna::find()->select('*')->joinWith('penggunaapprovere')->where(['approved_ckp_by' => $isapprover->id_approver])->orderBy('pangkatgol')->all();

            foreach ($ckp as $value) {
                array_push($lists, $value['gelar_depan'] . $value['nama'] . ', ' . $value['gelar_belakang']);
            }
        }
        if (!empty($lists))
            $ckps =   "<p>+ " . implode("<br/>+ ", $lists) . "</p>";
        else
            $ckps = '-';


        $model = $this->findModel($id);

        if (Yii::$app->user->identity->levelpegawai == true && $id != Yii::$app->user->identity->username) {
            Yii::$app->session->setFlash('warning', "Maaf. Anda hanya diperbolehkan melihat data Anda sendiri.");
            return $this->redirect(['site/index']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model,
                'levels' => $levels,
                'ckps' => $ckps
            ]);
        } else {
            return $this->render('view', [
                'model' => $model,
                'levels' => $levels,
                'ckps' => $ckps
            ]);
        }
    }

    public function actionUbahpassword($id)
    {
        $model = new \app\models\UbahPasswordForm();
        $pengguna = Pengguna::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->attributes = $_POST['UbahPasswordForm'];

            Yii::$app->db->createCommand()
                ->update('pengguna', ['password' => md5($_POST['UbahPasswordForm']['password_baru'])], 'username = "' . $id . '"')
                ->execute();
            Yii::$app->session->setFlash('success', "Password berhasil diubah. Terima kasih.");
            return $this->redirect(['index']);
        }

        return $this->render('ubahpassword', [
            'model' => $model,
        ]);
    }
    public function actionIndexlevel()
    {
        $searchModel = new PenggunaCari();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreatelevel($username)
    {
        $modelpengguna = $this->findModel($username);
        $model = new Levelpengguna();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Level berhasil ditambah. Terima kasih.");
            return $this->redirect(['view', 'id' => $username]);
        }

        return $this->render('createlevel', [
            'model' => $model,
            'usernamepengguna' => $modelpengguna->username,
            'namapengguna' => $modelpengguna->nama
        ]);
    }

    public function actionVerifikasilevel()
    {
        $searchModel = new LevelpenggunaCari();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['<>', 'username', 'admin']); //admin is excluded
        $dataProvider->sort->defaultOrder = ['level' => SORT_ASC];
        $dataProvider->pagination = false;

        return $this->render('verifikasilevel', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionApproverevokelevel($id)
    {
        $model = Levelpengguna::findOne($id);
        $a = $model->username;
        $b = Levelpengguna::find()->where(['username' => $a, 'autentikasi' => 1])->count();


        if ($model->autentikasi == '0') {
            if ($b >= 3) {
                Yii::$app->session->setFlash('warning', "Pengguna sudah memiliki tiga atau lebih level terautentikasi. Jumlah saat ini: " . $b);
                return $this->redirect(['verifikasilevel']);
            } else
                $affected_rows = Levelpengguna::updateAll(['autentikasi' => 1], 'id_levelpengguna = "' . $id . '"');
        } elseif ($model->autentikasi == '1')
            $affected_rows = Levelpengguna::updateAll(['autentikasi' => 0], 'id_levelpengguna = "' . $id . '"');
        if ($affected_rows == 0) {
            Yii::$app->session->setFlash('success', "Gagal.");
            return $this->redirect('verifikasilevel', []);
        } else {
            Yii::$app->session->setFlash('success', "Level pengguna berhasil di-approve/revoke. Terima kasih.");
            return $this->redirect(['verifikasilevel']);
        }
    }

    public function actionTampilsubfungsi()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $cat_id = $parents[0];
                $jum = \app\models\Penggunasubfungsi::find()
                    ->select('id_subfungsi, nama_subfungsi')
                    ->where(['id_fungsi' => $cat_id])
                    ->distinct()
                    ->count();
                $kabupaten = \app\models\Penggunasubfungsi::find()
                    ->where(['id_fungsi' => $cat_id])
                    ->distinct()
                    ->orderBy('id_subfungsi')
                    ->all();
                for ($i = 0; $i < $jum; $i++) {
                    $out[] = ['id' => $kabupaten[$i]['id_subfungsi'], 'name' => $kabupaten[$i]['nama_subfungsi']];
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }
}
