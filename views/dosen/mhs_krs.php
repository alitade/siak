<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use app\models\KrsSearch;

use yii\db\Query;
use yii\data\ArrayDataProvider;

/**
 * @var yii\web\View $this
 * @var app\models\Mahasiswa $model
 */	
		$kode = (empty($Kurikulum)) ? 'OBED' : $Kurikulum->kr_kode;

		$searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());
		
		//$ModKrs = Krs::find()->
		$query = new Query;
		$query
			->select('kln.kr_kode,krs.krs_stat, krs.krs_id, krs.mhs_nim , bn.mtk_kode ,mk.mtk_nama, mk.mtk_sks, jd.jdwl_masuk,jd.jdwl_keluar,jd.jdwl_kls')
			->from('tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn, tbl_kalender kln, tbl_matkul mk')
			->where("
				krs.jdwl_id = jd.jdwl_id 
				and bn.id=jd.bn_id
				and bn.mtk_kode=mk.mtk_kode
				and bn.kln_id=kln.kln_id
				and mhs_nim='$model[mhs_nim]'
				and kln.kr_kode = '$kode'
				and (krs.RStat='0' or krs.RStat is null )
			")
			->orderBy([
				'substring(kln.kr_kode,2,4)'=>SORT_ASC,
				'substring(kln.kr_kode,1,1)'=>SORT_ASC,
				'bn.mtk_kode'=>SORT_ASC]
				);

		$command = $query->createCommand();
		//print_r($command->getRawSql());
		$data = $command->queryAll();  		


		$n=0;
		$kod="";
		$kod="";
		$ITEM=array();
		$InfTahun="";
		$TotKrs=0;
		$TotGrade=0;
		foreach($data as $d){
			 
			$InfTahun=$d['kr_kode'];
			$ITEM[$n]['id']=$d['krs_id'];
			$ITEM[$n]['Kode']=$d['mtk_kode'];
			$ITEM[$n]['Matakuliah']=$d['mtk_nama'];
			$ITEM[$n]['SKS']	= $d['mtk_sks'];
			$ITEM[$n]['nim']	= $d['mhs_nim'];
			$ITEM[$n]['jdwl_masuk']	= $d['jdwl_masuk'];
			$ITEM[$n]['jdwl_keluar']	= $d['jdwl_keluar'];
			$ITEM[$n]['jdwl_kls']	= $d['jdwl_kls'];
			$ITEM[$n]['stat']	= $d['krs_stat'];
			 
			$ITEM[$n]['no']	= ($n+1);
			$TotKrs+=$ITEM[$n]['SKS'];
			$n++;

		}
		@$IPK =0;
		$dataProvider = new ArrayDataProvider([
			'key' => 'id',
			'allModels'=>$ITEM,
			'pagination' => [
        		'pageSize' => 0,
    		],

		]);

?>
<div class="mahasiswa-view">
    <?php 
	echo 
	GridView::widget([
		'dataProvider'=>$dataProvider,
		'id'	=> 'childGrid_'.$model->mhs_nim,
		'columns' => [
			
			['class'=>'kartik\grid\SerialColumn'],
			'id',
			'Matakuliah',
			[
				'attribute'=>'SKS',
				'label'=>'SKS',
				'pageSummary'=>true,
				'pageSummaryFunc'=>GridView::F_SUM,
				'footer'=>true	
			],
			[
				'label'	=> 'Jadwal',
				 'value'	=> function($model){
				 	return @$model['jdwl_masuk'] .' - '.@$model['jdwl_keluar'];
				 }
			],
			[
				'label'	=> 'Kelas',
				'value'	=> function($model){
				 	return @$model['jdwl_kls'];
				 }
			],			 
			[
				'label' => 'Status',
				'attribute' => 'stat',
				'format' => 'raw',
				'width' => '25px',
				'value'	=> function($model){
					return ($model['stat'] == '2') ? '<span class="label label-warning">DITOLAK</span>' : '<span class="label label-warning">PENDING</span>';
				}
			],
			['class' => '\kartik\grid\CheckboxColumn',
						'checkboxOptions' => function ($model, $key, $index, $column) {
							    return ['value' => 'ASD','class' => 'icheckbox_minimal','name' => 'detailKRS[]'];
							},
					 'options' => ['class' => 'icheckbox_minimal','name' => 'detailKRS[]']

			], 

			
		],

        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>FALSE,
		'showPageSummary' => FALSE,
       /* 'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-navicon"></i> Kode Kurikulum :'.$InfTahun,
        ]*/
		
	]); 
 	?>


</div>
