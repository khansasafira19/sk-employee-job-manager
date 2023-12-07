<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class EmailForm extends Model {

    public $email;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
        ];
    }

    public function attributeLabels() {
        return array(
            'email' => 'email@bps.go.id',
        );
    }

}
