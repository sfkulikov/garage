<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "car_type".
 *
 * @property int $id
 * @property string $type_name
 *
 * @property Car[] $cars
 */
class CarType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type_name'], 'required'],
            [['id'], 'integer'],
            [['type_name'], 'string', 'max' => 15],
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
            'type_name' => 'Type Name',
        ];
    }

    /**
     * Gets query for [[Cars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCars()
    {
        return $this->hasMany(Car::class, ['car_type_id' => 'id']);
    }
}
