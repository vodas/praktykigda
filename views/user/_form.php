<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['onsubmit' => 'return false']]); ?>

    <?php $model->pass = '';?>

    <?= $model->isNewRecord ? $form->field($model, 'login')->textInput(['maxlength' => true]): '' ?>

    <?= $form->field($model, 'pass') ->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model,'pass_repeat') ->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_role')->dropDownList(['0'=>'zwykły użytkownik', '1'=>'pracownik restauracji', '2'=>'manager', '3'=>'super_user', '4'=>'admin']); ?>

    <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'house_nr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'flat_nr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zipcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'onclick' => 'SiteContainer.submitAjaxForm(this, event)']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
