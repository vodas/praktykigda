<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Newsletter */

$this->title = $model->newsletter_id;
$this->params['breadcrumbs'][] = ['label' => 'Newsletters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newsletter-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <p>
        <?php if($model->sent == false)  : ?>
          <?=  Html::a('Update', ['update', 'id' => $model->newsletter_id], ['class' => 'btn btn-primary']) ; ?>
        <?php endif; ?>
        <?= Html::a('Send', ['send', 'id' => $model->newsletter_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'news:ntext',
        ],

    ]) ?>

</div>
