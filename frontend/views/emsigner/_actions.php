<?php

use yii\bootstrap5\Html;

$contract = Yii::$app->session->get('metadata')['Application'];

echo Html::a(
    '<i class="fas fa-fast-forward"></i>Next Reviewer',
    [
        'approve-request',
        'contractNumber' => $contract,
    ],
    [
        'class' => 'btn btn-app bg-success mx-3',
        'title' => 'Send contract to next reviewer for consideration and signing'
    ]
);



