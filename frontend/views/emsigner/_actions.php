<?php

use yii\bootstrap4\Html;

if (Yii::$app->session->has('approval')):

    $app = Yii::$app->session->get('approval');

    echo Html::a(
        '<i class="fas fa-print"></i>Approve',
        [
            'approve-request',
            'app' => $app['Document_No'],
            'empNo' => $app['Approver_No']
        ],
        [
            'class' => 'btn btn-app bg-success mx-3 approve',
            'data-Document_No' => $app['Document_No'],
            'data-Approver_No' => $app['Approver_No'],
            'data-Table_ID' => $app['Table_ID'],
            'data-Entry_No' => $app['Entry_No'],
            'data-action' => 'emsigner/approve',
            'title' => 'Approve Request'
        ]
    );


    /* Html::a('<i class="fas fa-print"></i>Reject', ['reject-request'], [
            'class' => 'btn btn-app bg-danger reject ',
            'rel' => $app['Document_No'],
            'rev' => $app['Approver_No'],
            'name' => $app['Table_ID'],
            'data-Entry_No' => $app['Entry_No'],
            'title' => 'Reject Request'
        ]);*/
endif;
