<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "timkerjaproject".
 *
 * @property int $id_project
 * @property int $timkerja
 * @property string $project_name
 * @property string $project_description
 * @property string $start_date
 * @property string $finish_date
 */
class Timkerjaproject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $persentase, $totalselesai, $totaltarget;
    public static function tableName()
    {
        return 'timkerjaproject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['timkerja', 'project_name', 'project_description', 'start_date', 'finish_date'], 'required'],
            [['timkerja'], 'integer'],
            [['project_description'], 'string'],
            [['start_date', 'finish_date'], 'safe'],
            [['project_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_project' => 'Id Project',
            'timkerja' => 'Timkerja',
            'project_name' => 'Project Name',
            'project_description' => 'Project Description',
            'start_date' => 'Start Date',
            'finish_date' => 'Finish Date',
        ];
    }

    public function getTimkerjae()
    {
        return $this->hasOne(Timkerja::className(), ['id_timkerja' => 'timkerja']);
    }

    public function getDailyreporte()
    {
        return $this->hasMany(Dailyreport::className(), ['timkerjaproject' => 'id_project']);
    }  
}
