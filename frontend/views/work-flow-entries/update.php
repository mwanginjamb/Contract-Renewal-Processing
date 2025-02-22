<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\WorkFlowEntries $model */

$this->title = 'Update Work Flow Entries: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Work Flow Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="work-flow-entries-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
