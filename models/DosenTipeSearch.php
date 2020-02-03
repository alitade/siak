<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DosenTipe;

/**
 * DosenTipeSearch represents the model behind the search form about `app\models\DosenTipe`.
 */
class DosenTipeSearch extends DosenTipe
{
    public function rules()
    {
        return [
            [['id', 'maxsks', 'cuid', 'uuid', 'duid'], 'integer'],
            [['tipe', 'ctgl', 'utgl', 'dtgl'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = DosenTipe::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'maxsks' => $this->maxsks,
            'cuid' => $this->cuid,
            'ctgl' => $this->ctgl,
            'uuid' => $this->uuid,
            'utgl' => $this->utgl,
            'duid' => $this->duid,
            'dtgl' => $this->dtgl,
        ]);

        $query->andFilterWhere(['like', 'tipe', $this->tipe]);

        return $dataProvider;
    }
}
