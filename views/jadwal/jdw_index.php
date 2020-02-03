<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Funct;

$this->title = 'Jadwal Perkuliahan';
$this->params['breadcrumbs'][] = $this->title;

$JR = array_flip($subAkses['jurusan']);
#Funct::v($JR);

?>
<div class="jadwal-index">
<div class="angge-search">
    <?php $form = ActiveForm::begin(['method' => 'get',]); ?>
<div class="panel panel-primary">
	<div class="panel-heading"><?= $this->title ?></div>
	<div class="panel-body">
    <?= 
		$form->field($searchModel, 'kr_kode')->widget(Select2::classname(), [
			'data' =>app\models\Funct::AKADEMIK(),
			'language' => 'en',
			'options' => ['placeholder' => 'Tahun Akademik','required'=>'required'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	 ?>
    <?= 
		$form->field($searchModel, 'jr_id')->widget(Select2::classname(), [
			'data' =>app\models\Funct::JURUSAN(1,($subAkses['jurusan']?['jr_id'=>$subAkses['jurusan']]:"")),
			'language' => 'en',
			'options' => ['placeholder' => 'Jurusan'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	 ?>
	 
    <?php // echo $form->field($model, 'Tipe') ?>

    <div class="form-group" style="text-align: right;">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Cari', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['akademik/jdw'],['class' => 'btn btn-danger']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
</div>
<br /><br />

    <?php 
	if(isset($_GET['JadwalSearch']['kr_kode']) && !empty($_GET['JadwalSearch']['kr_kode'])){
	//echo Html::a('<i class="glyphicon glyphicon-refresh"></i> Download PDF',Url::to().'&c=1',['class' => 'btn btn-danger','target'=>'_blank']);
	//Pjax::begin(); 
	//Menampilkan <b>{begin, number}-{end, number}</b> dari <b>{totalCount, number}</b> {totalCount, plural, one{item} other{item}}
	echo $d= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary'	=> 'Menampilkan {begin, number}-{end, number} dari {totalCount, number}',
        'toolbar'=> [
            ['content'=>
                Html::a(' <i class="fa fa-download"></i> PDF',Url::to(['index','c'=>1]).'&'.$_SERVER['QUERY_STRING'],['class' => 'btn btn-primary','target'=>'_blank'])
				.' '.Html::a(' <i class="fa fa-download"></i> Excel',Url::to(['excel','t'=>1]).'&'.$_SERVER['QUERY_STRING'],['class' => 'btn btn-primary','target'=>'_blank'])
            ],
            '{toggleData}',
            #(!Funct::acc('/gridview/export/*')?"":'{export}'),
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'	=> 'jr_id',
				'width'=>'20%',
				'value'		=> function($model){return $model->jr_jenjang." ".$model->jr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
				'group'=>true,  // enable grouping,
				'groupedRow'=>true,                    // move grouped column to a single grouped row
				'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
				'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
			],			
			[
				'attribute'	=> 'pr_kode',
				'width'=>'10%',
				'value'		=> function($model){return @$model->pr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Program-'],
				'group'=>true,  // enable grouping
				'subGroupOf'=>1, // supplier column index is the parent group,
				'pageSummary'=>'Total',
			],			
			[
				'attribute'=>'jdwl_hari',
				'value'=>function($model){
					return @app\models\Funct::HARI()[@$model->jdwl_hari];
				},
				'filter'=>@app\models\Funct::HARI(),
			],
			[
				'attribute'=>'jdwl_masuk',
				'value'=>function($model){return $model->jadwal;},
				'contentOptions'=>['class'=>'col-xs-1',],
			],
			[
				'attribute'=>'jdwl_kls',
				'width'=>'5%',
				'contentOptions'=>['class'=>'row col-xs-1',],
			],
			[
				'attribute'=>'mtk_nama',
				'value'=>function($model){return Html::decode($model->mtk_nama);}
			],
			[
				'label'=>'SKS',
				'value'=>function($model){
					return Html::decode($model->bn->mtk->mtk_sks);
				},
				'pageSummary'=>true,
				
			],
			[
				'attribute'=>'ds_nm',
				'filter'=>true,
				
			],
			
			[
				'attribute'=>'rg_kode',
				'width'=>'10%',
				'value'		=> function($model){return $model->rg->rg_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::RUANG(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'...'],
				
			],
            [
                'attribute'=>'jumabs',
                'format'=>'raw',
                'header'=>'<i class="fa fa-users"></i>',
                'width'=>'5%',
                'value'=>function($model){
                    return Html::a($model->jum,
                        Yii::$app->urlManager->createUrl(
                            ['akademik/jdw-detail','id' => $model->jdwl_id]),['title' => Yii::t('yii', 'Detail'),]
                    );
                },


            ],
			[
				'attribute'=>'TotSesi',
				'format'=>'raw',
				'header'=>'&sum;S.',
				'width'=>'1%',
				'value'=>function($model){
					return $model->TotSesi;
				},
			],
			[
				'attribute'=>'uts',
				'format'=>'raw',
				'width'=>'1%',
				'value'=>function($model){
					return "<center>".($model->uts>0 ? 
					'<i class="glyphicon glyphicon-ok"></i></i>':
					'<i class="glyphicon glyphicon-remove"></i>')."</center>";
				},
				'filter'=>['N','Y'],
			],
			[
				'attribute'=>'uas',
				'format'=>'raw',
				'width'=>'1%',
				'value'=>function($model){
					return "<center>".($model->uas>0 ? 
					'<i class="glyphicon glyphicon-ok"></i></i>':
					'<i class="glyphicon glyphicon-remove"></i>')."</center>";
				},
				'filter'=>['N','Y'],
			],
			[
				'header'=>'<center>Nil.</center>',
				'mergeHeader'=>true,
				'format'=>'raw',
				'value'=>function($model){
					return "<center>".(\app\models\Funct::StatNilDos($model->jdwl_id)>0 ? 
					'<i class="glyphicon glyphicon-ok"></i></i>':
					'<i class="glyphicon glyphicon-remove"></i>')."</center>";
				},
				
			],
			[
                'header'=>'<center>Transkrip</center>',
                'mergeHeader'=>true,
                'format'=>'raw',
                'value'=>function($model){

	                if(Funct::acc('/transkrip/nilai/dok-nil')){
                        return "<center>".( $model->Lock>=64 ?
                                Html::a('<i class="glyphicon glyphicon-ok"></i>',['/transkrip/nilai/dok-nil','id'=>$model->jdwl_id]
                                    ,['title'=>'Transfer Transkrip','target'=>'_blank']
                                ):
                                (\app\models\Funct::StatNilDos($model->jdwl_id)>0?
                                    Html::a('<i class="glyphicon glyphicon-remove"></i>',['/transkrip/nilai/dok-nil','id'=>$model->jdwl_id]
                                        ,['title'=>'Transfer Transkrip','target'=>'_blank']
                                    ):'<i class="glyphicon glyphicon-remove"></i>'

                                )
                            )."</center>";
                    }
                    return "<center>".( $model->Lock>=64 ?'<i class="glyphicon glyphicon-ok"></i>':(\app\models\Funct::StatNilDos($model->jdwl_id)>0?'<i class="glyphicon glyphicon-remove"></i>':'<i class="glyphicon glyphicon-remove"></i>'))."</center>";


                },
                #'visible'=>Funct::acc('/transkrip/nilai/dok-nil')

            ],


			[
				'class'=>'kartik\grid\ActionColumn',
				'template'=>
					#global
					'
						<li>{dtl}</li>
						<li>{edt}</li>
					'
					#Dirit
					.'
						<li>{pergantian}</li>
						<li>{c_ganti}</li>
						<li>{fm_awal}</li>
						<li>{fp_awal}</li>						
						<li>{a_pdf}</li>						
					'
					#Piket
					.'
					<li>{p_abs}</li>
					<li>{manual}</li>
					<li>{split}</li>
					<li>{a_abs}</li>
					<li>{r_abs}</li>
					<li>{u_ujian}</li>
					<li>{c_uts}</li>
					<li>{c_uts1}</li>
					<li>{c_uas}</li>
					<li>{c_uas1}</li>
					
					<li>{a_pdf}</li>
					<li>{c_abs}</li>
					<li>{rekap}</li>
					<li>{hps}</li>
					<li>{nilai}</li>
					<li>{bobot}</li>
					'
				,
				'buttons' => [
					'hps'=> function ($url, $model, $key) use($JR) {
							if(!Funct::acc('/jadwal/delete')){return false;}
							if($model->jum>0){return false;}
							if( $JR && !isset($JR[$model->bn->kln->jr_id]) ){return false;}

							return Html::a('<i class="glyphicon glyphicon-trash"></i> Hapus',['/jadwal/delete','id' => $model->jdwl_id],['onClick'=>'return confirm("Hapus Data Ini?")']);
					},
					#=========== DIRIT ========
					'fp_awal'=> function ($url, $model, $key) {
						if(!Funct::acc('/ptrx-finger/form')){return false;}
						return Html::a('<i class="fa fa-file"></i> Form Pulang Awal'
						,['/trx-finger/form','id' => $model->jdwl_id,'view'=>'t','t'=>'1'],['target'=>'_blank']);
					},
					'fm_awal'=> function ($url, $model, $key) {
							return false;
							return Html::a('<i class="glyphicon glyphicon-file"></i> Form Masuk Awal'
							,['trx-finger/form','id' => $model->jdwl_id,'view'=>'t','t'=>'2'],['target'=>'_blank']);
					},
					'pergantian'=> function ($url, $model, $key) {
						if(!Funct::acc('/perkuliahan/pergantian')){return false;}
						return Html::a(
							'<span class="fa fa-exchange"></span> Pergantian Perkuliahan',
							['/perkuliahan/pergantian','id' => $model->jdwl_id]
						);
					},
					'c_ganti'=> function ($url, $model, $key) {
						if(!Funct::acc('/perkuliahan/cek-pergantian')){return false;}
						return Html::a(
							'<span class="glyphicon glyphicon-list"></span> Cek Jadwal Pergantian',
                        ['/perkuliahan/cek-pergantian','id' => $model->jdwl_id, 'jenis'=>2]
						);
					},
					#--
					'split'=> function ($url, $model, $key)use($JR) {
						if(!Funct::acc('/krs/split-perkuliahan')){return false;}
						if($JR && !isset($JR[$model->bn->kln->jr_id])){return false;}

						return Html::a(
							'<span class="glyphicon glyphicon-list"></span> Split Peserta',
							['/krs/split-perkuliahan','id' => $model->jdwl_id]
						);
					},
                    #nilai
                    'nilai'=> function ($url, $model, $key) {
                        //return false;
                        if(!Funct::acc('/pengajar/nilait')){return false;}
                        return Html::a('<span class="fa fa-pencil-square-o"></span>Input Nilai',['/pengajar/nilait','id' => $model->jdwl_id],['target'=>'_blank']);
                    },
                    'bobot'=> function ($url, $model, $key) {
                        //return false;
                        if(!Funct::acc('/jadwal/bobot')){return false;}
                        return Html::a('<span class="fa fa-pencil-square-o"></span> Bobot Nilai',['/jadwal/bobot','id' => $model->jdwl_id],['target'=>'_blank']);
                    },
					#--
										
					#====PIKET====
					'p_abs'=> function ($url, $model, $key) {
						if(!Funct::acc('/perkuliahan/cetak-peserta')){return false;}
						return Html::a(
							'<span class="fa fa-print"></span> Peserta Perkuliahan',
							['/perkuliahan/cetak-peserta','id' => $model->jdwl_id, 'sort'=>'id']
						);
					},
					'manual'=> function ($url, $model, $key) {
						if(!Funct::acc('/perkuliahan/cetak-peserta-v2')){return false;}
						return Html::a(
							'<span class="fa fa-print"></span> Absen Manual',
							['/perkuliahan/cetak-peserta-v2','id' => $model->jdwl_id, 'sort'=>'id']
						);
					},
					'r_abs'=> function ($url, $model, $key) {
						if(!Funct::acc('/prodi/cetak-absen')){return false;}
						return Html::a(
							'<span class="fa fa-print"></span> Rekap Absensi',
							['/prodi/cetak-absen','id' => $model->jdwl_id,],
							['target'=>"_blank"]
						);
					},

					'u_ujian'=> function ($url, $model, $key) {
							if(!Funct::acc('/perkuliahan/jdw-update')){return false;}
							$model->jdwl_kls;
							if($model->lolos==0){if($model->uts==0){return false;}}
							return Html::a(
								'<span class="fa fa-pencil"></span>Update Jadwal Ujian',
								['/perkuliahan/jdw-update','id' => $model->jdwl_id]
							);
						},
					'c_uts'=> function ($url, $model, $key) {
							if(!Funct::acc('/form/cetak-absensi')){return false;}
							$model->jdwl_kls;
							if($model->lolos==0 && Yii::$app->vd->vd()['vdSesi']==1){
							    if($model->TotSesi< $model->uts){return false;}
							}
							return Html::a(
								'<span class="fa fa-print"></span>Absen UTS',
								//['perkuliahan/cetak-absensi','id' => $model->jdwl_id, 'jenis'=>1]
								['/form/cetak-absensi','id' => $model->jdwl_id, 'jenis'=>1]
							);
						},
					'c_uas'=> function ($url, $model, $key) {
                        if(!Funct::acc(	'/form/cetak-absensi')){return false;}
                        $model->jdwl_kls;
                        if($model->lolos==0 && Yii::$app->vd->vd()['vdSesi']==1){
                            if($model->TotSesi< $model->uas){return false;}
                        }
                        return Html::a(
                            '<span class="fa fa-print"></span> Absen UAS',
                            //['perkuliahan/cetak-absensi','id' => $model->jdwl_id, 'jenis'=>2]
                            ['/form/cetak-absensi','id' => $model->jdwl_id, 'jenis'=>2]
                        );
                    },
					'c_uts1'=> function ($url, $model, $key) {
                        if(!Funct::acc('/perkuliahan/cetak-absensi-ujian')){return false;}
                        if($model->lolos==0 && Yii::$app->vd->vd()['vdSesi']==1){
                            if($model->TotSesi< $model->uts){return false;}
                        }

                        return Html::a(
                            '<span class="fa fa-print"></span> Absen UTS Printer LX310',
                            ['/perkuliahan/cetak-absensi-ujian','id' => $model->jdwl_id, 'jenis'=>1]
                        );
                    },
					'c_uas1'=> function ($url, $model, $key) {
                        if(!Funct::acc('/perkuliahan/cetak-absensi-ujian')){return false;}
                        if($model->lolos==0 && Yii::$app->vd->vd()['vdSesi']==1){
                            if($model->TotSesi< $model->uas){return false;}
                        }
                        return Html::a(
								'<span class="fa fa-print"></span> Absen UAS Printer LX310',
								['/perkuliahan/cetak-absensi-ujian','id' => $model->jdwl_id, 'jenis'=>2]
							);
						},
					#===================					
					
					'dtl'=> function ($url, $model, $key) {
							if(!Funct::acc('/jadwal/view')){return false;}
							return Html::a('<i class="glyphicon glyphicon-eye-open"></i> Detail'
							,['/jadwal/view','id' => $model->jdwl_id,'view'=>'t']);
						},
					'edt'=> function ($url, $model, $key) use($JR) {
							if(!Funct::acc('/jadwal/gab-update')){return false;}
							if($model->mhs >0){return false;}
							if($JR && !isset($JR[$model->bn->kln->jr_id])){return false;}

							return Html::a(
								'<span class="glyphicon glyphicon-list"></span> Edit Jadwal',
								['/jadwal/gab-update','id' => $model->jdwl_id,'view'=>'t']
							);
						},

					'a_pdf'=> function ($url, $model, $key) {
							if(!Funct::acc('/dirit/absen-pdf')){return false;}
							return Html::a(
								'<span class="glyphicon glyphicon-list"></span> Absensi Harian PDF',
								['dirit/absen-pdf','id' => $model->jdwl_id, 'jenis'=>3],['target'=>'_blank']
							);
						},

					/*	
					'a_pdf'=> function ($url, $model, $key) {
						#if(!Funct::acc('/perkuliahan/cetak-absen')){return false;}
						return Html::a(
							'<span class="glyphicon glyphicon-list"></span> Absensi PDF',
							['perkuliahan/cetak-absen','id' => $model->jdwl_id, 'jenis'=>2]
						);
					},
					*/
					'a_abs'=> function ($url, $model, $key) {
						if(!Funct::acc('/dosen/absensi')){return false;}						
						return Html::a(
							'<span class="fa fa-pencil-square-o"></span> Input Absen',
							['dosen/absensi','id' => $model->jdwl_id, 'matakuliah'=>$model->mtk_kode, 'sort'=>'id']
						);

						return Html::a(
							'<span class="glyphicon glyphicon-list"></span> Input Absen',
							['bisa/absensi','id' => $model->jdwl_id, 'matakuliah'=>$model->mtk_kode, 'sort'=>'id']
						);
					},
					#==== end Piket					
					/*
					'c_abs'=> function ($url, $model, $key) {
							#if(!Funct::acc('/akademik/cetak-absen')){return false;}
							return Html::a(
								'<span class="glyphicon glyphicon-list"></span> Cetak Absen Harian',
								['akademik/cetak-absen','id' => $model->jdwl_id, 'jenis'=>2]
							);
						},
					*/
					'rekap'=> function ($url, $model, $key) {
						if(!Funct::acc('/prodi/cetak-absens')){return false;}
						return Html::a(
							'<span class="fa fa-print"></span> Rekap Absen	',
							['/prodi/cetak-absen','id' => $model->jdwl_id, 'jenis'=>2]
							//['form/cetak-absensi','id' => $model->jdwl_id, 'jenis'=>2]
						);
					},
						
				],
				'dropdown'=>true,
				'dropdownOptions'=>['class'=>'pull-right'],
				'headerOptions'=>['class'=>'kartik-sheet-style'],
			],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
		'showPageSummary'=>true, 
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'<i class="fa fa-navicon"></i> Jadwal Kuliah',
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['jdw'], ['class' => 'btn btn-info']),
    	]
    ]); //Pjax::end(); 



	}
	?>

</div>
