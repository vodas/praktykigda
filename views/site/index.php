<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Restaurant;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'My Yii Application');
?>



<div class="site-index">

    <div class="jumbotron">
        <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
            echo '<div class="alert alert-success">';
            echo $message ;
            echo '</div>';
        }

        if(Yii::$app->user->isGuest){
            echo "<h1>". Yii::t('app', 'Welcome!')."</h1>";

            echo '<p class="lead">'.Yii::t('app', 'You are welcome. Check out our website.') .'</p>';
            echo '<p class="lead">'.Yii::t('app', 'Register or login to find out the best of our website.') .'</p>';

        }
        else{
            $model = \app\models\User::findOne(['login' => Yii::$app->user->identity->username]);
            echo'<div class="alert alert-success">
                        '.Yii::t('app', 'You are logged in').'
                </div>';
            echo '<h1>'.Yii::t('app', 'Welcome').' '.$model->login.'</h1>';
            echo '<img src= "/upload/'.$model->filename.'" onError="this.src=\'\';"/>';
            echo '<h1>'. Yii::t('app', 'Find restaurant').'</h1>';

        }
?>
        <div style="width:100%">
        <div class="col-lg-5" style="left: 30%;">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>


            <?= $form->field($modelform, 'city')->dropDownList(
                ArrayHelper::map(Restaurant::find()->all(), 'city', 'city')
            ) ?>

            <?= $form->field($modelform,'street')->label(Yii::t('app', "Street name:")) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Find'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
</div>
    </div>
    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
    <div style="margin-top:100px;clear: both;width:100%;"><div style="left: 35%;position: relative;">
    <?php  if(sizeof($results)!=0) {
        echo  '<table class="tabela2" cellspacing="0">
				<tr>
					<th>'.Yii::t('app', 'Name').'</th>
					<th>'.Yii::t('app', 'City').'</th>
					<th>'.Yii::t('app', 'Adress').'</th>
				</tr>';

        foreach($results as $result) {
        echo'<tr><td><a href='.Yii::$app->getUrlManager()->createUrl(['restaurant/view', 'id' => $result['restaurant_id']]).'>'.$result['name'].'</a></td>
            <td>'.$result['city'].'</td>
					<td>'.$result['street'].'  '.$result['house_nr'].'/'.$result['flat_nr'].'  '.$result['zip_code'].'</td>
				</tr>';
        }

		echo '</table></div>';

    } else {
    echo'<div class="alert alert-warning" style="left: -400px;position: relative;">';
       echo Yii::t('app', 'There is no results.');
       echo "</div>";
    } ?>
        <?php endif; ?>
</div>
