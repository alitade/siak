<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pembayarankrs".
 *
 * @property integer $id
 * @property string $nim
 * @property integer $semester
 * @property string $tahun
 * @property string $dpp
 * @property string $sks
 * @property string $praktek
 * @property string $sisa
 * @property string $status
 */
class KPembayarankrsSearch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pembayarankrs';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db1');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nim', 'tahun', 'status'], 'string'],
            [['semester'], 'integer'],
            [['dpp', 'sks', 'praktek', 'sisa'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nim' => 'Nim',
            'semester' => 'Semester',
            'tahun' => 'Tahun',
            'dpp' => 'Dpp',
            'sks' => 'Sks',
            'praktek' => 'Praktek',
            'sisa' => 'Sisa',
            'status' => 'Status',
        ];
    }

    public function search($params,$kon=''){
 
        $query = KPembayarankrs::find();
		if($kon!=''){
			$query->andWhere($kon);
		}
       // $query->joinWith(['mhs','mhs.people']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

     
         $dataProvider->setSort([
        'attributes' => [
            
            'fullName' => [
                'asc' => ['mhs.nim' => SORT_ASC],
                'desc' => ['mhs.nim' => SORT_DESC],
                'label' => 'Nama Lengkap',
                'default' => SORT_ASC
            ],
             
        ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'mhs_tipe' => $this->mhs_tipe,
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'mhs.nim', $this->fullName])
            ->andFilterWhere(['like', 'mhs_pass', $this->mhs_pass])
            ->andFilterWhere(['like', 'mhs_pass_kode', $this->mhs_pass_kode])
            ->andFilterWhere(['like', 'mhs_angkatan', $this->mhs_angkatan])
            ->andFilterWhere(['like', 'jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'pr_kode', $this->pr_kode])
            ->andFilterWhere(['like', 'mhs_stat', $this->mhs_stat])
            ->andFilterWhere(['like', 'ds_wali', $this->ds_wali]);
 

        return $dataProvider;
    }
	


}
