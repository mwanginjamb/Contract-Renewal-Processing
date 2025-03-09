<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model backend\models\PermissionForm */
/* @var $existingPermissions yii\rbac\Permission[] */
/* @var $isUpdate bool */
?>

<?php $form = ActiveForm::begin([
    'id' => 'permission-form',
    'enableAjaxValidation' => true,
]); ?>

<?= $form->field($model, 'name')->textInput([
    'maxlength' => true,
    'readonly' => $isUpdate
]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

<?= $form->field($model, 'parent')->dropDownList(
    \yii\helpers\ArrayHelper::map($existingPermissions, 'name', 'description'),
    [
        'prompt' => '-- No Parent --',
        'disabled' => $isUpdate ? 'disabled' : false
    ]
) ?>

<?php if ($isUpdate): ?>
    <?= $form->field($model, 'originalName')->hiddenInput()->label(false) ?>
<?php endif; ?>

<div class="form-group">
    <?= Html::submitButton($isUpdate ? 'Update' : 'Create', [
        'class' => 'btn btn-primary'
    ]) ?>
    <?= Html::a('Cancel', ['permissions'], ['class' => 'btn btn-outline-secondary']) ?>
</div>

<?php ActiveForm::end(); ?>