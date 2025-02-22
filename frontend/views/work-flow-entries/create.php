<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\WorkFlowEntries $model */

$this->title = 'Create Work Flow Entries';
$this->params['breadcrumbs'][] = ['label' => 'Work Flow Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-flow-entries-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
