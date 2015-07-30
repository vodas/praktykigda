<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Restaurant */

$this->title = Yii::t('app', $model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restaurants'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="restaurant-view">

    <h1><?= Yii::t('app', Html::encode($this->title)) ?></h1>
<?php if(Yii::$app->user->isGuest||Yii::$app->user->identity->user_role==0||Yii::$app->user->identity->user_role==1||Yii::$app->user->identity->user_role==2): ?>

<?php else: ?>
    <p>

        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->restaurant_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->restaurant_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'street',
            'house_nr',
            'flat_nr',
            'zip_code',
            'city',
        ],
    ]) ?>

    <?php if(Yii::$app->user->isGuest||Yii::$app->user->identity->user_role==0||Yii::$app->user->identity->user_role==1||Yii::$app->user->identity->user_role==2): ?>

    <?php else: ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($menu, 'jsonFile')->fileInput() ?>
<div>
    <?= Html::submitButton(Yii::t('app', 'Add menu'), ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end() ?>

    <?= Html::a(Yii::t('app', 'Export menu to csv'), ['menu-export-to-csv', 'id' => $model->restaurant_id], ['class' => 'btn btn-primary']) ?>

</div>

    <?php endif; ?>
