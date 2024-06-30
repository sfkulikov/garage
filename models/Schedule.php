<?php

namespace app\models;

use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "schedule".
 *
 * @property int $id
 * @property int $driver_id
 * @property int $car_id
 * @property string $start_time
 * @property string $end_time
 * @property string $address_from
 * @property string $address_to
 * @property float $driver_avard
 * @property string $deleted
 * @property string $driver_fio
 *
 * @property Car $car
 * @property Driver $driver
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['driver_id', 'car_id', 'address_from', 'address_to'], 'required'],
            [['driver_id', 'car_id'], 'integer'],
            [['start_time', 'end_time', 'deleted'], 'safe'],
            [['driver_avard'], 'number'],
            [['address_from', 'address_to'], 'string', 'max' => 255],
            [['car_id'], 'exist', 'skipOnError' => true, 'targetClass' => Car::class, 'targetAttribute' => ['car_id' => 'id']],
            [['driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Driver::class, 'targetAttribute' => ['driver_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'driver_id' => 'Водитель',
            'car_id' => 'Автомобиль',
            'start_time' => 'Время начала',
            'end_time' => 'Время окончания',
            'address_from' => 'Откуда',
            'address_to' => 'Куда',
            'driver_avard' => 'Стоимость',
            'deleted' => 'Время удаления',
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
     * Gets query for [[Car]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCar()
    {
        return $this->hasOne(Car::class, ['id' => 'car_id']);
    }

    /**
     * Gets query for [[Driver]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDriver()
    {
        return $this->hasOne(Driver::class, ['id' => 'driver_id']);
    }

    public function getCarText()
    {
        //return $this->hasOne(Car::class, ['id' => 'car_id'])->model;
        $car_ = Car::findOne($this->car_id); 
        return $car_->model.' ('.$car_->car_number.')';
    }
    
    public function getDriverFIO()
    {
        return Driver::findOne($this->driver_id)->fio;
    }
 
    public function beforeSave($insert)
    {
        if(!parent::beforeSave($insert))
        {
            return false;
        }
        
        $car_ = Car::findOne($this->car_id); 
        $driver_ = Driver::findOne($this->driver_id); 

        if ($car_->car_type_id == 1){
            if ($driver_ -> stag >= 2)
                return true;
            else
                return false;
        }
        else {
            if ($car_->car_type_id == 2){
                if ($driver_ -> stag >= 5)
                    return true;
                else
                    return false;
            }
        }
        return true;
    }    
}
