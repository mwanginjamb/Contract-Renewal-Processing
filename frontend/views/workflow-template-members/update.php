<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\WorkflowTemplateMembers $model */

$this->title = Yii::t('app', 'Update Workflow Template Members: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Template Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="workflow-template-members-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
