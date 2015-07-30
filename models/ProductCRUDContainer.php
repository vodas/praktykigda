<?php
 namespace app\models;
 use yii\web\NotFoundHttpException;

class ProductCRUDContainer implements CRUDInterface {

    public function index() {
        $searchModel = new ProductCRUD();
        $searchModel->scenario = Product::SCENARIO_READ;
        return $searchModel;
    }

    public function create() {
        $model = new Product();
        $model->scenario = Product::SCENARIO_CREATE;
        return $model;
    }

    public function update($id) {
        $model = $this->findModel($id);
        $model->scenario = Product::SCENARIO_UPDATE;
        return $model;
    }

    public function delete($id) {
        $model = $this->findModel($id);
        $model->scenario = Product::SCENARIO_DELETE;
        $model->delete();
    }


    public function findModel($id) {
        $search = new Product();
        $search->scenario = Product::SCENARIO_READ;
        if (($model = $search->findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
