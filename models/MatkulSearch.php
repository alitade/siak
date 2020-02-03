<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Matkul;

/**
 * MatkulSearch represents the model behind the search form about `app\models\Matkul`.
 */
class MatkulSearch extends Matkul
{
    public function rules()
    {
        return [
            [['mtk_kode', 'mtk_nama', 'mtk_kat', 'mtk_stat', 'jr_id', 'penanggungjawab', 'mtk_sesi', 'mtk_sub', 'mtk_semester', 'mtk_jenis'], 'safe'],
            [['mtk_sks'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon='')
    {
        $query = Matkul::find()->where(" (RStat='0' or RStat is null) ");
		if($kon!=''){$query->andWhere($kon);}
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'mtk_sks' => $this->mtk_sks,
        ]);

        $query->andFilterWhere(['like', 'mtk_kode', $this->mtk_kode])
            ->andFilterWhere(['like', 'mtk_nama', $this->mtk_nama])
            ->andFilterWhere(['like', 'mtk_kat', $this->mtk_kat])
            ->andFilterWhere(['like', 'mtk_stat', $this->mtk_stat])
            ->andFilterWhere(['like', 'jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'penanggungjawab', $this->penanggungjawab])
            ->andFilterWhere(['like', 'mtk_sesi', $this->mtk_sesi])
            ->andFilterWhere(['like', 'mtk_sub', $this->mtk_sub])
            ->andFilterWhere(['like', 'mtk_semester', $this->mtk_semester])
            ->andFilterWhere(['like', 'mtk_jenis', $this->mtk_jenis]);

        return $dataProvider;
    }
}
