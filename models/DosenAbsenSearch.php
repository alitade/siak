<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DosenAbsen;

/**
 * DosenAbsenSearch represents the model behind the search form about `app\models\DosenAbsen`.
 */
class DosenAbsenSearch extends DosenAbsen
{
    public function rules()
    {
        return [
            [['id', 'jdwl_id', 'ds_id'], 'integer'],
            [['sesi', 'tgl_absen', 'masuk', 'keluar', 'RStat', 'status'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = DosenAbsen::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'jdwl_id' => $this->jdwl_id,
            'ds_id' => $this->ds_id,
            'tgl_absen' => $this->tgl_absen,
        ]);

        $query->andFilterWhere(['like', 'sesi', $this->sesi])
            ->andFilterWhere(['like', 'masuk', $this->masuk])
            ->andFilterWhere(['like', 'keluar', $this->keluar])
            ->andFilterWhere(['like', 'RStat', $this->RStat])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
