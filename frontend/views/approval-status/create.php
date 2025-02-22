<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ApprovalStatus $model */

$this->title = 'Create Approval Status';
$this->params['breadcrumbs'][] = ['label' => 'Approval Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="approval-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
