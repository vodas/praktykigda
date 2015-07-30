<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Newsletter */

$this->title = 'Create Newsletter';
$this->params['breadcrumbs'][] = ['label' => 'Newsletters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newsletter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
