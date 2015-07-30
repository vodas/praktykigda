<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductCRUD */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="product-index">

    <h1><?= Yii::t('app', Html::encode($this->title)) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'columns'=> [
            ['class' => '\kartik\grid\SerialColumn'],
            'name',
            'description:ntext',
            ['class' => '\kartik\grid\ActionColumn']
        ],
        'pjax'=>false,
        'beforeHeader'=>[
            [
                'options'=>['class'=>'skip-export'] // remove this row from export
            ]
        ],
        // set your toolbar
        'toolbar'=> [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['data-pjax'=>0, 'type' => 'button', 'class'=>'btn btn-success', 'title'=> Yii::t('app', 'Create')]). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['reset'], ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>Yii::t('app', 'Reset')])
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
            'heading'=>Yii::t('app', "Products"),
        ],
        'persistResize'=>false,
    ]);

    ?>

</div>
