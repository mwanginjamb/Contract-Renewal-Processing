<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\WorkFlowEntries $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="work-flow-entries-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'template_id')->textInput() ?>

    <?= $form->field($model, 'approver_id')->textInput() ?>

    <?= $form->field($model, 'approval_status')->textInput() ?>

    <?= $form->field($model, 'actioned_date')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'contract_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>