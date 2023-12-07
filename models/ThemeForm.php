<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ThemeForm extends Model
{
    public $choice;    

    /**
     * @return array the validation rules.
     */
    
    public function rules()
    {
        return [
            [['choice'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            // 'verifyCode' => 'Verification Code',
        ];
    }

}
