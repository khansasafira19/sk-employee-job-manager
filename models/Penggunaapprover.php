<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penggunaapprover".
 *
 * @property int $id_approver
 * @property int $satker
 * @property string $approver
 * @property int $autentikasi
 */
class Penggunaapprover extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penggunaapprover';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['satker', 'approver'], 'required'],
            [['satker', 'autentikasi'], 'integer'],
            [['approver'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_approver' => 'Id Approver',
            'satker' => 'Satker',
            'approver' => 'Approver',
            'autentikasi' => 'Autentikasi',
        ];
    }

    public function getPenggunae()
    {
        return $this->hasOne(Pengguna::className(), ['username' => 'approver']);
    }
}
