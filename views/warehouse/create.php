<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Warehouse */

$this->title = Yii::t('app', 'Create Warehouse');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Warehouses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="warehouse-create">

    <h1><?= Yii::t('app', Html::encode($this->title)) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
