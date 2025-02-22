<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ContractsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="contracts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'contract_number') ?>

    <?= $form->field($model, 'employee_name') ?>

    <?= $form->field($model, 'employee_number') ?>

    <?= $form->field($model, 'original_contract_path') ?>

    <?php // echo $form->field($model, 'signed_contract_path') ?>

    <?php // echo $form->field($model, 'contract_duration') ?>

    <?php // echo $form->field($model, 'employee_workstation') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
