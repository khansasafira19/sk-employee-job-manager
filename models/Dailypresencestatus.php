<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dailypresencestatus".
 *
 * @property int $id_dailypresencestatus
 * @property string $keterangan_presensi
 */
class Dailypresencestatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dailypresencestatus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keterangan_presensi'], 'required'],
            [['keterangan_presensi'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_dailypresencestatus' => 'Id Dailypresencestatus',
            'keterangan_presensi' => 'Keterangan Presensi',
        ];
    }
}
