<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fakultas;

/**
 * FakultasSearch represents the model behind the search form about `app\models\Fakultas`.
 */
class FakultasSearch extends Fakultas
{
    public function rules()
    {
        return [
            [['fk_id', 'fk_nama', 'RStat'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Fakultas::find()->where(" (RStat='0' or RStat is null) ");;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'fk_id', $this->fk_id])
            ->andFilterWhere(['like', 'fk_nama', $this->fk_nama])
            ->andFilterWhere(['like', 'RStat', $this->RStat]);

        return $dataProvider;
    }
}
