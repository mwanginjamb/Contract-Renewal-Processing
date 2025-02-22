<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ApprovalStatus $model */

$this->title = 'Update Approval Status: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Approval Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="approval-status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
