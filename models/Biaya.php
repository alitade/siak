<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Biaya extends Model{

    public $vendor,$fk,$jr,$pr,$tot,$ket,$thn,$kurikulum,$jns,$jnsBayar;
    public $kDvendor,$kDfk,$kDjr,$kDpr,$kDthn;
    //
    public function rules(){
        return [
            [['ket'], 'required'],
            [['fk','jr','pr','vendor'],'integer'],
            [['vendor','thn','kurikulum'],'string'],
            [['kDvendor'],'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vendor'    => 'Konsultan',
            'fk'        => 'Fakultas',
            'jr'        => 'Jurusan',
            'pr'        => 'Program',
            'thn'        => 'Tahun',
            'tot'       => 'Total Tagihan',
            'ket'       => 'Keterangan',
        ];
    }
}
