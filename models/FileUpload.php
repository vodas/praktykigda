<?php

namespace app\models;

use yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\Warehouse;

class FileUpload extends Model
{
    /**
     * @var UploadedFile
     */
    public $xmlFile;
    public $jsonFile;

    public function rules()
    {
        return [
                [['xmlFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'xml, txt'],
                [['jsonFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'json']

        ];
    }

    //It allows to contain data in format:
    //"col_0" => "name"
    //"col_1" => "description"
    //"col_2" => "price"
    //Name of column in json file could be change
    public function uploadMenuJson($restaurant_id){
        $col0 = "col_0";
        $col1 = "col_1";
        $col2 = "col_2";

        $warehouse = null;
        $product = null;
        Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/upload/';
        $this->jsonFile = UploadedFile::getInstance($this, 'jsonFile');
        $this->jsonFile->saveAs(Yii::$app->params['uploadPath']. $this->jsonFile->baseName . '.' . $this->jsonFile->extension);
        $json = json_decode(file_get_contents(Yii::$app->params['uploadPath'].$this->jsonFile), true);

        $product_id = Product::find()->orderBy(['product_id' => SORT_DESC])->one()->product_id;
        $product_id++;
        foreach($json as $array) {
            $warehouse = new Warehouse();
            $product = new Product();

            $product->name = $array[$col0];
            $product->description = $array[$col1];
            $product->save();

            $warehouse->restaurant_id = $restaurant_id;
            $warehouse->product_id = $product_id;
            $warehouse->price = $array[$col2];
            $warehouse->save();

            $product_id++;
        }
        return true;
    }
    public function upload()
    {
        if ($this->validate()) {

            $this->xmlFile->saveAs('uploads/' . $this->xmlFile->baseName . '.' . $this->xmlFile->extension);

            $xml = simplexml_load_file('uploads/' . $this->xmlFile->baseName . '.' . $this->xmlFile->extension);


            foreach ($xml->product as $product) {


                $warehouse=new Warehouse;
                $warehouse->warehouse_id=null;
                $restaurant = Restaurant::find()
                    ->where(['name' => $product->restaurant_name])
                    ->one();

                $products = Product::find()
                    ->where(['name' => $product->product_name])
                    ->one();

                if(isset($products)&&isset($restaurant)) {
                    $warehouse->restaurant_id = $restaurant->restaurant_id;
                    $warehouse->product_id = $products->product_id;
                    $warehouse->description = $product->description;
                    $warehouse->price = $product->price;
                    $warehouse->save();
                }
            }
            unlink('uploads/' . $this->xmlFile->baseName . '.' . $this->xmlFile->extension);
            return true;
        } else {
            return false;
        }
    }
}

