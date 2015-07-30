<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Changing password');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="site-about">
    <h1><?= Yii::t('app', Html::encode($this->title)) ?></h1>


    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            <?= Yii::t('app', 'Password has changed')?>
        </div>

    <?php else: ?>
        <p>
            <?= Yii::t('app', 'Write new password:')?>
        </p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model,  'password2')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>


    <?php endif; ?>
</div>