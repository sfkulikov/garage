<?php

namespace app\models;

use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "car".
 *
 * @property int $id
 * @property string $model
 * @property string $car_number
 * @property int $car_type_id
 * @property string $deleted
 *
 * @property CarType $carType
 * @property Schedule[] $schedules
 * @property Trip[] $trips
 */
class Car extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model', 'car_number', 'car_type_id'], 'required'],
            [['car_type_id'], 'integer'],
            [['deleted'], 'safe'],
            [['model'], 'string', 'max' => 50],
            [['car_number'], 'string', 'max' => 15],
            [['car_number'], 'unique'],
            [['car_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarType::class, 'targetAttribute' => ['car_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Модель',
            'car_number' => 'Номер',
            'car_type_id' => 'Тип автомобиля',
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
     * Gets query for [[CarType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarType()
    {
        return $this->hasOne(CarType::class, ['id' => 'car_type_id']);
    }

    /**
     * Gets query for [[Schedules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchedules()
    {
        return $this->hasMany(Schedule::class, ['car_id' => 'id']);
    }

    /**
     * Gets query for [[Trips]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrips()
    {
        return $this->hasMany(Trip::class, ['car_id' => 'id']);
    }

    public function getCarTypeText()
    {
        return CarType::findOne($this->car_type_id)->type_name;
    }

}
