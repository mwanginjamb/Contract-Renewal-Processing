<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\WorkflowTemplate $model */

$this->title = 'Create Workflow Template';
$this->params['breadcrumbs'][] = ['label' => 'Workflow Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
