<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\WorkflowTemplateMembers $model */

$this->title = Yii::t('app', 'Add a Workflow Template Member');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Template Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-template-members-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>