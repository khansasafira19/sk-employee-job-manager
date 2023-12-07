<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penggunasatker".
 *
 * @property int $id_satker
 * @property string $nama_satker
 */
class Penggunasatker extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penggunasatker';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_satker'], 'required'],
            [['nama_satker'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_satker' => 'Id Satker',
            'nama_satker' => 'Nama Satker',
        ];
    }
}
