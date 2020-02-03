<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Phone;

/**
 * PhoneSearch represents the model behind the search form about `app\models\Phone`.
 */
class PhoneSearch extends Phone
{
    public function rules()
    {
        return [
            [['Id'], 'integer'],
            [['mhs_nim', 'phone', 'aktif'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Phone::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'Id' => $this->Id,
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'aktif', $this->aktif]);

        return $dataProvider;
    }
}
