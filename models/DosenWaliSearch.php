<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DosenWali;

/**
 * DosenWaliSearch represents the model behind the search form about `app\models\DosenWali`.
 */
class DosenWaliSearch extends DosenWali{
    public function rules()
    {
        return [
            [['jr_id', 'aktif', 'ctgl', 'utgl', 'dtgl','dosen'], 'safe'],
            [['ds_id', 'cuid', 'uuid', 'duid'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params){
        $query = DosenWali::find();
        $dataProvider = new ActiveDataProvider(['query' => $query,]);

        if (!($this->load($params) && $this->validate())) {return $dataProvider;}

        $query->andFilterWhere([
            'ds_id' => $this->ds_id,
            'cuid' => $this->cuid,
            'ctgl' => $this->ctgl,
            'uuid' => $this->uuid,
            'utgl' => $this->utgl,
            'duid' => $this->duid,
            'dtgl' => $this->dtgl,
        ]);

        $query->andFilterWhere(['like', 'jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'aktif', $this->aktif]);

        return $dataProvider;
    }

    public function cari($params){
        $query = DosenWali::find()
        ->select([
            "dosen_wali.*",
            'dosen'=>"ds.ds_nm",
        ])
        ->innerJoin("tbl_dosen ds","(ds.ds_id=dosen_wali.ds_id and isnull(ds.RStat,0)=0)");

        $dataProvider = new ActiveDataProvider(['query' => $query,]);

        if (!($this->load($params) && $this->validate())) {return $dataProvider;}

//        $query->andFilterWhere([
//            'cuid' => $this->cuid,
//            'ctgl' => $this->ctgl,
//            'uuid' => $this->uuid,
//            'utgl' => $this->utgl,
//            'duid' => $this->duid,
//            'dtgl' => $this->dtgl,
//        ]);

        $query->andFilterWhere(['like', 'jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'aktif', $this->aktif])
            ->andFilterWhere(['like', 'ds.ds_nm', $this->dosen]);

        return $dataProvider;
    }


}
