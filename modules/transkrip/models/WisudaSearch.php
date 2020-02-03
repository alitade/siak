<?php

namespace app\modules\transkrip\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transkrip\models\Wisuda;

/**
 * WisudaSearch represents the model behind the search form about `app\modules\transkrip\models\Wisuda`.
 */
class WisudaSearch extends Wisuda
{
    public function rules()
    {
        return [
            [['id', 'ds_id_', 'no_urut'], 'integer'],
            [['jr_id', 'npm', 'nama', 'mtk_kode', 'pembimbing', 'skripsi_indo', 'skripsi_end', 'kode', 'tgl_lulus', 'predikat', 'nilai', 'pejabat1', 'pejabat2', 'tgl_cetak', 'tgl'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Wisuda::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ds_id_' => $this->ds_id_,
            'no_urut' => $this->no_urut,
            'tgl_lulus' => $this->tgl_lulus,
            'tgl_cetak' => $this->tgl_cetak,
            'tgl' => $this->tgl,
        ]);

        $query->andFilterWhere(['like', 'jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'npm', $this->npm])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'mtk_kode', $this->mtk_kode])
            ->andFilterWhere(['like', 'pembimbing', $this->pembimbing])
            ->andFilterWhere(['like', 'skripsi_indo', $this->skripsi_indo])
            ->andFilterWhere(['like', 'skripsi_end', $this->skripsi_end])
            ->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'predikat', $this->predikat])
            ->andFilterWhere(['like', 'nilai', $this->nilai])
            ->andFilterWhere(['like', 'pejabat1', $this->pejabat1])
            ->andFilterWhere(['like', 'pejabat2', $this->pejabat2]);

        return $dataProvider;
    }
}
