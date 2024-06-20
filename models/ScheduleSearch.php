<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Schedule;

/**
 * ScheduleSearch represents the model behind the search form of `app\models\Schedule`.
 */
class ScheduleSearch extends Schedule
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'driver_id', 'car_id'], 'integer'],
            [['start_time', 'end_time', 'address_from', 'address_to'], 'safe'],
            [['driver_avard'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Schedule::find()
            -> where(['deleted' => null]);
        ;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'driver_id' => $this->driver_id,
            'car_id' => $this->car_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'driver_avard' => $this->driver_avard,
        ]);

        $query->andFilterWhere(['like', 'address_from', $this->address_from])
            ->andFilterWhere(['like', 'address_to', $this->address_to]);

        return $dataProvider;
    }
}
