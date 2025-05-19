<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Update User';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup card">

    <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <p>Please fill out the following fields to update your details:</p>

        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin(['id' => 'form-user-update']); ?>

                <?php $form->errorSummary($model) ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'readonly' => true]) ?>

                <?= $form->field($model, 'email')->textInput(['readonly' => true]) ?>

                <?= $form->field($model, 'staff_id_number')->textInput() ?>


                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>


</div>