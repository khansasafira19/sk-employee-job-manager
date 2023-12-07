<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "eombulananvoting".
 *
 * @property int $id_eombulananvoting
 * @property int $eombulanan
 * @property string $voter
 * @property string $timestamp
 * @property string $timestamp_lastupdated
 */
class Eombulananvoting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eombulananvoting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eombulanan', 'voter'], 'required'],
            [['eombulanan'], 'integer'],
            [['timestamp', 'timestamp_lastupdated'], 'safe'],
            [['voter'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_eombulananvoting' => 'Id Eombulananvoting',
            'eombulanan' => 'Eombulanan',
            'voter' => 'Voter',
            'timestamp' => 'Timestamp',
            'timestamp_lastupdated' => 'Timestamp Lastupdated',
        ];
    }

    public function getPenggunae()
    {
        return $this->hasOne(Pengguna::className(), ['username' => 'voter']);
    }
    public function getEombulanane()
    {
        return $this->hasOne(Eombulanan::className(), ['id_eombulanan' => 'eombulanan']);
    }
}
