<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div id="ref" style="text-align:center;font-weight:bolder;font-size:36px;">
Reg. <?= $data ?> Data<br />
Input. <?= $data1 ?> Data<br />
Sisa. <?= $data-$data1 ?> Data<br />
</div>
<script type="text/javascript">
setInterval("my_function();",5000); 
function my_function(){window.location = location.href;}
</script>
