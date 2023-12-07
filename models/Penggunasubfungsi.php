<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subfungsi".
 *
 * @property int $id_subfungsi
 * @property int $id_fungsi
 * @property string $nama_subfungsi
 */
class Penggunasubfungsi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penggunasubfungsi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_fungsi', 'nama_subfungsi'], 'required'],
            [['id_fungsi'], 'integer'],
            [['nama_subfungsi'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_subfungsi' => 'Id Subfungsi',
            'id_fungsi' => 'Id Fungsi',
            'nama_subfungsi' => 'Nama Subfungsi',
        ];
    }
}
