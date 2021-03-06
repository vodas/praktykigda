<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WarehouseCRUD */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Warehouses');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="warehouse-index">

    <h1><?= Yii::t('app', Html::encode($this->title)) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Warehouse'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Import from XML'), ['addfromxml'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            <?= Yii::t('app', 'Menu added to database')?>
        </div>

    <?php endif ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'columns'=> [
            ['class' => '\kartik\grid\SerialColumn'],
            'restaurant_id',
            'product_id',
            'description',
            'price',
            ['class' => '\kartik\grid\ActionColumn']
        ],
        'pjax'=>true, // pjax is set to always true for this demo
        'beforeHeader'=>[
            [
                'options'=>['class'=>'skip-export'] // remove this row from export
            ]
        ],
        // set your toolbar
        'toolbar'=> [
            ['content'=>
            //Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type'=>'button', 'title'=>'Add restaurant', 'class'=>'btn btn-success', 'onclick'=>Html::a('Create Restaurant', ['create'], ['class' => 'btn btn-success'])]) . ' '.
            /*Html::a('<i class="glyphicon glyphicon-plus"></i>',Create Restaurant, ['create'], ['class' => 'btn btn-success'])*/
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['data-pjax'=>0, 'type' => 'button', 'class'=>'btn btn-success', 'title'=>'Create']). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['reset'], ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset'])
            ],
            '{export}',
            '{toggleData}',
        ],
        // set export properties
        'export'=>[
            'fontAwesome'=>true
        ],
        // parameters from the demo form
        'bordered'=>true,
        'striped'=>false,
        'condensed'=>true,
        'responsive'=>true,
        'hover'=>true,
        'showPageSummary'=>true,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>Yii::t('app', "Restaurant"),
        ],
        'persistResize'=>false,
    ]);

    ?>

</div>
