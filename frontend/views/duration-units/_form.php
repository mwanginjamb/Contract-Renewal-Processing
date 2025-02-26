<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DurationUnits $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="duration-units-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'created_at')->textInput() ?>

    <?php $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>