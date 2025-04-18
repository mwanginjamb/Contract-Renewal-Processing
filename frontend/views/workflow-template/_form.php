<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\WorkflowTemplate $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="workflow-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $form->field($model, 'sequence')->textInput() ?>

    <?php $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'workflow_name')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'workflow_role')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'created_at')->textInput() ?>

    <?php $form->field($model, 'updated_at')->textInput() ?>

    <?php $form->field($model, 'created_by')->textInput() ?>

    <?php $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>