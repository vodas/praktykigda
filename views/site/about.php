<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'About');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="site-about">
    <h1><?= Yii::t('app', Html::encode($this->title)) ?></h1>

    <p>
<?= Yii::t('app', 'This is the About page. You may modify the following file to customize its content:')?>
    </p>

    <code><?= '_FILE__'?></code>
</div>
