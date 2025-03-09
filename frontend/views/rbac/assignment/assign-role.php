<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model backend\models\UserRoleForm */
/* @var $user common\models\User */
/* @var $allRoles yii\rbac\Role[] */
?>

<div class="role-assignment">
    <h2>Assign Roles to <?= Html::encode($user->username) ?></h2>

    <?php $form = ActiveForm::begin([
        'id' => 'role-assignment-form',
        'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($model, 'roles')->checkboxList(
        \yii\helpers\ArrayHelper::map($allRoles, 'name', 'name'),
        [
            'item' => function ($index, $label, $name, $checked, $value) {
                return Html::checkbox($name, $checked, [
                    'value' => $value,
                    'label' => '<span class="role-item">' . $label . '</span>',
                    'labelOptions' => ['class' => 'role-label'],
                    'class' => 'role-checkbox'
                ]);
            }
        ]
    ) ?>

    <?= $form->field($model, 'userId')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save Roles', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Cancel', ['user-roles'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
    .role-item {
        padding: 5px 10px;
        margin: 2px;
        border-radius: 3px;
        background: #f8f9fa;
        display: inline-block;
        color: #000;
    }

    .role-checkbox:checked+.role-label .role-item {
        background: #e3f2fd;
        font-weight: bold;
    }
</style>