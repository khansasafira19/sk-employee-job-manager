<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dailypresence".
 *
 * @property int $id_dailypresence
 * @property string $tanggal
 * @property string $pegawai
 * @property string|null $jam_datang
 * @property string|null $jam_pulang
 * @property int $status_presensi
 */
class Dailypresence extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dailypresence';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tanggal', 'pegawai'], 'required'],
            [['tanggal', 'jam_datang', 'jam_pulang'], 'safe'],
            [['status_presensi'], 'integer'],
            [['pegawai'], 'string', 'max' => 30],
            [['tanggal'], 'validateHarikerja', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['tanggal'], 'validateBulanini', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_dailypresence' => 'Id Dailypresence',
            'tanggal' => 'Tanggal',
            'pegawai' => 'Pegawai',
            'jam_datang' => 'Jam Datang',
            'jam_pulang' => 'Jam Pulang',
            'status_presensi' => 'Status Presensi',
            'is_setujuadmin' => 'Verifikasi Admin Umum'
        ];
    }

    public function getPenggunae()
    {
        return $this->hasOne(Pengguna::className(), ['username' => 'pegawai']);
    }

    public function getDailypresencestatuse()
    {
        return $this->hasOne(Dailypresencestatus::className(), ['id_dailypresencestatus' => 'status_presensi']);
    }

    public function validateHarikerja()
    {
        $a = $this->tanggal;
        $b = $this->status_presensi;
        if (date('N', strtotime($a)) >= 6 && ($b != 4)) {
            $this->addError('tanggal', 'Presensi selain lembur dilakukan di hari kerja.');
        } elseif (date('N', strtotime($a)) <=5 && ($b == 4)) {
            $this->addError('tanggal', 'Presensi lembur dilakukan di hari libur.');
        }
    }

    public function validateBulanini()
    {
        $a = $this->tanggal;
        $b = $this->status_presensi;
        $c = date("Y-m-d");
        if (($b = 1 || $b = 4) && $a > $c) {
            $this->addError('tanggal', 'Presensi kehadiran dan lembur (selain cuti, DL) hanya dapat diinput maksimum pada tanggal hari ini.');
        }
    }
}
