<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LAbsenDosen;

/**
 * LAbsenDosenSearch represents the model behind the search form about `app\models\LAbsenDosen`.
 */
class LAbsenDosenSearch extends LAbsenDosen
{
    public function rules()
    {
        return [
            [['id', 'cuid', 'uuid', 'duid'], 'integer'],
            [['kode', 'kr_kode', 'tipe', 'tgl_awal', 'tgl_akhir', 'ctgl', 'utgl', 'dtgl', 'RStat'], 'safe'],
        ];
    }

    public function scenarios(){
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon='' ){
        $query = LAbsenDosen::find()
        ->orderBy(['ctgl'=>SORT_DESC]);

        if($kon!=''){$query->andWhere($kon);}

        $dataProvider = new ActiveDataProvider(['query' => $query,]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tgl_awal' => $this->tgl_awal,
            'tgl_akhir' => $this->tgl_akhir,
            'cuid' => $this->cuid,
            'ctgl' => $this->ctgl,
            'uuid' => $this->uuid,
            'utgl' => $this->utgl,
            'duid' => $this->duid,
            'dtgl' => $this->dtgl,
        ]);

        $query->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'kr_kode', $this->kr_kode])
            ->andFilterWhere(['like', 'tipe', $this->tipe])
            ->andFilterWhere(['like', 'RStat', $this->RStat]);

        return $dataProvider;
    }
}
