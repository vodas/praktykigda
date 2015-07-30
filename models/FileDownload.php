<?php
namespace app\models;
use yii;
use yii\base\Model;
use app\models\Product;
use yii\web\UploadedFile;
use app\models\Warehouse;
use app\models\Restaurant;
use yii\web\Response;
use yii\base\Exception;

class FileDownload extends model{
    public function exportRestaurantMenuToCsv($id){
        $restaurantName = Restaurant::findOne(['restaurant_id' => $id])->name;
        Yii::$app->params['uploadPath'] = Yii::$app->basePath. '/web/upload/';
        $string = "name, description, price \n";
        $product = null;
        foreach(Warehouse::find()->where(['restaurant_id' => $id])->batch(1) as $warehouse) {
            try{
                $product = Product::findOne(['product_id' => $warehouse[0]->product_id]);
                $string .='"'.$product->name.'","'.$product->description.'","'.$warehouse[0]->price.'"' ;
                $string .= "\n";
            }
            catch(Exception $e) {

            }
        }
        $path = $this->saveStringToCsvFile($string, $restaurantName);
        $this->giveUserFile($path);
    }
    private function saveStringToCsvFile($string, $restaurantName) {
        $filename = $restaurantName.'.csv';
        file_put_contents(Yii::$app->params['uploadPath'] . $filename, $string);
        return $filename;
    }
    private function giveUserFile($filename) {
        $var = new Response();
        $var->sendFile(Yii::$app->params['uploadPath']. $filename);
        $var->send();
        unlink(Yii::$app->params['uploadPath']. $filename);
    }
}