<?php

namespace app\models;

use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "driver".
 *
 * @property int $id
 * @property string $fio
 * @property string $license_issue_date
 * @property string $deleted
 * @property int $stag
 * 
 *
 * @property Schedule[] $schedules
 * @property Trip[] $trips
 */
class Driver extends \yii\db\ActiveRecord
{
 
    //Стаж в годах
    public function getStag()
    {
        $stag = date('Y') - date('Y',strtotime($this->license_issue_date)) - 
                (date('m') * 32 + date('d') < date('m',strtotime($this->license_issue_date)) * 32 + date('d',strtotime($this->license_issue_date)) ? 1 : 0);
        return $stag > 0 ? $stag : 0;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'driver';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio'], 'required'],
            [['license_issue_date', 'deleted'], 'safe'],
            ['license_issue_date', 'compare', 'compareValue' => date('Y-M-d'), 'operator' => '<=', 'type' => 'date'],
            [['fio'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'ФИО',
            'license_issue_date' => 'Дата выдачи ВУ',
            'deleted' => 'Время удаления',
            'stag' => 'Стаж/лет'
        ];
    }

    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'deleted' => new Expression('NOW()'), 
                ],
            ],
        ];
    }    

    /**
     * Gets query for [[Schedules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchedules()
    {
        return $this->hasMany(Schedule::class, ['driver_id' => 'id']);
    }

    /**
     * Gets query for [[Trips]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrips()
    {
        return $this->hasMany(Trip::class, ['driver_id' => 'id']);
    }

    public function validateDate(){

        $currentDate = Yii::$app->getFormatter()->asDate(date('Y-M-d'));
    
        if ($this->license_issue_date > $currentDate){
            $this->addError('license_issue_date', '"Дата выдачи ВУ" не может быть больше текущей даты');
        }
    }    

}
