<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Biodata;

/**
 * BiodataSearch represents the model behind the search form about `app\models\Biodata`.
 */
class KonsultanOpSearch extends KonsultanOp
{


    public function scenarios(){
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function operator($params,$kode){

        $query = KonsultanOp::find()
            ->select([
                'konsultan_op.*',
                'sign'=>'iif(u.id_bio is null ,0,1)',
            ])
            ->leftJoin('user_ u',"u.id_bio=konsultan_op.id_bio")
            ->where(['id_konsultan'=>$kode])->orderBy(['id_bio'=>SORT_DESC]);
        #$query->limit=10;

        $dataProvider = new ActiveDataProvider(['query' => $query,]);

        if (!($this->load($params) && $this->validate())) {return $dataProvider;}

        return $dataProvider;
    }


}
