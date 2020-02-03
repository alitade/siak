<?php
namespace app\components;
use Yii;
use yii\base\Component;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

class InputComponent extends Component{
	public function init(){
		parent::init();
	}
	public function display($model,$attribute,$options = []){
			$form = ActiveForm::begin([
			    'method' => 'post',
			    'action' => ['controller/action'],
			]);
			  
			$input =   $form->field($model, $attribute)->widget(\yii\widgets\MaskedInput::className(), [
					        
					        'clientOptions' => [
					        'alias' => 'decimal',
					        'groupSeparator' => ',',
					        'autoGroup' => true,
					        'removeMaskOnSubmit' => true,
					        ],
					        'options' => $options

					])->label(''); //$form->Field($model,$attribute)->input('text',$options)->label('');
			$form->end();

			return $input;
	}

}

?>
