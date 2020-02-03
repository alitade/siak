<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;
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

$this->title = 'Absensi Perkuliahan Hari Ini';
$this->params['breadcrumbs'][] = $this->title;
?>    
<style type="text/css"> 

.container{
  width:100%;
  margin:auto;
}
table{
  border-collapse:collapse;
  width:100%;
}
thead{
color: white;
background: #337AB7;
}
tbody tr:nth-child(even){
  background:#ECF0F1;
} 
.fixed{
  top:0;
  position:fixed;
  width:auto;
  display:none;
  border:none;
}
.scrollMore{
  margin-top:600px;
}
.up{
  cursor:pointer;
}

.summary{
  background: rgb(51, 122, 183) none repeat scroll 0% 0%;
  margin-left: 16px;
  margin-right: 16px;
  color: white;
  padding-top: 3px;
  padding-right: 10px;
  text-align: right;
  height: 70px;
}

.info{
  float: left;
  font-weight: bold;
  padding-left: 10px;
  text-align: left; 
}

</style>
<?=$table?>


