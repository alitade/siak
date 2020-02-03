<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Funct;
use kartik\select2\Select2;
use kartik\editable\Editable;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\BobotNilaiSearch $searchModel
 */

$this->title = 'Jadwal Mengajar';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-primary">
    <div class="panel-heading"></div>
    <div class="panel-body">
 <div class="angge-search">
 <?php Pjax::begin(['enablePushState' => TRUE]); ?>
    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>
    <?= 
        $form->field($Kurikulum, 'kr_kode')->widget(Select2::classname(), [
            'data' =>app\models\Funct::AKADEMIK(),
            'language' => 'en',
            'options' => ['placeholder' => 'Tahun Akademik','required'=>'required'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Tahun Akademik');
     ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Cari', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['dosen/jdwl'],['class' => 'btn btn-danger']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>
</div>
</div>

<?php Pjax::begin(['id'=>'pjaxNilai']); ?>
    <?php if (!empty($dataProvider)): ?>
    <?= GridView::widget([     
    'dataProvider'=> $dataProvider,
    'rowOptions' => function ($data, $index, $widget, $grid){
        if($data['Lock']>0){
            return ['style' => 'color:#000;font-weight:bold'];
        }else{
            return [];
        }
    },      
   
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'header'=>'Jadwal',
			'attribute'=>'GKode',
			'format'=>'raw',
            'value'=>function($model){
				$jdwl=explode("|",$model['jadwal']);
				$jd = "";
				foreach($jdwl as $k=>$v){
					$Info=explode('#',$v);
					$ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";
					$jd.=" $ket &";
				}
				$jdwl=substr($jd,0,-1);
				
                return "<b style='font-size:14px'>$jdwl ($model[Tahun])</b> ";
            },
            'group'=>true,  // enable grouping,
            'groupedRow'=>true,                    // move grouped column to a single grouped row
            'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
        ],          
        [
            'header'=>'Jurusan',
            'value'=>function($model){
                return $model['Jurusan'];
            },
        ],          

        [
            'header'=>'Program',
            'value'=>function($model){
                return $model['Program'];
            },
            #'group'=>true,  // enable grouping,
        ],          
        [     
            'width' => '5%',
            'attribute' => 'Kode','format' => 'raw',
            'value' => function($model){
				$P= $model['nb_tgs1']+$model['nb_tgs2']+$model['nb_tgs3']+$model['nb_tambahan']+$model['nb_quis']+$model['nb_uts']+$model['nb_uas'];
				$G= $model['B']+$model['C']+$model['D']+$model['E'];
				$info="";				
				$link=['/dosen/nilait', 'id' => $model['jdwl_id']];
				if($P==0||$G==0){
					$link=['/dosen/bobot1','id'=>$model['jdwl_id']];
					return Html::a($model['Kode'],$link,['data-pjax'=> '0','class' => 'btn btn-primary','title'=>'Input Nilai','target'=>'_blank']);
				}
				return Html::a($model['Kode'],$link,['data-pjax'=> '0','class' => 'btn btn-primary','title'=>'Input Nilai','target'=>'_blank']);
            },
        ], 
		[
			'attribute'=>'Matakuliah',
			'format'=>'raw',
			'value'=>function($model){
				$P= $model['nb_tgs1']+$model['nb_tgs2']+$model['nb_tgs3']+$model['nb_tambahan']+$model['nb_quis']+$model['nb_uts']+$model['nb_uas'];
				$G= $model['B']+$model['C']+$model['D']+$model['E'];
				$info="";				
				if($P==0){$info.=' Persentase &';}
				if($G==0){$info.=' Grade &';}
				return ($model['Lock']>0 ?' <i class="glyphicon glyphicon-lock" title="Nilai Sudah Terkunci"></i> ':" ")."$model[Matakuliah] ($model[Kelas])"
				.($info?"<br /><span style='font-size:12px;color:red;'><i><b>".substr($info,0,-1)." Nilai Belum Diset!</b></i></span>":"");
			}
		],
        'SKS',
        [
            'header' => '<i class="fa fa-user"></i>',
            'attribute' => 'Approved'
        ],
        [
            'header' => '<i class="fa fa-exclamation"></i>',
            'attribute' => 'Pending',
			'value'=>function($model){return false;},
			'visible'=>false,
        ],
        [
            'header' => '<i class="fa fa-remove"></i>',
            'attribute' => 'Reject',
			'value'=>function($model){return false;},
			'visible'=>false,
        ],
        [     
            'width' => '5%',
            'label' => 'Absensi',
            'attribute' => 'Kode','format' => 'raw',
            'value' => function($model){
                    return Html::a('<i class="glyphicon glyphicon-list"></i>',
                     ['absensi', 'id' => $model['jdwl_id'],'matakuliah'=>$model['Kode'],'sort'=>'id'],
                     ['data-pjax'=> '0','class' => 'btn btn-info','target'=>'_blank']);
            },
        ], 
    ],
    'responsive'=>true,
    'hover'=>true,
    'export' => false,
    'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>'<i class="fa fa-navicon"></i> Jadwal Mengajar ( *Untuk input nilai mahasiswa, klik pada <label style="color:aqua;"><u>Kode Matakuliah</u></label> )',
    ]
]) ?>     
    <?php endif ?>
   
<?php Pjax::end(); ?>
