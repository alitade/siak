<?php $this->renderPartial('schedule', array('model'=>$model)); ?><br/><br/>
<?php
	echo CHtml::link("<i class='icon-print icon-white'></i> Cetak",
	array('jadwal/cetakUAS','jenis'=>$jn,'kr'=>$kr),
	array('class'=>' btn btn-info','target'=>'_blank',));
?>
<?php
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'=>'seekrs-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		array(
      		'header'=>'No',
        	'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
        ),
		array(
		'header'=>'kode',
		'value'=>'@$data->bn->mtk_kode',
		'type'=>'raw',
		),
		array(
		'header'=>'Mata Kuliah',
		'value'=>'@$data->bn->mtk->mtk_nama', 
		'type'=>'raw'
		),
		array(
		'header'=>'Semester',
		'value'=>'$data->semester', 
		'type'=>'raw'
		),
		array(
		'header'=>'Jadwal UAS',
		'value'=>'$data->Tanggal($data->jdwl_uas)', 
		'type'=>'raw'
		),
		array(
		'header'=>'Jam',
		'value'=>'$data->Jam($data->jdwl_uas)."-".$data->jdwl_uas_out', 
		'type'=>'raw'
		),
		array(
		'header'=>'Ruangan',
		'value'=>'@$data->rg->rg_kode."-".@$data->rg->rg_nama', 
		'type'=>'raw'
		),
		array(
		'header'=>'Kelas',
		'value'=>'$data->jdwl_kls', 
		'type'=>'raw'
		),
		array(
		'header'=>'Dosen',
		'value'=>'@$data->bn->ds->ds_nm', 
		'type'=>'raw'
		),
		
		),
		
		
		)); 
	?>