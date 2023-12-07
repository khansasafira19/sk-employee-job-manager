<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "timkerjamember".
 *
 * @property int $id_timkerjamember
 * @property int $timkerja
 * @property string $anggota
 * @property int $is_ketua
 * @property int $is_member
 */
class Timkerjamember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'timkerjamember';
    }

    public $satkere, $tahune;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['timkerja', 'anggota'], 'required'],
            [['timkerja', 'is_ketua', 'is_member'], 'integer'],
            [['anggota'], 'string', 'max' => 30],
            [['anggota'], 'validateDuplicate', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['is_ketua'], 'validateStatusketua', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['satkere', 'tahune'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_timkerjamember' => 'ID Data',
            'timkerja' => 'Tim Kerja',
            'anggota' => 'Anggota',
            'is_ketua' => 'Sebagai Ketua',
            'is_member' => 'Is Member',
            'timkerjae' => 'Tim Kerja',
            'penggunasatkere' => 'Satker',
            'satkere' => 'Satker',
        ];
    }

    public function validateDuplicate()
    {
        if (Yii::$app->controller->action->id != 'update') {
            $a = $this->timkerja;
            $b = $this->anggota;
            $c = Timkerjamember::find()->where(['timkerja' => $a])->andWhere(['anggota' => $b])->count();
            if ($c >= 1) {
                $this->addError('anggota', 'Pegawai ini telah masuk ke dalam tim yang Anda input');
            }
        }
    }

    public function validateStatusketua()
    {
        $a = $this->timkerja;
        $b = $this->is_ketua;
        $data = $this->anggota;
        $c = Timkerjamember::find()->where(['timkerja' => $a])->andWhere(['is_ketua' => $b])->count();
        if ($c >= 1) {
            $d = Timkerjamember::find()->where(['timkerja' => $a])->andWhere(['is_ketua' => $b])->joinWith('penggunae')->one();
            if ($data != $d['anggota'])
                $this->addError('is_ketua', 'Tim ini sudah memiliki Ketua atas nama ' . $d['penggunae']['gelar_depan'] . ' ' . $d['penggunae']['nama'] . ', ' . $d['penggunae']['gelar_belakang']);
        }
    }

    public function validateTahune()
    {
        $a = $this->tahune;
        $b = date("Y");
        if ($a - $b >= 2 || $b - $a >= 2) {
            $this->addError('tahune', 'Data Tim Kerja yang dapat diinput/diubah hanya tahun ini, tahun sebelum tahun ini, atau tahun sesudah tahun ini');
        }
    }

    public function getPenggunae()
    {
        return $this->hasOne(Pengguna::className(), ['username' => 'anggota']);
    }

    public function getTimkerjae()
    {
        return $this->hasOne(Timkerja::className(), ['id_timkerja' => 'timkerja']);
    }

    public function getPenggunasatkere()
    {
        return $this->hasOne(Penggunasatker::className(), ['id_satker' => 'satker'])->via('timkerjae');
    }
}
