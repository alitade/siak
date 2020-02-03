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
      <?= 
        $form->field($Jurusan, 'jr_id')->widget(Select2::classname(), [
            'data' =>app\models\Funct::JURUSAN(),
            'language' => 'en',
            'options' => ['placeholder' => 'Jurusan','required'=>'required'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Jurusan');
     ?>
      <?= 
        $form->field($Program, 'pr_kode')->widget(Select2::classname(), [
            'data' =>app\models\Funct::PROGRAM(),
            'language' => 'en',
            'options' => ['placeholder' => 'Program'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Program');
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
   
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'Tahun',
        'Jurusan',
        'Program',
        [     
            'width' => '5%',
            'attribute' => 'Kode','format' => 'raw',
            'value' => function($model){
                    return Html::a($model['Kode'],
                     ['nilait', 'id' => $model['jdwl_id']],
                     ['data-pjax'=> '0','class' => 'btn btn-primary','title'=>'Input Nilai','target'=>'_blank']);
            },
        ], 
        'Matakuliah',
        'SKS',
        'Kelas',
        'Jam',
        [
            'label' => 'KRS Approved',
            'attribute' => 'Approved'
        ],
        [
            'label' => 'KRS Pending',
            'attribute' => 'Pending'
        ],
        [
            'label' => 'KRS Reject',
            'attribute' => 'Reject'
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
