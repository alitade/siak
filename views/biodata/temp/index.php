<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use yii\bootstrap\Modal;

$this->title = 'PERBAIKAN BIODATA';
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'options'=>[
        'tabindex' => false
    ],
    'header'=>'Filter Data Mahasiswa',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'modals'
]);
echo $this->render('_search', ['model' => $searchModel]);
Modal::end();


?>
    <div class="biodata-index">
        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            #'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'label'=>'No. KTP',
                    'value'=>function($model){return $model->no_ktp;},
                ],
                [
                    'label'=>'Nama',
                    'value'=>function($model){return $model->nama;},
                ],
                [
                    'label'=>'Tempat, Tanggal Lahir',
                    'value'=>function($model){
                        return $model->tempat_lahir.'. '.Funct::TANGGAL($model->tanggal_lahir,2);
                    },

                ],

                [
                    'class' => 'kartik\grid\ActionColumn',
					'template'=>'<div class="text-nowrap">{view}</div>',
                    'buttons' => [
                        /*'view' => function ($url, $model) {
                            return Html::a('<span class="fa fa-eye"></span>',
                                Yii::$app->urlManager->createUrl(['biodata/tmp-view','id' => $model->id_]),
                                ['title' => Yii::t('yii', 'Detail'),'target'=>'_blank']);
                        },*/
						'view' => function ($url, $model) {
							if(!Funct::acc('/biodata/tmp-view')){return false;}
							return Html::a('<i class="fa fa-eye"></i>', Yii::$app->urlManager->createUrl(['biodata/tmp-view','id' => $model->id_]), [
								'title' =>'Detail',
								'class'=>'btn btn-success btn-xs'
							]);
						},

                    ],
                ],
            ],
            'responsive'=>true,
            'hover'=>true,
            'condensed'=>true,
            'floatHeader'=>true,
            'panel' => [
                'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
                'type'=>'info',
                'before'=>Html::a('<i class="fa fa-search"></i> Filter Data', ['create'], ['class' => 'btn btn-success','id'=>'popupModal']),
                'after'=>Html::a('<i class="fa fa-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
                'showFooter'=>false
            ],
        ]);
        ?>
    </div>
<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");
