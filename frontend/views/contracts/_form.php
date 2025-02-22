<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Contracts $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="contracts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'contract_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'employee_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'employee_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'original_contract_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'signed_contract_path')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'contract_duration')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'employee_workstation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
