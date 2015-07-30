<?php
use yii\helpers\Html;



/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Chart');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);






?>
<div class="site-about">
    <h1><?= Yii::t('app', Html::encode($this->title)) ?></h1>

    <p style="margin-top:10px;">Userzy w danych miastach:</p>
    <img src="<?=$LabChartsPie->getChart()?>">

    <p style="margin-top:30px;">Restauracje w danych miastach :</p>
    <img src="<?=$LabChartsBar->getChart()?>">

    <p style="margin-top:30px;">Zamowienia dla danej restauracji:</p>
    <img src="<?=$LabChartsPie2->getChart()?>">
</div>
