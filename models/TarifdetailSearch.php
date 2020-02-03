<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tarifdetail;

/**
 * TarifdetailSearch represents the model behind the search form about `app\modules\keuangan\models\Tarifdetail`.
 */
class TarifdetailSearch extends Tarifdetail
{
    public function rules()
    {
        return [
            [['id', 'dpp', 'sks', 'praktek', 'urutan'], 'integer'],
            [['idtarif', 'tipe', 'cc'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon='')
    {
        $query = Tarifdetail::find();
        if($kon!=''){
            $query->andWhere($kon);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'dpp' => $this->dpp,
            'sks' => $this->sks,
            'praktek' => $this->praktek,
            'urutan' => $this->urutan,
        ]);

        $query->andFilterWhere(['like', 'idtarif', $this->idtarif])
            ->andFilterWhere(['like', 'tipe', $this->tipe])
            ->andFilterWhere(['like', 'cc', $this->cc]);

        return $dataProvider;
    }
}
