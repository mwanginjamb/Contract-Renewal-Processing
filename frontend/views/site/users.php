<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */

$this->title = 'App Users';
?>
<div class="site-index">

    <div class="body-content">
        <div class="card card-info">
            <div class="card-header">
                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr>
                            <td class="fw-bold">#</td>
                            <td class="fw-bold">Username</td>
                            <td class="fw-bold">Email</td>
                            <td class="fw-bold">Created At</td>
                            <td class="fw-bold">Staff Number</td>
                            <td class="fw-bold">Status</td>
                            <td class="fw-bold">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users && is_array($users)): ?>
                            <?php foreach ($users as $c):

                                $status = null;
                                if ($c->status == 10) {
                                    $status = '<div class="badge bg-success">Active</div>';
                                } else if ($c->status == 9) {
                                    $status = '<div class="badge badge-dark">Inactive</div>';
                                } else if ($c->status == 0) {
                                    $status = '<div class="badge badge-danger">Deleted</div>';
                                }
                                ?>
                                <tr>
                                    <td><?= strtoupper($c->id) ?? '' ?></td>
                                    <td><?= $c->username ?? '' ?></td>
                                    <td><?= strtoupper($c->email) ?? '' ?></td>
                                    <td><?= Yii::$app->formatter->asDatetime($c->created_at) ?? '' ?></td>
                                    <td><?= $c->staff_id_number ?? '' ?></td>
                                    <td><?= $status ?></td>
                                    <td><?= Html::a('<i class="fas fa-trash mx-1"></i>Delete', ['site/delete'], [
                                        'class' => 'btn btn-xs btn-danger',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this user?  Note this is a destructive action.',
                                            'method' => 'post',
                                            'params' => [
                                                'id' => $c->id
                                            ]
                                        ]
                                    ]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>


    </div>
</div>

<?php

$script = <<<JS
    $('#table').DataTable();
JS;

$this->registerJs($script);
