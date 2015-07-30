<?php
use yii\widgets\ActiveForm;
$this->title = Yii::t('app', 'Add products to restaurant from XML file');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

    <div class="alert alert-success">
        <?= Yii::t('app', 'Menu dodane do bazy')?>
    </div>

    <?php endif; ?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'xmlFile')->fileInput() ?>

    <button>Submit</button>

<?php ActiveForm::end() ?>