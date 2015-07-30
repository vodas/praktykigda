<?php
/*Say something You want from controllers/FastDebugging*/

use yii\helpers\Html;
?>
<?php

var_dump($message);


foreach($message as $record){
    echo $record->product_id."<br/>";
}
?>
