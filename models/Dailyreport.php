<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dailyreport".
 *
 * @property int $id_keg
 * @property int|null $timkerja
 * @property int|null $is_setujuketuatim
 * @property string $rincian_report
 * @property int $status_selesai
 * @property string $tanggal_kerja
 * @property string $timestamp
 * @property string $timestamp_lastupdated
 * @property string|null $ket
 */
class Dailyreport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $totalselesai, $totaltarget, $pemilik;
    public static function tableName()
    {
        return 'dailyreport';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['timkerjaproject', 'is_setujuketuatim', 'status_selesai','priority', 'deleted'], 'integer'],
            [['owner', 'rincian_report', 'tanggal_kerja'], 'required'],
            [['rincian_report', 'ket'], 'string'],
            [['tanggal_kerja', 'timestamp', 'timestamp_lastupdated'], 'safe'],
            [['owner', 'assigned_to'], 'string', 'max' => 30],
            [['tanggal_kerja'], 'validateHarikerja', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_keg' => 'ID Keg',
            'is_izinlintastim' => 'Diizinkan Ketua Tim Terkait',
            'assigned_to' => 'Delegasi',
            'timkerja' => 'Tim Kerja',
            'owner' => 'Pengusul Kegiatan',
            'timkerjaproject' => 'Project',
            'is_setujuketuatim' => 'Disetujui Ketua Tim',
            'rincian_report' => 'Rincian Kegiatan',
            'status_selesai' => 'Status',
            'tanggal_kerja' => 'Tanggal Kerja',
            'timestamp' => 'Timestamp',
            'timestamp_lastupdated' => 'Timestamp Last Updated',
            'ket' => 'Keterangan',
        ];
    }

    public function getOwnere()
    {
        return $this->hasOne(Pengguna::className(), ['username' => 'owner']);
    }
    public function getAssignedtoe()
    {
        return $this->hasOne(Pengguna::className(), ['username' => 'assigned_to']);
    }

    public function getTimkerjaprojecte()
    {
        return $this->hasOne(Timkerjaproject::className(), ['id_project' => 'timkerjaproject']);
    }
    public function getTimkerjae()
    {
        return $this->hasOne(Timkerja::className(), ['id_timkerja' => 'timkerja'])->via('timkerjaprojecte');
    }
    public function getKetuaproject()
    {
        // return $this->hasOne(Timkerjamember::className(), ['id_timkerjamember' => 'id_timkerja'])->via('timkerjamembere');
        $project = $this->timkerjaproject;
        $owner = $this->owner;
        $timkerja = Timkerjaproject::find()->select('*')->where('id_project = ' . $project)->one();
        $ketuatim = Timkerjamember::find()->select('is_ketua')->where('anggota = "' . $owner . '"')->andWhere('timkerja = ' . $timkerja->timkerja)->all();
        if (isset($ketuatim))
            return false;
        elseif ($ketuatim->is_ketua == 1)
            return true;
        else
            return false;
    }

    public function validateHarikerja()
    {
        $a = $this->tanggal_kerja;
        if (date('N', strtotime($a)) >= 6) {
            $cekpresensi = Dailypresence::find()->select('*')
                ->where('pegawai = "' . Yii::$app->user->identity->username . '"')
                ->andWhere(('tanggal = "' . $a . '"'))
                ->one();

            // $b = $cekpresensi->status_presensi;
            if ($cekpresensi == '') {
                $this->addError('tanggal_kerja', 'Mohon isi dahulu presensi lembur Anda pada tanggal tersebut.');
            }
        }
    }
}
