<?php

namespace app\models;

use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "trip".
 *
 * @property int $id
 * @property int $status_id
 * @property string $start_date
 * @property string $end_date
 * @property int $driver_id
 * @property int $car_id
 * @property string $address_from
 * @property string $address_to
 * @property float $driver_avard
 * @property string $deleted
 *
 * @property Car $car
 * @property Driver $driver
 * @property TripStatus $status
 */
class Trip extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'driver_id', 'car_id', 'address_from', 'address_to'], 'required'],
            [['status_id', 'driver_id', 'car_id'], 'integer'],
            [['start_date', 'end_date', 'deleted'], 'safe'],
            [['driver_avard'], 'number'],
            [['address_from', 'address_to'], 'string', 'max' => 255],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TripStatus::class, 'targetAttribute' => ['status_id' => 'id']],
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
            'status_id' => 'Status ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'driver_id' => 'Driver ID',
            'car_id' => 'Car ID',
            'address_from' => 'Address From',
            'address_to' => 'Address To',
            'driver_avard' => 'Driver Avard',
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

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(TripStatus::class, ['id' => 'status_id']);
    }

    public function getStatusText()
    {
        return TripStatus::findOne($this->status_id)->status_name;
    }

    public function getDriverText()
    {
        return Driver::findOne($this->driver_id)->fio;
    }

    public function getCarText()
    {
        $car_ = Car::findOne($this->car_id); 
        return $car_->model.' ('.$car_->car_number.')';
    }

    public function Cancel()
    {
        $trip = Trip::findOne($this->id);
        $trip->status_id = 2;
        $trip->save();    
        return $trip;
        //Trip::findOne($this->id);
        //setTripStatus(2);
    }

    protected function setTripStatus($status_id)
    {
        $trip = Trip::findOne($this->id);
        $trip->status_id = $status_id;
        $trip->save();

        /*
        $transaction=$trip_->dbConnection->beginTransaction();
        try
        {
            // поиск и сохранение — шаги, между которыми могут быть выполнены другие запросы,
            // поэтому мы используем транзакцию, чтобы удостовериться в целостности данных
            $trip_ = Trip::findOne($this->id);
            $trip_->status_id = status_id;
            if($trip_->save())
                $transaction->commit();
            else
                $transaction->rollback();
        }
        catch(Exception $e)
        {
            $transaction->rollback();
            throw $e;
        }
            */

    }

    public function handleTrips(){
        // 1. выбрать все активные рейсы
        // 2. завершить их
        // 3. создать новые по табличке Расписания

    }


}
