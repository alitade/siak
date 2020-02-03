<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProdukHarga;

/**
 * ProdukHargaSearch represents the model behind the search form about `app\models\ProdukHarga`.
 */
class ProdukHargaSearch extends ProdukHarga
{
    public function rules()
    {
        return [
            [['kode_produk', 'aktif', 'utgl', 'dtgl', 'Rstat'], 'safe'],
            [['harga', 'cuid', 'uuid', 'duid'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon="")
    {
        $query = ProdukHarga::find();
		if($kon){$query->where($kon);}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'harga' => $this->harga,
            'cuid' => $this->cuid,
            'uuid' => $this->uuid,
            'utgl' => $this->utgl,
            'duid' => $this->duid,
            'dtgl' => $this->dtgl,
        ]);

        $query->andFilterWhere(['like', 'kode_produk', $this->kode_produk])
            ->andFilterWhere(['like', 'aktif', $this->aktif])
            ->andFilterWhere(['like', 'Rstat', $this->Rstat]);

        return $dataProvider;
    }
}
