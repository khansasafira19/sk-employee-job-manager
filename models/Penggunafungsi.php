<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fungsi".
 *
 * @property int $id_fungsi
 * @property string $nama_fungsi
 * @property string $koordinator
 */
class Penggunafungsi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penggunafungsi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_fungsi', 'koordinator'], 'required'],
            [['nama_fungsi', 'koordinator'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_fungsi' => 'Kode Satuan Fungsi',
            'nama_fungsi' => 'Nama Fungsi',
            'koordinator' => 'Koordinator Fungsi',
        ];
    }
}
