<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TKrsHead;

/**
 * TKrsHeadSearch represents the model behind the search form about `app\models\TKrsHead`.
 */
class TKrsHeadSearch extends TKrsHead
{
    public function rules()
    {
        return [
            [['kode', 'nim', 'kr_kode', 'status', 'app', 'app_date', 'ctgl', 'utgl', 'dtgl', 'Rstat'], 'safe'],
            [['id', 'ds_id', 'cuid', 'uuid', 'duid'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TKrsHead::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ds_id' => $this->ds_id,
            'app_date' => $this->app_date,
            'cuid' => $this->cuid,
            'ctgl' => $this->ctgl,
            'uuid' => $this->uuid,
            'utgl' => $this->utgl,
            'duid' => $this->duid,
            'dtgl' => $this->dtgl,
        ]);

        $query->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'nim', $this->nim])
            ->andFilterWhere(['like', 'kr_kode', $this->kr_kode])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'app', $this->app])
            ->andFilterWhere(['like', 'Rstat', $this->Rstat]);

        return $dataProvider;
    }
}
