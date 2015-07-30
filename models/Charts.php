<?php
namespace app\models;
use yii\base\Model;
use app\charts\LabChartsBar;
use app\charts\LabChartsLine;
use app\charts\LabChartsPie;
use app\models\User;
use app\models\Restaurant;


class Charts extends Model {




    function getRestaurantNames($models) {
        $str='';
        foreach ($models as $model) {
            if ($model['Count(orders.order_id)']!=0) {
                $str = $str . $model['name'] . '|';
            }
        }
        $str = substr($str,0,strlen($str)-1);
        return $str;
    }


    function getCities($models) {
        $str='';
        foreach ($models as $model) {
            $str = $str . $model->city . '|';
        }
        $str = substr($str,0,strlen($str)-1);
        return $str;
    }

    function getAmountArray($models) {
        $count=array();
        foreach ($models as $model) {
            array_push($count, $model->street);
        }
        return $count;
    }


    function getAmountOrderArray($models) {
        $count=array();
        foreach ($models as $model) {
            if($model['Count(orders.order_id)']!=0) {
                array_push($count, $model['Count(orders.order_id)']);
            }
        }
        return $count;
    }

}