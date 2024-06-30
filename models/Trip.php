<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\db\Expression;
use app\models\Schedule;
use app\models\ScheduleSearch;

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
 * @property string $driver_fio
 *
 * @property Car $car
 * @property Driver $driver
 * @property TripStatus $status
 */
class Trip extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 0;
    const STATUS_FINISH = 1;
    const STATUS_CANCEL = 2;
    
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
            [['start_date', 'end_date'], 'safe'],
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
            'status_id' => 'Статус рейса',
            'start_date' => 'Дата/время начала',
            'end_date' => 'Дата/время окончания',
            'driver_id' => 'Водитель',
            'car_id' => 'Автомобиль',
            'address_from' => 'Откуда',
            'address_to' => 'Куда',
            'driver_avard' => 'Стоимость',
            'driver_fio' => 'ФИО',
        ];
    }

    /*
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
    */

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

    public function getDriverFIO()
    {
        return Driver::findOne($this->driver_id)->fio;
    }

    public function getCarText()
    {
        $car_ = Car::findOne($this->car_id); 
        return $car_->model.' ('.$car_->car_number.')';
    }

    protected function setTripStatus($status_id)
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try
        {
            $trip = Trip::findOne($this->id);
            $trip->status_id = $status_id;
            if($trip->save())
                $transaction->commit();
            else
                $transaction->rollback();
        }
        catch(Exception $e)
        {
            $transaction->rollback();
            throw $e;
        }
        return $trip;
    }

    // Отменить рейс
    public function Cancel()
    {
        return $this->setTripStatus(2);
    }

    // Завершить рейс
    public function Finish()
    {
        return $this->setTripStatus(1);
    }

    // Обработать расписание
    public function HandleTrips()
    {
        Trip::updateAll(['status_id' => Trip::STATUS_FINISH], ['=', 'status_id', '0']);        

        $schedules = Schedule::find()->where(['deleted' => null])->all();
        //var_dump($schedules);die();
        foreach ($schedules as $schedule) {
            $newt = new Trip();
            $newt->status_id = Trip::STATUS_ACTIVE;
            $newt->start_date = date('Y-m-d').' '.$schedule->start_time;
            $newt->end_date = date('Y-m-d').' '.$schedule->end_time;
            $newt->driver_id = $schedule->driver_id;
            $newt->car_id = $schedule->car_id;
            $newt->address_from = $schedule->address_from;
            $newt->address_to = $schedule->address_to;
            $newt->driver_avard = $schedule->driver_avard;
            $newt->isNewRecord = true;
            $newt->insert();
        }

    }

    // Получить отчёт
    public function GetReport($date1, $date2)
    {
        $data = Trip::find()
        ->select(['fio', 'cnt_trip' => new Expression('COUNT(*)'), 'sum(trip.driver_avard) AS sum_award'])
        ->join('INNER JOIN', 'driver', 'driver.id = trip.driver_id')
        ->where(['between', 'start_date',  $date1, $date2])
        ->andwhere(['status_id' => Trip::STATUS_FINISH])
        ->groupBy(['driver_id'])
        ->asArray()
        ->all();
        //->createCommand()
            ;
        // работает    
            
        /*        
        select @i:=@i+1 num, a.* 
        from
        (
        SELECT `fio`, COUNT(*) AS `cnt_trip`, sum(trip.driver_avard) AS `sum_award` 
        FROM `trip` 
        INNER JOIN `driver` ON driver.id = trip.driver_id 
        WHERE (`start_date` BETWEEN '2024-06-10' AND '2024-06-24') AND (`status_id`=1) 
        GROUP BY `driver_id`
        ) a
        , (SELECT @i:=0) X;
        */

        $result = "Отчёт о выполненных рейсах с ". $date1 ." по ". $date2 . "\r\n";
        $result .= "№п/п;фио водителя; кол-во поездок;сумарный заработок\r\n";
        //echo "<br />";
        $npp = 1;
        foreach ($data as $value) {
            $result .= $npp .
                    ';' . $value['fio'] .
                    ';' . $value['cnt_trip'] .
                    ';' . $value['sum_award'] .
                    "\r\n";
            $npp += 1;
            //var_dump($value);
            //echo "<br />";
        }

        return $result;
    }


}
