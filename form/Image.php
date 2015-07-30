<?php
namespace app\form;

use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;

class Image extends Model{
    public $x;
    public $y;
    public $w;
    public $h;


    public function rules(){
        return [
            [['x', 'y', 'w', 'h'], 'required']
        ];
    }
    public function scenarios(){
        return parent::scenarios();
    }

}