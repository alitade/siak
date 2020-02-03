<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AbsenAwal;

/**
 * AbsenAwalSearch represents the model behind the search form about `app\models\AbsenAwal`.
 */
class AbsenAwalSearch extends AbsenAwal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'GKode', 'jdwl_masuk', 'jdwl_keluar', 'tgl', 'ds_masuk', 'ds_keluar', 'tipe'], 'safe'],
            [['ds_fid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = AbsenAwal::find();

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
            'jdwl_masuk' => $this->jdwl_masuk,
            'jdwl_keluar' => $this->jdwl_keluar,
            'tgl' => $this->tgl,
            'ds_masuk' => $this->ds_masuk,
            'ds_keluar' => $this->ds_keluar,
            'ds_fid' => $this->ds_fid,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'GKode', $this->GKode])
            ->andFilterWhere(['like', 'tipe', $this->tipe]);

        return $dataProvider;
    }
}
