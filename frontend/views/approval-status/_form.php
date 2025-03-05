<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ApprovalStatus $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="approval-status-form">

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><?= $this->title ?></h3>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'required' => true]) ?>

            <?php $form->field($model, 'created_at')->textInput() ?>

            <?php $form->field($model, 'updated_at')->textInput() ?>

            <?php $form->field($model, 'created_by')->textInput() ?>

            <?php $form->field($model, 'updated_by')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>


</div>