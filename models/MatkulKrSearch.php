<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MatkulKr;

/**
 * MatkulKrSearch represents the model behind the search form about `app\models\MatkulKr`.
 */
class MatkulKrSearch extends MatkulKr
{
    public function rules()
    {
        return [
            [['kode', 'ket', 'ctgl', 'utgl', 'dtgl', 'Rstat', 'lock', 'aktif','jr_id'], 'safe'],
            [['totSks', 'cuid', 'uuid', 'duid'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon=""){
        $query = MatkulKr::find();
        if($kon!=""){
            $query->andWhere($kon);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'jr_id' => $this->jr_id,
            'totSks' => $this->totSks,
            'cuid' => $this->cuid,
            'ctgl' => $this->ctgl,
            'uuid' => $this->uuid,
            'utgl' => $this->utgl,
            'duid' => $this->duid,
            'dtgl' => $this->dtgl,
        ]);

        $query->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'Rstat', $this->Rstat])
            ->andFilterWhere(['like', 'lock', $this->lock])
            ->andFilterWhere(['like', 'aktif', $this->aktif]);

        return $dataProvider;
    }
}
