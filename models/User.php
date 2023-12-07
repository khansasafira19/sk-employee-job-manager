<?php

namespace app\models;

use yii\db\ActiveRecord;

use Yii;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return 'pengguna';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        // return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /* foreach (self::$users as $user) {
          if ($user['accessToken'] === $token) {
          return new static($user);
          }
          }

          return null;
         */

        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        /* foreach (self::$users as $user) {
          if (strcasecmp($user['username'], $username) === 0) {
          return new static($user);
          }
          }

          return null;
         */
        return static::findOne(['username' => $username]);
        //return static::findOne(['username' => $username,'id_bidsie'=>5]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        //return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        //return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    public function validateAktif()
    {
        return ($this->status_pengguna != 0);
        //return $this->password === sha1($password);
    }

    public function getLevels()
    {
        $model = Levelpengguna::find()->select('*, level.nama_level AS namalevel')->joinWith(['levele'])->where(['username' => $this->username, 'autentikasi' => 1])->all();
        return $model;
    }
    public function getLevelcount()
    {
        $model = Levelpengguna::find()->select('*')->where(['username' => $this->username, 'autentikasi' => 1])->orderBy(['level' => SORT_ASC])->all();
        // return print_r($model);
        $check = [];
        foreach ($model as $row) {
            //echo $row->level;
            array_push($check, $row->level);
        }
        if (count($check) > 1)
            return true;
        else
            return false;
    }

    public function getLevelsuperadmin()
    {
        $model = Levelpengguna::find()->select('*')->where(['username' => $this->username, 'autentikasi' => 1])->orderBy(['level' => SORT_ASC])->all();
        // return print_r($model);
        $check = [];
        foreach ($model as $row) {
            //echo $row->level;
            array_push($check, $row->level);
        }
        if (in_array(0, $check))
            return true;
        // if (Yii::$app->session['choicesuperadmin'] == true) return true;
        // else return false;
        else
            return false;
    }

    public function getLeveladmin()
    {
        $model = Levelpengguna::find()->select('*')->where(['username' => $this->username, 'autentikasi' => 1])->orderBy(['level' => SORT_ASC])->all();
        // return print_r($model);
        $check = [];
        foreach ($model as $row) {
            //echo $row->level;
            array_push($check, $row->level);
        }
        if (in_array(1, $check))
            return true;
        // if (Yii::$app->session['choiceadmin'] == true) return true;
        // else return false;
        else
            return false;
    }

    public function getLevelpimpinan()
    {
        $model = Levelpengguna::find()->select('*')->where(['username' => $this->username, 'autentikasi' => 1])->orderBy(['level' => SORT_ASC])->all();
        $check = [];
        foreach ($model as $row) {
            //echo $row->level;
            array_push($check, $row->level);
        }
        if (in_array(2, $check))
            return true;
        // if (Yii::$app->session['choicepimpinan'] == true) return true;
        // else return false;
        else
            return false;
    }
    public function getLevelketuatim()
    {
        $model = Levelpengguna::find()->select('*')->where(['username' => $this->username, 'autentikasi' => 1])->orderBy(['level' => SORT_ASC])->all();
        $check = [];
        foreach ($model as $row) {
            //echo $row->level;
            array_push($check, $row->level);
        }
        if (in_array(3, $check))
            return true;
        // if (Yii::$app->session['choiceketuatim'] == true) return true;
        // else return false;
        else
            return false;
    }

    public function getLeveladmintu()
    {
        $model = Levelpengguna::find()->select('*')->where(['username' => $this->username, 'autentikasi' => 1])->orderBy(['level' => SORT_ASC])->all();
        $check = [];
        foreach ($model as $row) {
            //echo $row->level;
            array_push($check, $row->level);
        }
        if (in_array(4, $check))
            return true;
        // if (Yii::$app->session['choiceadmintu'] == true) return true;
        // else return false;
        else
            return false;
    }

    public function getLevelpegawai()
    {
        $model = Levelpengguna::find()->select('*')->where(['username' => $this->username, 'autentikasi' => 1])->orderBy(['level' => SORT_ASC])->all();
        $check = [];
        foreach ($model as $row) {
            //echo $row->level;
            array_push($check, $row->level);
        }
        if (in_array(5, $check) && count($check) <= 1)
            return true;
        // if (Yii::$app->session['choicepegawai'] == true) return true;
        // else return false;
        else
            return false;
    }

    public function getKantor()
    {
        $model = Penggunasatker::find()->select('*')->where('id_satker = ' . $this->satker)->one();
        return 'BPS ' . $model->nama_satker;
    }

    public function getIsprakom()
    {
        $cek = Penggunajabatan::find()->select('nama_jabatan')->where(['id_jabatan' => $this->jabatan])->one();
        if (str_contains($cek->nama_jabatan, 'Pranata Komputer')) { 
            return true;
        } else
            return false;
    }

    public function getIsstatistisi()
    {
        $cek = Penggunajabatan::find()->select('nama_jabatan')->where(['id_jabatan' => $this->jabatan])->one();
        if (str_contains($cek->nama_jabatan, 'Statistisi')) { 
            return true;
        } else
            return false;
    }

    public function getIspenyuluhhukum()
    {
        $cek = Penggunajabatan::find()->select('nama_jabatan')->where(['id_jabatan' => $this->jabatan])->one();
        if (str_contains($cek->nama_jabatan, 'Penyuluh Hukum')) { 
            return true;
        } else
            return false;
    }
}
