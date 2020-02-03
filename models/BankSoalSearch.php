<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BankSoal;

/**
 * BankSoalSearch represents the model behind the search form about `app\models\BankSoal`.
 */
class BankSoalSearch extends BankSoal
{
    public function rules()
    {
        return [
            [['Id', 'jml_soal'], 'integer'],
            [['mtk_kode', 'jenis', 'tgl_upload'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = BankSoal::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'Id' => $this->Id,
            'jml_soal' => $this->jml_soal,
            'tgl_upload' => $this->tgl_upload,
        ]);

        $query->andFilterWhere(['like', 'mtk_kode', $this->mtk_kode])
            ->andFilterWhere(['like', 'jenis', $this->jenis]);

        return $dataProvider;
    }
}
