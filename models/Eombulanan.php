<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "eombulanan".
 *
 * @property int $id_eombulanan
 * @property int $tahun
 * @property int $bulan
 * @property int $satker
 * @property string $pegawai
 * @property int $ranking_sistem
 * @property int|null $ranking_voting
 * @property float|null $satu_persen
 * @property float|null $dua_persen
 * @property float|null $tiga_persen
 * @property float|null $empat_persen
 * @property float|null $lima_persen
 * @property float|null $enam_persen
 * @property int|null $pilihan_pimpinan
 * @property string $timestamp
 * @property string $timestamp_lastupdated
 */
class Eombulanan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eombulanan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun', 'bulan', 'satker', 'pegawai', 'ranking_sistem'], 'required'],
            [['tahun', 'bulan', 'satker', 'ranking_sistem', 'ranking_voting', 'pilihan_pimpinan'], 'integer'],
            [['satu_persen', 'dua_persen', 'tiga_persen', 'empat_persen', 'lima_persen', 'enam_persen'], 'number'],
            [['timestamp', 'timestamp_lastupdated'], 'safe'],
            [['pegawai'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_eombulanan' => 'ID EOM Bulanan',
            'tahun' => 'Tahun',
            'bulan' => 'Bulan',
            'satker' => 'Satker',
            'pegawai' => 'Pegawai',
            'ranking_sistem' => 'Ranking Sistem',
            'ranking_voting' => 'Ranking Voting',
            'satu_persen' => 'Nilai Unsur Satu',
            'dua_persen' => 'Nilai Unsur Dua',
            'tiga_persen' => 'Nilai Unsur Tiga',
            'empat_persen' => 'Nilai Unsur Empat',
            'lima_persen' => 'Nilai Unsur Lima',
            'enam_persen' => 'Nilai Unsur Enam',
            'pilihan_pimpinan' => 'Pilihan Pimpinan',
            'timestamp' => 'Timestamp',
            'timestamp_lastupdated' => 'Timestamp Lastupdated',
        ];
    }

    public function getCkpbulane()
    {
        return $this->hasOne(Ckpbulan::className(), ['kode_bulan' => 'bulan']);
    }

    public function getPenggunasatkere()
    {
        return $this->hasOne(Penggunasatker::className(), ['id_satker' => 'satker']);
    }

    public function getPenggunae()
    {
        return $this->hasOne(Pengguna::className(), ['username' => 'pegawai']);
    }
    
}
