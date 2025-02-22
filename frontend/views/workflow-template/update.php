<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\WorkflowTemplate $model */

$this->title = 'Update Workflow Template: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Workflow Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="workflow-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
