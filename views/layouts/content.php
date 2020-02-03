<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
?>
<div class="content-wrapper">
    <section class="content-header">
        <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
    </section>
    <p>&nbsp;</p>
    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer" style="font-size:11px">
    <div class="pull-right hidden-xs">
        <b>Developed By Internofa IT Solution</b>
    </div>
     <strong>&copy; Sistem Informasi Akademik Universitas Sangga Buana YPKP <?= date("Y")?> | Design By <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong>
</footer>
