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
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users && is_array($users)): ?>
                            <?php foreach ($users as $c): ?>



                                <tr>
                                    <td><?= strtoupper($c->id) ?? '' ?></td>
                                    <td><?= $c->username ?? '' ?></td>
                                    <td><?= strtoupper($c->email) ?? '' ?></td>
                                    <td><?= Yii::$app->formatter->asDatetime($c->created_at) ?? '' ?></td>
                                    <td><?= $c->staff_id_number ?? '' ?></td>
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
