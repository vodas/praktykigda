<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Restaurant;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>




<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->dropDownList(
        ArrayHelper::map(User::find()->all(), 'user_id', 'login')
    ) ?>

    <?= $form->field($model, 'user_info')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'restaurant_id')->dropDownList(
        ArrayHelper::map(Restaurant::find()->all(), 'restaurant_id', 'name')
    ) ?>

    <?= $form->field($model, 'order_info')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'status')->dropDownList(['0' => 'niezatwierdzony', '1' => 'zatwierdzony']); ?>

    <?= $form->field($model, 'other_users')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
