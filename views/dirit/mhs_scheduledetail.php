<?php
if($jn=="UTS")
{
echo $this->renderPartial('uts', array('dataProvider'=>$dataProvider,'mhs'=>$mhs,'model'=>$model,'jn'=>$jn,'kr'=>$kr,));
}else if($jn=="UAS"){
echo $this->renderPartial('Uas', array('dataProvider'=>$dataProvider,'MHS'=>$mhs,'model'=>$model,'jn'=>$jn,'kr'=>$kr,)); 
} 
else{

$this->redirect(array('schedule'));
}
?>