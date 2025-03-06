<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\WorkflowTemplateMembers $model */

$this->title = Yii::t('app', 'Add a Workflow Template Member');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Template Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-template-members-create">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'users' => $users
            ]) ?>

        </div>
    </div>



</div>