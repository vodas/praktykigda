<?php

namespace app\models;
use yii\di\Container;

interface CRUDInterface {
    public function index();
    public function create();
    public function update($id);
    public function delete($id);
    public function findModel($id);
}