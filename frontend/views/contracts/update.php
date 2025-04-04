<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Contracts $model */

$this->title = 'Update Contract: ' . strtoupper($model->contract_number);
$this->params['breadcrumbs'][] = ['label' => 'Contracts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contracts-update">
    <div class="card card-info">
        <div class="card-header">
            <div class="card-title">
                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
            </div>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'durationUnits' => $durationUnits,
                'content' => $content
            ]) ?>

        </div>
    </div>



</div>