<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Angge;

/**
 * AnggeSearch represents the model behind the search form about `app\models\Angge`.
 */
class AnggeSearch extends Angge
{
    public function rules()
    {
        return [
            [['Id'], 'integer'],
            [['Fk', 'Username', 'Pass', 'PassKode', 'Tipe'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Angge::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'Id' => $this->Id,
        ]);

        $query->andFilterWhere(['like', 'Fk', $this->Fk])
            ->andFilterWhere(['like', 'Username', $this->Username])
            ->andFilterWhere(['like', 'Pass', $this->Pass])
            ->andFilterWhere(['like', 'PassKode', $this->PassKode])
            ->andFilterWhere(['like', 'Tipe', $this->Tipe]);

        return $dataProvider;
    }
}
