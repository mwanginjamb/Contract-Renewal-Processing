<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:29 PM
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AgendaDocument */

$this->title = 'Signature Gateway';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="emudhra-gateway">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form'); ?>

</div>