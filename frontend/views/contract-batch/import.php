<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 11/13/2024
 * Time: 5:29 PM
 */



/* @var $this yii\web\View */
/* @var $model app\models\Communities */

$this->title = 'Batch Contract Import via Excel Template';
$this->params['breadcrumbs'][] = ['label' => 'Contract Batches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="flexbox-container">

    <?= $this->render('_excelform', [
        'model' => $model,

    ]) ?>

</section>