<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;

Modal::begin([
    'options'=>['tabindex' => false],
    'header'=>'<i class="fa fa-users"></i> Tambah Data Operator',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'op-modals'
]);
echo $this->render('_form_op');
Modal::end();
echo Html::a('<i class="fa fa-plus"> </i> Tambah Data Operator',['#'],['class'=>'btn btn-primary','id'=>'op-popupModal']);
?>

<?php
$this->registerJs("$(function() {
   $('#op-popupModal').click(function(e) {
     e.preventDefault();
     $('#op-modals').modal('show').find('.modal-content').html(data);
   });
});");
