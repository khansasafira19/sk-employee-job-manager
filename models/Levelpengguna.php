<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "levelpengguna".
 *
 * @property int $id_levelpengguna
 * @property string $username
 * @property int $level
 * @property int $autentikasi
 */
class Levelpengguna extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $namalevel;
    public static function tableName()
    {
        return 'levelpengguna';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'level'], 'required'],
            [['level', 'autentikasi'], 'integer'],
            [['username'], 'string', 'max' => 25],
            [['level'], 'unique', 'targetAttribute' => ['level', 'username'], 'message' => 'User telah ditambahkan dengan level tersebut. Silahkan lapor ke Super Admin untuk mengembalikan status level.'],
            [['username'], 'validateJumlahlevel', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_levelpengguna' => 'Id Levelpengguna',
            'username' => 'Username',
            'level' => 'Level',
            'autentikasi' => 'Autentikasi',
        ];
    }

    public function validateJumlahlevel()
    {
        $a = $this->username;
        $b = Levelpengguna::find()->where(['username' => $a])->count();
        if ($b >= 3) {
            $this->addError('username', 'Pengguna sudah memiliki tiga atau lebih level. Jumlah saat ini: ' . $b);
        }
    }

    public function getLevele()
    {
        return $this->hasOne(Level::className(), ['id_level' => 'level']);
    }

    public function getPenggunae()
    {
        return $this->hasOne(Pengguna::className(), ['username' => 'username']);
    }
}
