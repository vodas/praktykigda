<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="col-lg-5" style="left: 30%;">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
<?= $form->field($modelForm,'name')->label(Yii::t('app','Product name:')) ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app','Find'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
</div>
<?php ActiveForm::end(); ?>
</div>
<h1>CASDSA</h1>
<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
    <div style="margin-top:100px;clear: both;width:100%;"><div style="left: 35%;position: relative;">
        <?php  if(sizeof($results)!=0) {
            echo  '<table class="tabela2" cellspacing="0">
				<tr>
					<th>'.Yii::t('app','Name') .'</th>
					<th>'.Yii::t('app','City') .'</th>
					<th>'.Yii::t('app','Adress') .'</th>
				</tr>';

            foreach($results as $result) {
                echo'<tr><td><a href='.Yii::$app->getUrlManager()->createUrl(['restaurant/view', 'id' => $result['restaurant_id']]).'>'.$result['name'].'</a></td><td>'.$result['city'].'</td>
					<td>'.$result['street'].'  '.$result['house_nr'].'/'.$result['flat_nr'].'</td>
				</tr>';
            }

            echo '</table></div>';

        } else {
            echo'<div class="alert alert-warning" style="left: -400px;position: relative;">';
            echo Yii::t('app', 'There is no results.');
            echo "</div>";
        } ?>
    </div>
<?php endif; ?>