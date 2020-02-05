<?php
use yii\bootstrap\Carousel;
use yii\bootstrap\Tabs;
/* @var $this yii\web\View */

$this->title = 'Sistem Informasi Akademik';
$url= "<img src=".Yii::getAlias('@web');
$url.="/images/wowslider/"; 
?>
<?php
//session_destroy();

$User = Yii::$app->user;
switch (@$User->identity->tipe) {
	case  5:echo $this->render('mhs',['User'=>$User]);break;
	case  3:echo $this->render('dsn',['User'=>$User]);break;
	case  4:echo $this->render('prodi',['User'=>$User]);break;
  }

?>