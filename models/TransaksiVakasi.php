<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class TransaksiVakasi extends Model
{
	
	
	#
	public $TB,$QTY,$SOAL,$NILAI,$TANDA,$PPH;
	
	#uts
    public $TGS1,$UTS,$NUTS,$AWS1,$AWS,$PR;

	
	#susulan
	
	#uas
    public $TGS2,$UAS,$NUAS,$AWS2;


    public function rules(){
        return 
		[
			[['PPH','TANDA',],'required','message'=>'Tidak Boleh Kosong'],
			[['PPH'],'number'],
            [[
				'TGS1','UTS','NUTS','AWS1','TGS2','UAS','NUAS','AWS2','AWS','PR','QTY',
				'SOAL','NILAI'
			], 'integer'],
			[['TB','TANDA',],'string']
			
        ];
    }
	
	
	
    public function attributeLabels()
    {
        return [
            'TGS1' 	=> 'Tugas 1',
            'UTS' 	=> 'UTS',
            'NUTS' 	=> 'Naskah Soal',
			'AWS1'	=> 'Pengawas(Pagi)',

            'TGS2' 	=> 'Tugas 2',
            'UAS' 	=> 'UAS',
            'NUAS' 	=> 'Naskah Soal',
			'AWS2'	=> 'Pengawas(Sore)',
			
			#'AWS2'	=> 'Pengawas(Sore)',

			
        ];
    }




}
