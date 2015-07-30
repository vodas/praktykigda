<?php

use yii\helpers\Html;


$this->title = Yii::t('app', 'Twitter login');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>

<div class="site-index">

    <div class="jumbotron">
        <?php
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }
        else{
            echo'<div class="alert alert-success">
                        '.Yii::t('app', 'You are logged in').'
                </div>';
        }



        ?>




    </div>

</div>
