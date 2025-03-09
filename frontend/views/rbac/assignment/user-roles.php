<?php
$this->title = 'User Roles Assignment';
?>
<?php foreach ($users as $user): ?>
    <div class="user-row">
        <?= $user->username ?>
        <?= yii\helpers\Html::a(
            'Assign Roles',
            ['assign-role', 'userId' => $user->id],
            ['class' => 'btn btn-xs btn-primary']
        ) ?>
    </div>
<?php endforeach; ?>