<?php $this->renderPartial('schedule', array('model'=>$model)); ?><br/><br/>
<table class='table table-hover table-nomargin table-colored-header'>
	<tr>
        	<td class=cc> 
<?php
	echo CHtml::link("<i class='icon-print icon-white'></i> Cetak",
	array('jadwal/cetakuts','jenis'=>$jn,'kr'=>$kr),
	array('class'=>' btn btn-info','target'=>'_blank',));
?>
</td>
</tr>
</table>
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
		'header'=>'Jadwal UTS',
		'value'=>'$data->Tanggal($data->jdwl_uts)', 
		'type'=>'raw'
		),
		array(
		'header'=>'Jam',
		'value'=>'$data->Jam($data->jdwl_uts)."-".$data->jdwl_uts_out', 
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