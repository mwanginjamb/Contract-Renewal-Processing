<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DurationUnits $model */

$this->title = 'Create Duration Units';
$this->params['breadcrumbs'][] = ['label' => 'Duration Units', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="duration-units-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
