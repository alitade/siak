<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MatkulKrDet;

/**
 * MatkulKrDetSearch represents the model behind the search form about `app\models\MatkulKrDet`.
 */
class MatkulKrDetSearch extends MatkulKrDet
{
    public function rules()
    {
        return [
            [['kode_kr', 'kode', 'matkul', 'matkul_en', 'ctgl', 'utgl', 'dtgl', 'Rstat'], 'safe'],
            [['id', 'sks', 'cuid', 'uuid', 'duid'], 'integer'],
        ];
    }

    public function scenarios(){
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$ket="")
    {
        $query = MatkulKrDet::find();

        if($ket!=""){$query->andWhere($ket);}
        $query->orderBy(["kode"=>SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'sks' => $this->sks,
            'cuid' => $this->cuid,
            'ctgl' => $this->ctgl,
            'uuid' => $this->uuid,
            'utgl' => $this->utgl,
            'duid' => $this->duid,
            'dtgl' => $this->dtgl,
        ]);

        $query->andFilterWhere(['like', 'kode_kr', $this->kode_kr])
            ->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'matkul', $this->matkul])
            ->andFilterWhere(['like', 'matkul_en', $this->matkul_en])
            ->andFilterWhere(['like', 'Rstat', $this->Rstat]);

        return $dataProvider;
    }
}
