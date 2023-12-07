<?php

namespace app\controllers;

use app\models\Timkerja;
use app\models\TimkerjaCari;
use app\models\Timkerjamember;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * TimkerjaController implements the CRUD actions for Timkerja model.
 */
class TimkerjaController extends Controller
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
                            'actions' => ['view', 'index', 'create', 'update', 'import'],
                            'allow' => true,
                            'matchCallback' => function ($rule, $action) {
                                return !\Yii::$app->user->isGuest && (\Yii::$app->user->identity->levelsuperadmin === true || \Yii::$app->user->identity->leveladmin === true);
                            },
                        ],
                        [
                            'actions' => ['tampiltimkerja'],
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
     * Lists all Timkerja models.
     *
     * @return string
     */
    public function actionIndex($tahun)
    {
        $searchModel = new TimkerjaCari();
        $dataProvider = $searchModel->search($this->request->queryParams);
        if ($tahun != '')
            $dataProvider->query->andWhere(' tahun = ' . $tahun);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Timkerja model.
     * @param int $id_timkerja Id Timkerja
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $level = Timkerjamember::find()->select('*')->joinWith('penggunae')->where(['timkerja' => $id])->andWhere(['is_member' => 1])->orderBy('pangkatgol')->all();
        $items = [];
        foreach ($level as $value) {
            array_push($items, $value['penggunae']['gelar_depan'] . ' ' . $value['penggunae']['nama'] . ', ' . $value['penggunae']['gelar_belakang']);
        }
        if (!empty($items))
            $levels =   "<p>+ " . implode("<br/>+ ", $items) . "</p>";
        else
            $levels = '[BELUM ADA]';

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
                'levels' => $levels
            ]);
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
                'levels' => $levels
            ]);
        }
    }

    /**
     * Creates a new Timkerja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (date('n') != 1) {
            Yii::$app->session->setFlash('warning', "Maaf. Tim Kerja hanya dapat diinput pada bulan Januari setiap tahunnya. <br/>Terima kasih.");
            return $this->redirect(['index', 'tahun'=>date("Y")]);
        }

        $model = new Timkerja();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('warning', "Data anggota berhasil diubah. Terima kasih.");
                return $this->redirect(['view', 'id' => $model->id_timkerja]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Timkerja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_timkerja Id Timkerja
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_timkerja]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Timkerja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_timkerja Id Timkerja
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $affected_rows = Timkerja::updateAll(['status' => 0], 'id_timkerja = "' . $id . '"');
        if ($affected_rows == 0) {
            Yii::$app->session->setFlash('warning', "Perintah gagal dieksekusi. Mohon hubungi Super Admin.");
            return $this->redirect('view', [
                'id' => $id,
                'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('warning', "Tim kerja berhasil dihapus dari sistem. Terima kasih.");
            return $this->redirect(['index', 'tahun' => date("Y")]);
        }
    }

    /**
     * Finds the Timkerja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_timkerja Id Timkerja
     * @return Timkerja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_timkerja)
    {
        if (($model = Timkerja::findOne(['id_timkerja' => $id_timkerja])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionImport()
    {
        $model = new Timkerja();

        if ($model->load(Yii::$app->request->post())) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            if ($model->importFile != null) {
                $filename = 'timkerja';

                if (Yii::$app->user->identity->levelsuperadmin == true)
                    $path = \Yii::getAlias("@webroot/library/excel/" . $filename . '-superadmin-' .  date('Y-m-d') . '.' . $model->importFile->extension);
                else
                    $path = \Yii::getAlias("@webroot/library/excel/" . $filename . '-admin-' . Yii::$app->user->identity->satker . '-' .  date('Y-m-d') . '.' . $model->importFile->extension);

                if (file_exists($path)) {
                    if (Yii::$app->user->identity->levelsuperadmin == true)
                        $path = \Yii::getAlias("@webroot/library/excel/" . $filename . '-superadmin-' .  date('Y-m-d') . ' - versi 2.' . $model->importFile->extension);
                    else
                        $path = \Yii::getAlias("@webroot/library/excel/" . $filename . '-admin-' . Yii::$app->user->identity->satker . '-' .  date('Y-m-d') . ' - versi 2.' . $model->importFile->extension);
                }
                $uploaded = $model->importFile->saveAs($path);
                if ($uploaded) {
                    try {
                        $inputFileType = IOFactory::identify($path);
                        $objReader = IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($path);
                    } catch (Exception $e) {
                        Yii::$app->session->setFlash('warning', "Error membaca data. Silahkan hubungi Super Admin jika terjadi kesalahan.");
                        return $this->redirect(['index', 'tahun' => date("Y")]);
                    }

                    $sheet = $objPHPExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn  = $sheet->getHighestColumn();

                    if ($highestColumn > "I") {
                        Yii::$app->session->setFlash('warning', "Terdapat kelebihan kolom pada file. Silahkan sesuaikan kembali dengan template. <br/>Terima kasih.");
                        return $this->redirect(['index', 'tahun' => date("Y")]);
                    }

                    for ($row = 2; $row <= $highestRow; $row++) {
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                        if ($row == 1) {
                            continue; //skip baris pertama
                        }

                        $tahun = $rowData[0][1];
                        $satker = $rowData[0][2];
                        $nama = $rowData[0][3];
                        $cek = Timkerja::find()->select('*')->where('tahun = ' . $tahun)->andWhere('satker = ' . $satker)->andWhere('nama_timkerja = "' . $nama . '"')->andWhere('status = 1')->count();
                        if ($cek > 1) {
                            Yii::$app->session->setFlash('warning', "Terdapat duplikasi data. Silahkan cek kembali. <br/>Terima kasih.");
                            return $this->redirect(['index', 'tahun' => date("Y")]);
                        } elseif (array_search('', $rowData[0]) !== false) {
                            Yii::$app->session->setFlash('warning', "Terdapat cell kosong pada tabel di dalam file. Silahkan sesuaikan kembali dengan template. <br/>Terima kasih.");
                            return $this->redirect(['index', 'tahun' => date("Y")]);
                        } elseif ($rowData[0][1] - date("Y") >= 2 || date("Y") - $rowData[0][1] >= 2) {
                            Yii::$app->session->setFlash('warning', "Silahkan input data tim kerja di tahun ini, tahun sebelum tahun ini, atau tahun sesudah tahun ini. <br/>Terima kasih.");
                            return $this->redirect(['index', 'tahun' => date("Y")]);
                        } elseif (!in_array($rowData[0][2], [1700, 1701, 1702, 1703, 1704, 1705, 1706, 1707, 1708, 1709, 1771])) {
                            Yii::$app->session->setFlash('warning', "Data satker untuk satker di Provinsi Bengkulu (1700 - 1709 dan 1771). <br/>Terima kasih.");
                            return $this->redirect(['index', 'tahun' => date("Y")]);
                        } else {
                            $anggaran = new Timkerja();
                            $anggaran->tahun = $rowData[0][1];
                            $anggaran->satker = $rowData[0][2];
                            if (ctype_lower($nama)) {
                                $anggaran->nama_timkerja = ucwords($rowData[0][3]);
                            } else {
                                $anggaran->nama_timkerja = $rowData[0][3];
                            }                            
                            $anggaran->save();
                        }

                    }
                    Yii::$app->session->setFlash('warning', "Data Tim Kerja berhasil diinput. <br/>Terima kasih.");
                    return $this->redirect(['index', 'tahun' => date("Y")]);
                }
            }
            return $this->redirect(['index', 'tahun' => date("Y")]);
        }

        return $this->render('import', [
            'model' => $model,
        ]);
    }
}
