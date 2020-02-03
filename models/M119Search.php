<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Gedung;

/**
 * GedungSearch represents the model behind the search form about `app\models\Gedung`.
 */
class M119Search extends M119
{
    public function rules()
    {
        return [
            [['Id', 'Lantai'], 'integer'],
            [['Name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Gedung::find()->where(" (RStat='0' or RStat is null) ");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'Id' => $this->Id,
            'Lantai' => $this->Lantai,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name]);

        return $dataProvider;
    }
}
