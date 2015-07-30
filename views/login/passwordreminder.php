<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Forgot password?');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="site-about">
    <h1><?= Yii::t('app', Html::encode($this->title)) ?></h1>


    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

    <div class="alert alert-success">
        <?= Yii::t('app', 'We email You a link to reset You password')?>
    </div>



        <?php else: ?>

    <p>
        <?= Yii::t('app', 'Use email to reset You password')?>
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>
            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>


<?php endif; ?>
</div>