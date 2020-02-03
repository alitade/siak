<?php
$NO='<span style="font-size:10px">No.'.$model->kode_transaksi.'</span>';
?>
<table border="0" width="100%" height="100%" style="vertical-align:top;height:100%;font-size:11px">

	<tr>
    	<td width="45%" style="font-size:10px;border-bottom:#000 solid 1px;"> 
		&nbsp;&nbsp;ARSIP JURUSAN <?= "<br />&nbsp;&nbsp;$NO"?>
        </td>
        <td style="background:#000" width="1px" rowspan="3"></td>
    	<td width="45%" style="font-size:10px;border-bottom:#000 solid 1px;"> 
        &nbsp;&nbsp;ARSIP KEUANGAN <?= "<br />&nbsp;&nbsp;$NO"?>
        </td>
    </tr>

	<tr>
    	<td width="45%"> 
            <?= $data."<br /><br />".$tanda1 ?>
        </td>
    	<td width="45%" height="100%">
        
		<?= $data."<br /><br />".$tanda2 ?>
        </td>
    </tr>
	<tr>
    	<td width="45%" style="font-size:10px;border-top:#000 solid 1px;"> 
        	&nbsp;&nbsp;<?= $NO ?>
        </td>
    	<td width="45%" style="font-size:10px;border-top:#000 solid 1px;"> 
        &nbsp;&nbsp;<?= $NO ?>
        </td>
    </tr>
</table>