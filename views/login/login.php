<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<body>
<script src="/js/facebook.js"></script>

<?php
foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
    echo '<div class="alert alert-success">';
    echo $message ;
    echo '</div>';
}
?>



<div class="site-login">
    <h1><?= Yii::t('app', Html::encode($this->title)) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following fields to login:')?></p>

    <?php $formBuilder = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>



    <?= $formBuilder->field($form, 'username') ?>

    <?= $formBuilder->field($form, 'password')->passwordInput() ?>
    <label class="col-lg-1 control-label" style="width:120px;">
    <?= $formBuilder->field($form, 'rememberMe', [
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ])->checkbox() ?>
    </label>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11" style="margin-left:0px;">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            <a href="<?= Yii::$app->getUrlManager()->createUrl('login/tweeterlogin')  ?>"><img src="https://g.twimg.com/dev/sites/default/files/images_documentation/sign-in-with-twitter-gray.png"></a>
            <fb:login-button scope="public_profile,email" onLogin="checkLoginState();" >
            </fb:login-button>
        </div>
    </div>




    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;margin-left:0px;">
        <a href="http://praktyki.gda.dev/login/passwordreminder"><?= Yii::t('app', 'Forgot password?')?></a>


    </div>
</div>
</body>