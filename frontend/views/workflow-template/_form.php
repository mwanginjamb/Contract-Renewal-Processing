<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\WorkflowTemplate $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="workflow-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sequence')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'workflow_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'workflow_role')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
