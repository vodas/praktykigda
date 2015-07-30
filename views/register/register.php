<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = Yii::t('app', 'Register');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>

<div class="site-contact">
    <h1><?= Yii::t('app', Html::encode($this->title)) ?></h1>

    <?php if (Yii::$app->session->hasFlash('registered')): ?>

        <div class="alert alert-success">
            <?= Yii::t('app', 'You have been registered')?>
        </div>

    <?php else: ?>
        <script type="text/javascript" src="/js/RegisterContainer.js"></script>
        <script src="/js/facebook.js"></script>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'pass')->passwordInput() ?>
                <?= $form->field($model, 'pass_repeat')->passwordInput() ?>
                <?= $form->field($model, 'newsletter')->checkBox()->label("Czy chcesz dostawac newsletter ?") ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</br><p style="font-size:8px;">press to change picture</p></div><div class="col-lg-6">{input}</div></div>',
                ]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('login/tweeterlogin')  ?>"><img src="https://g.twimg.com/dev/sites/default/files/images_documentation/sign-in-with-twitter-gray.png"></a>
                    <fb:login-button scope="public_profile,email" onLogin="checkLoginState();" >
                    </fb:login-button>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
