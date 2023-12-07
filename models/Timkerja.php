<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "timkerja".
 *
 * @property int $id_timkerja
 * @property int $tahun
 * @property int $satker
 * @property string $nama_timkerja
 */
class Timkerja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $importFile;

    public static function tableName()
    {
        return 'timkerja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun', 'satker', 'nama_timkerja'], 'required'],
            [['tahun', 'satker', 'status'], 'integer'],
            [['nama_timkerja'], 'string', 'max' => 255],
            [['tahun'], 'validateTahun', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_timkerja' => 'Id Tim Kerja',
            'tahun' => 'Tahun',
            'satker' => 'Satker',
            'nama_timkerja' => 'Nama Tim Kerja',
            'status' => 'Status',
            'importFile' => 'Import File'
        ];
    }

    public function validateTahun()
    {
        $a = $this->tahun;
        $b = date("Y");
        if ($a - $b >= 2 || $b - $a >= 2) {
            $this->addError('tahun', 'Data Tim Kerja yang dapat diinput/diubah hanya tahun ini, tahun sebelum tahun ini, atau tahun sesudah tahun ini');
        }
    }

    public function getPenggunasatkere()
    {
        return $this->hasOne(Penggunasatker::className(), ['id_satker' => 'satker']);
    }

    public function getKetua()
    {
        $ketua = Timkerjamember::find()->select('*')
            ->joinWith('penggunae')
            ->where('timkerja = ' . $this->id_timkerja)
            ->andWhere('is_ketua = 1')->one();
        if ($ketua != '')
            return $ketua->penggunae->gelar_depan . ' ' . $ketua->penggunae->nama . ', ' . $ketua->penggunae->gelar_belakang;
        else
            return '-';
    }

    public function getYears()
    {
        $currentYear = date('Y');
        $yearFrom = 2021;
        $yearsRange = range($currentYear, $yearFrom);
        return array_combine($yearsRange, $yearsRange);
    }
}
