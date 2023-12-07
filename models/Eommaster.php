<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "eommaster".
 *
 * @property int $id_eommaster
 * @property string $nama_eommaster
 * @property string $definisi_eommaster
 */
class Eommaster extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eommaster';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_eommaster', 'nama_eommaster', 'definisi_eommaster'], 'required'],
            [['id_eommaster'], 'integer'],
            [['definisi_eommaster'], 'string'],
            [['nama_eommaster'], 'string', 'max' => 255],
            [['id_eommaster'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_eommaster' => 'Id Eommaster',
            'nama_eommaster' => 'Nama Eommaster',
            'definisi_eommaster' => 'Definisi Eommaster',
        ];
    }
}
