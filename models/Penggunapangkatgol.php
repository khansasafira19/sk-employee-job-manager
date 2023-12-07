<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pangkatgol".
 *
 * @property int $id_pangkatgol
 * @property string $nama_pangkatgol
 */
class Penggunapangkatgol extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penggunapangkatgol';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_pangkatgol'], 'required'],
            [['nama_pangkatgol'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pangkatgol' => 'Id Pangkatgol',
            'nama_pangkatgol' => 'Nama Pangkatgol',
        ];
    }
}
