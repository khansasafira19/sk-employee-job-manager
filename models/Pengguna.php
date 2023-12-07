<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pengguna".
 *
 * @property string $username
 * @property string $password
 * @property int $nip
 * @property string $nama
 * @property int $jabatan
 * @property int $pangkatgol
 * @property string $email
 * @property string $foto
 * @property string $tgl_daftar
 * @property int $status_pengguna
 */
class Pengguna extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $password_repeat, $superadmin, $admin, $pjsdm, $filefoto;

    public static function tableName()
    {
        return 'pengguna';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'nip', 'nama', 'jabatan', 'pangkatgol', 'email', 'foto', 'fungsi_pengguna', 'approved_ckp_by'], 'required'],
            [['nip', 'jabatan', 'pangkatgol', 'status_pengguna', 'fungsi_pengguna'], 'integer'],
            [['foto'], 'string'],
            [['tgl_daftar'], 'safe'],
            [['username'], 'string', 'max' => 25],
            [['password', 'nama', 'email'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['nip'], 'string', 'max' => 18],
            [['nip'], 'string', 'min' => 18],
            ['password_repeat', 'required', 'skipOnEmpty' => !$this->isNewRecord],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => "Password tidak sesuai"],
            [['approved_ckp_by'], 'validateApprovedBy', 'skipOnEmpty' => false, 'skipOnError' => false],
            // ['filefoto', 'required', 'skipOnEmpty' => !$this->isNewRecord, 'message' => 'Mohon upload file.'],
            // [['filefoto'], 'file', 'extensions' => 'jpg', 'maxSize' => 2097152, 'message' => 'Mohon gunakan JPG dengan ukuran maksimal 2 MB.'],            
            ['filefoto', 'image', 'enableClientValidation' => FALSE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
            'nip' => 'NIP',
            'nama' => 'Nama',
            'jabatan' => 'Jabatan',
            'pangkatgol' => 'Pangkat/Golongan',
            'pangkatgole' => 'Pangkat/Golongan',
            'email' => 'Email',
            'foto' => 'Foto',
            'tgl_daftar' => 'Tgl Daftar',
            'status_pengguna' => 'Status Pengguna',
            'fungsie' => 'Fungsi',
            'fungsi_pengguna' => 'Fungsi Pengguna',
            'is_ckp_approver' => 'Pegawai Ini Adalah Penanggung Jawab CKP',
            'approved_ckp_by' => 'CKP Pegawai Ini Dinilai Oleh',
            'filefoto' => 'File Foto'
        ];
    }

    public function getPangkatgole()
    {
        return $this->hasOne(Penggunapangkatgol::className(), ['id_pangkatgol' => 'pangkatgol']);
    }

    public function getJabatane()
    {
        return $this->hasOne(Penggunajabatan::className(), ['id_jabatan' => 'jabatan']);
    }

    public function getLevelpenggunae()
    {
        return $this->hasMany(Levelpengguna::className(), ['username' => 'username']);
    }

    public function getLevele()
    {
        return $this->hasMany(Level::className(), ['id_level' => 'level'])->via('levelpenggunae');
    }

    public function getFungsie()
    {
        return $this->hasOne(Penggunafungsi::className(), ['id_fungsi' => 'fungsi_pengguna']);
    }

    public function getSubfungsie()
    {
        return $this->hasOne(Penggunasubfungsi::className(), ['id_subfungsi' => 'subfungsi_pengguna']);
    }

    public function getSatkere()
    {
        return $this->hasOne(Penggunasatker::className(), ['id_satker' => 'satker']);
    }

    public function getPenggunaapprovere()
    {
        return $this->hasOne(Penggunaapprover::className(), ['id_approver' => 'approved_ckp_by']);
    }

    public function getNamapenggunaapprovere()
    {
        return $this->hasOne(Pengguna::className(), ['username' => 'approver'])->via('penggunaapprovere');
    }

    public function getJumlahtim()
    {
        $username = $this->username;
        $tim = Timkerjamember::find()->select('*')->where('anggota = "' . $username . '"')->andWhere('is_member = 1')->count();
        return $tim;
    }

    public function getJumlahproject()
    {
        $username = $this->username;
        $tim = Dailyreport::find()->select(['COUNT(DISTINCT timkerjaproject) as timkerjaproject'])->where('owner = "' . $username . '"')->orWhere('assigned_to = "' . $username . '"')->one();
        return $tim->timkerjaproject;
    }

    public function getJumlahtugas()
    {
        $username = $this->username;
        $tim = Dailyreport::find()->select(['*'])->where('owner = "' . $username . '"')->orWhere('assigned_to = "' . $username . '"')->count();
        return $tim;
    }

    // public function beforeSave($insert)
    // {
    //     if (parent::beforeSave($insert)) {
    //         $this->password = md5($this->password);
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->password = md5($this->password);
                return true;
            } else
                return true;
        } else {
            return false;
        }
    }

    public function validateApprovedBy()
    {
        $a = $this->approved_ckp_by;
        $b = $this->username;
        if ($a == $b) {
            $this->addError('approved_ckp_by', 'Pegawai tidak dapat menilai CKP-nya sendiri.');
        }
    }
}
