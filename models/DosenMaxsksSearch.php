<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DosenMaxsks;

/**
 * DosenMaxsksSearch represents the model behind the search form about `app\models\DosenMaxsks`.
 */
class DosenMaxsksSearch extends DosenMaxsks
{
    public function rules()
    {
        return [
            [['id', 'id_tipe', 'maxsks', 'cuid', 'uuid', 'duid'], 'integer'],
            [['tahun', 'ctgl', 'utgl', 'dtgl'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = DosenMaxsks::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_tipe' => $this->id_tipe,
            'maxsks' => $this->maxsks,
            'cuid' => $this->cuid,
            'ctgl' => $this->ctgl,
            'uuid' => $this->uuid,
            'utgl' => $this->utgl,
            'duid' => $this->duid,
            'dtgl' => $this->dtgl,
        ]);

        $query->andFilterWhere(['like', 'tahun', $this->tahun]);

        return $dataProvider;
    }
}
