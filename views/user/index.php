<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User';
$this->params['breadcrumbs'][] = $this->title;
?>
<p></p>
<div class="user-index">    
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
				(!Funct::acc('/user/create')?"":Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['/user/create'],['class'=>'btn btn-success'])).' '.
                (!Funct::acc('/user/report-user')?"":Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-user'],['class'=>'btn btn-info']))				
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'name',
            'username',
            'posisi',
			[
				'attribute'=>'stat',
				'value'=>function($model){
					$stat='Tidak Aktif';
					if($model->stat=='1'){$stat='Aktif';}
					return $stat;
				},
				'filter'=>['Tidak Aktif','Aktif']
			],
			[
				'header'=>'SubAkses',
				'value'=>function($model){
					return (Yii::$app->authManager->checkAccess($model->id,'SubAkses')?"Y":"N");
					return $stat;
				},
				'filter'=>['Tidak Aktif','Aktif']
			],
			
			[
				'header'=>' ',
				'format'=>'raw',
				'value'=>function($model){
					if(md5('ypkp@#1234'.$model->pass_kode.$model->tipe)!==$model->password){
						return Html::a(" Reset Password",['reset','id'=>$model->id],["onClick"=>"return confirm('Reset user $model->username?')"]);
					}
					return " ";					
				},
				'visible'=>Funct::acc('/user/reset')
			],
            [
                'class' => 'kartik\grid\ActionColumn',
				'template'=>"<li>{view}</li>",
				'dropdown'=>true,
				'dropdownOptions'=>['class'=>'pull-right'],
				'headerOptions'=>['class'=>'kartik-sheet-style'],
				'buttons'=>[
					'view'=> function ($url, $model, $key) {
						return (!Funct::acc('/user/view')?false:
						Html::a('<i class="glyphicon glyphicon-eye-open"></i> view',['/user/view','id' => $model->id]));
					},
				]				
            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-navicon"></i> User',
        ]
    ]); Pjax::end(); ?>

</div>
