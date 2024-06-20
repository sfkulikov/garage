<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trip_status".
 *
 * @property int $id
 * @property string $status_name
 *
 * @property Trip[] $trips
 */
class TripStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trip_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_name'], 'required'],
            [['id'], 'integer'],
            [['status_name'], 'string', 'max' => 50],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status_name' => 'Status Name',
        ];
    }

    /**
     * Gets query for [[Trips]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrips()
    {
        return $this->hasMany(Trip::class, ['status_id' => 'id']);
    }
}
