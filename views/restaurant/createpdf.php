<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Restaurant;

$this->title = Yii::t('app', 'Pdf menu creator');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,'restaurant_id')->dropDownList(
        ArrayHelper::map(Restaurant::find()->all(), 'restaurant_id', 'name')
    ) ?>



    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>