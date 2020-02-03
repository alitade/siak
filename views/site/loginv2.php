<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;


?>
<!--
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
<!------ Include the above in your HEAD tag ---------->

<div class="container"><br></div>
<div class="container">
    <div class="panel" style="background: rgba(51, 122, 183, 0.3);">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <h4>Sistem Informasi Akademik</h4>
                    <hr>
                    <?php
                    $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]);
                    echo Form::widget([
                        'model' => $model,
                        'form' => $form,
                        'columns' =>1,
                        'attributes' => [
                            'username'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'NPM / USERNAME'],'label'=>false],
                            'password'=>['type'=> Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'**********'],'label'=>false],
                            #'rememberMe'=>['type'=>Form::INPUT_CHECKBOX,],
                            ['type'=>Form::INPUT_RAW,'value'=>'<div class="pull-right">'.Html::submitButton('<i class="fa fa-save"></i> Login',['class'=>'btn btn-primary']).'</div>']
                        ]
                    ]);


                    ?>
                    <?php ActiveForm::end(); ?>
                    <!-- span style="font-size:16px">
                    <hr>
                    <i class="fa fa-phone"></i> +62 (xxx) xxxx-xxxx
                    <br>
                    <i class="fa fa-fax"></i> info@mail.com
                    <br>
                    <i class="fa fa-envelope"></i> info@mail.com
                    <br>
                    <i class="fa fa-street-view"></i>
                    </span -->
                </div>
                <div class="col-md-8">

                    <blockquote style="border-left: solid 3px #000;padding: 3px 9px;margin: 0 0 8px">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                        <footer>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a an... <?= Html::a('detail',['#']) ?></footer>
                    </blockquote>
                    <blockquote style="border-left: solid 3px #000;padding: 3px 9px;margin: 0 0 8px">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                        <footer>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a an... <?= Html::a('detail',['#']) ?></footer>
                    </blockquote>
                    <blockquote style="border-left: solid 3px #000;padding: 3px 9px;margin: 0 0 8px">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                        <footer>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a an... <?= Html::a('detail',['#']) ?></footer>
                    </blockquote>
                    <blockquote style="border-left: solid 3px #000;padding: 3px 9px;margin: 0 0 8px">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                        <footer>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a an... <?= Html::a('detail',['#']) ?></footer>
                    </blockquote>

                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li>
                                <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="active"><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li>
                                <a href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>
            <hr style="margin-top: 0px;margin-bottom:6px;border-bottom:solid 1px #000; ">
            <div class="copyrights pull-right" style="font-style: italic;font-size: 12px;"> &copy; <?php echo date('Y'); ?> Developed by <a href="http://internofa.com" title="Internofa IT Solution" target="_blank"><i style="color:black"><b>Internofa IT Solution</b></i></a> </div>
        </div>

    </div>
</div>

