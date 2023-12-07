<?php

namespace app\controllers;

use app\models\Dailyreport;
use app\models\Pengguna;
use app\models\PenggunaCari;
use app\models\Timkerja;
use app\models\Timkerjamember;
use app\models\TimkerjamemberCari;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\helpers\Json;

/**
 * TimkerjamemberController implements the CRUD actions for Timkerjamember model.
 */
class TimkerjamemberController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
                            'actions' => ['error'],
                            'allow' => true,
                        ],
                        [
                            /* Untuk user yang login. */
                            'actions' => ['index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['view', 'create', 'update', 'tampilsatker', 'tampiltimkerja', 'rekapmemberindex', 'rekapmemberviewtim', 'rekapmemberviewprojects', 'rekapmemberviewtugas'],
                            'allow' => true,
                            'matchCallback' => function ($rule, $action) {
                                return !\Yii::$app->user->isGuest && (\Yii::$app->user->identity->levelsuperadmin === true || \Yii::$app->user->identity->leveladmin === true);
                            },
                        ],
                        [
                            'actions' => ['tampilanggota'],
                            'allow' => true,
                            'matchCallback' => function ($rule, $action) {
                                return !\Yii::$app->user->isGuest && \Yii::$app->user->identity->levelsuperadmin === true;
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Timkerjamember models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TimkerjamemberCari();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Timkerjamember model.
     * @param int $id_timkerjamember Id Timkerjamember
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_timkerjamember)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_timkerjamember),
        ]);
    }

    /**
     * Creates a new Timkerjamember model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (date('n') != 1) {
            Yii::$app->session->setFlash('warning', "Anggota tim hanya dapat diinput pada bulan Januari setiap tahunnya. <br/>Terima kasih.");
            return $this->redirect(['index']);
        }
        $model = new Timkerjamember();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('warning', "Anggota berhasil ditambahkan. Terima kasih.");
                return $this->redirect(['view', 'id_timkerjamember' => $model->id_timkerjamember]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $jumlahtim = Timkerja::find()->select('*')->where('satker = ' . Yii::$app->user->identity->satker)->orderBy(['id_timkerja' => SORT_ASC])->count();        

        return $this->render('create', [
            'model' => $model,
            'jumlahtim' => $jumlahtim,
        ]);
    }

    /**
     * Updates an existing Timkerjamember model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_timkerjamember Id Timkerjamember
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $a = $model->timkerja;
            $b = $model->is_ketua;
            $data = $model->anggota;
            $c = Timkerjamember::find()->where(['timkerja' => $a])->andWhere(['is_ketua' => $b])->count();
            if ($c >= 1) {
                $d = Timkerjamember::find()->where(['timkerja' => $a])->andWhere(['is_ketua' => $b])->joinWith('penggunae')->one();
                if ($data != $d['anggota']) {
                    Yii::$app->session->setFlash('warning', 'Maaf. Tim ini sudah memiliki Ketua atas nama ' . $d['penggunae']['gelar_depan'] . ' ' . $d['penggunae']['nama'] . ', ' . $d['penggunae']['gelar_belakang']);
                    return $this->redirect(['view', 'id_timkerjamember' => $model->id_timkerjamember]);
                }

            } else
                $ubah = Timkerjamember::updateAll(['is_ketua' => $model->is_ketua], 'id_timkerjamember = "' . $id . '"');
            Yii::$app->session->setFlash('warning', "Data anggota berhasil diubah. Terima kasih.");
            return $this->redirect(['view', 'id_timkerjamember' => $model->id_timkerjamember]);
        }

        $jumlahtim = Timkerja::find()->select('*')->where('satker = ' . Yii::$app->user->identity->satker)->orderBy(['id_timkerja' => SORT_ASC])->count();

        return $this->render('update', [
            'model' => $model,
            'jumlahtim' => $jumlahtim,
        ]);
    }

    /**
     * Deletes an existing Timkerjamember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_timkerjamember Id Timkerjamember
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */    

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $affected_rows = Timkerjamember::updateAll(['is_member' => 0], 'id_timkerjamember = "' . $id . '"');
        if ($affected_rows == 0) {
            Yii::$app->session->setFlash('warning', "Perintah gagal dieksekusi. Mohon hubungi Super Admin.");
            return $this->redirect('view', [
                'id' => $id,
                'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('warning', "Membership anggota berhasil dibatalkan. Terima kasih.");
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Timkerjamember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_timkerjamember Id Timkerjamember
     * @return Timkerjamember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_timkerjamember)
    {
        if (($model = Timkerjamember::findOne(['id_timkerjamember' => $id_timkerjamember])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionTampilsatker($new, $val)
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                if ($new == "yes")
                    $selected = "1700-2022";
                else
                    $selected = $val;
                $cat_id = $parents[0];
                $jum = Timkerja::find()
                    ->select('satker, penggunasatker.nama_satker')
                    ->joinWith('penggunasatkere')
                    ->where(['tahun' => $cat_id])
                    ->distinct()
                    ->count();
                $data = Timkerja::find()
                    ->select('satker, penggunasatker.nama_satker')
                    ->joinWith('penggunasatkere')
                    ->where(['tahun' => $cat_id])
                    ->distinct()
                    ->orderBy('satker')
                    ->all();
                for ($i = 0; $i < $jum; $i++) {
                    $out[] = ['id' => $data[$i]['satker'] . '-' . $cat_id, 'name' => $data[$i]['penggunasatkere']['nama_satker']];
                }
                return Json::encode(['output' => $out, 'selected' => $selected]);
                //print_r($jum);
            }
        }
        //print_r($out);
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionTampiltimkerja($new, $val)
    {
        $out = [];
        $selected = "";
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $cat_id = $parents[0];
                // $cat_id = '1700-2022';
                if (YIi::$app->user->identity->levelsuperadmin == true) {
                    $kodesatker = substr($cat_id, 0, 4);
                    $tahun = substr($cat_id, 5, 4);
                    $jum = Timkerja::find()
                        ->select('*')
                        ->where(['satker' => $kodesatker])
                        ->andWhere(['tahun' => $tahun])
                        ->count();
                    $data = Timkerja::find()
                        ->select('*')
                        ->where(['satker' => $kodesatker])
                        ->andWhere(['tahun' => $tahun])
                        ->orderBy('id_timkerja')
                        ->all();
                } else {
                    if ($new == "yes")
                        $selected = "";
                    else
                        $selected = $val;
                    $tahun = $cat_id;
                    $jum = Timkerja::find()
                        ->select('*')
                        ->where(['satker' => Yii::$app->user->identity->satker])
                        ->andWhere(['tahun' => $tahun])
                        ->count();
                    $data = Timkerja::find()
                        ->select('*')
                        ->where(['satker' => Yii::$app->user->identity->satker])
                        ->andWhere(['tahun' => $tahun])
                        ->orderBy('id_timkerja')
                        ->all();
                }

                for ($i = 0; $i < $jum; $i++) {
                    $out[] = ['id' => $data[$i]['id_timkerja'], 'name' => $data[$i]['nama_timkerja']];
                }
                return Json::encode(['output' => $out, 'selected' => $selected]);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionTampilanggota()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $cat_id = $parents[0];
                $kodesatker = substr($cat_id, 0, 4);
                $jum = Pengguna::find()
                    ->select('*')
                    ->where(['satker' => $kodesatker])
                    ->count();
                $data = Pengguna::find()
                    ->select('*')
                    ->where(['satker' => $kodesatker])
                    ->orderBy(['pangkatgol' => SORT_DESC])
                    ->all();
                for ($i = 0; $i < $jum; $i++) {
                    $out[] = ['id' => $data[$i]['username'], 'name' => $data[$i]['gelar_depan'] . ' ' . $data[$i]['nama'] . ', ' . $data[$i]['gelar_belakang']];
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }
    public function actionRekapmemberindex()
    {
        $searchModel = new PenggunaCari();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('status_pengguna = 1');
        $dataProvider->pagination->pageSize = 20;

        return $this->render('rekapmemberindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRekapmemberviewtim($id)
    {
        $tim = Timkerjamember::find()->select('nama_timkerja as namatim')->joinWith('timkerjae')->where('anggota = "' . $id . '"')->andWhere('is_member = 1')->asArray()->all();

        $items = [];
        foreach ($tim as $value) {
            array_push($items, $value['namatim']);
        }
        if (!empty($items))
            $listtim =   "<p>+ " . implode("<br/>+ ", $items) . "</p>";
        else
            $listtim = '-';


        $project = Dailyreport::find()->select(['*'])->joinWith('timkerjaprojecte')->where('owner = "' . $id . '"')->orWhere('assigned_to = "' . $id . '"')->groupBy('timkerjaproject')->asArray()->all();
        $itemsproject = [];
        foreach ($project as $value) {
            array_push($itemsproject, $value['project_name']);
        }
        if (!empty($itemsproject))
            $listprojects =   "<p>+ " . implode("<br/>+ ", $itemsproject) . "</p>";
        else
            $listprojects = '-';

        $model = Pengguna::findOne($id);

        if (Yii::$app->user->identity->levelpegawai == true && $id != Yii::$app->user->identity->username) {
            Yii::$app->session->setFlash('warning', "Maaf. Anda hanya diperbolehkan melihat data Anda sendiri.");
            return $this->redirect(['site/index']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('rekapmemberviewtim', [
                'model' => $model,
                'listtim' => $listtim,
                'listprojects' => $listprojects
            ]);
        } else {
            return $this->render('rekapmemberviewtim', [
                'model' => $model,
                'listtim' => $listtim,
                'listprojects' => $listprojects
            ]);
        }
    }
}
