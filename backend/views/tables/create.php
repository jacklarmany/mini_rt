<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Tables */

$this->title = 'Create Tables';
$this->params['breadcrumbs'][] = ['label' => 'Tables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12 bg-light rounded p-0">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 pl-2 m-0">
                <h4><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="col-md-6 pr-2 m-0 text-right">
                <a href="#" id="cback"><span><i class="fa fa-arrow-circle-left"></i></i></span></a>
                <a href="index.php?r=site/index"><span><i class="text-danger fa fa-times-circle"></i></i></span></a>
            </div>
        </div>
    </div>
    <div class="card-body bg-white p-5">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>