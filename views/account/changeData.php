<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;


$this->title = Yii::t('app', 'Change data');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<link rel="stylesheet" href="/css/jquery.Jcrop.css" type="text/css" />


<head>
    <h1><?= Yii::t('app', Html::encode($this->title)) ?></h1>
    <p><?= Yii::t('app', 'Please fill out the following fields:')?></p>

</head>
<body >
<?php if (Yii::$app->session->hasFlash('dataChanged')): ?>

    <div class="alert alert-success">
        <?= Yii::t('app', 'You account setting has been changed')?>
    </div>
<?php endif; ?>
    <div class="user-form">
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
                    <h2><?=Yii::t('app', 'Resize image')?></h2>
        <img src="/upload/default.jpg" alt="<?= Yii::t('app', 'Change picture')?>" id="cropbox" style="height: 370px"/>
                </td>
                <td>
                    <h2><?=Yii::t('app', 'Preview')?></h2>
                    <div style="width:200px;height:200px;overflow:hidden;margin-left:5px;">

        <img src="" id="preview" style="width: 400px; height: 296px; margin-left: -106px; margin-top: -103px;">
                    </div>
                </td>
            </tr>
        </table>

        <input type="hidden" id="jCropImageHelper" value="<?=$model->filename ? '/upload/' .$model->filename : "/upload/default.jpg"?>"/>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'onsubmit' => 'return false']]); ?>
        <?php

        // Usage with ActiveForm and model
        echo $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'showPreview' => false,
            ]
        ]);

        ?>
        <?php $model->pass = '';?>
        <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'pass') ->passwordInput(['maxlength' => true]) ?>

        <?= $form->field($model,'pass_repeat') ->passwordInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'house_nr')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'flat_nr')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'zipcode')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'newsletter')->checkBox()->label("Czy chcesz dostawac newsletter ?") ?>


        <?= $form->field($image, 'x')->hiddenInput(['id' => 'x'])->label('') ?>
        <?= $form->field($image, 'y')->hiddenInput(['id' => 'y'])->label('') ?>
        <?= $form->field($image, 'w')->hiddenInput(['id' => 'w'])->label('') ?>
        <?= $form->field($image, 'h')->hiddenInput(['id' => 'h'])->label('') ?>
        <div class="form-row">
            <?= Html::submitButton(Yii::t('app', 'Update'), ['id' => 'submitButton', 'class' => 'btn btn-primary', 'onclick' => 'SiteContainer.submitAjaxForm(this, event)']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        </div>
</body>
