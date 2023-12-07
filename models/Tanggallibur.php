<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tanggallibur".
 *
 * @property string $tanggal
 * @property int $status
 * @property string|null $ket
 */
class Tanggallibur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tanggallibur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tanggal', 'status'], 'required'],
            [['tanggal'], 'safe'],
            [['status'], 'integer'],
            [['ket'], 'string'],
            [['tanggal'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tanggal' => 'Tanggal',
            'status' => 'Status',
            'ket' => 'Ket',
        ];
    }
}
