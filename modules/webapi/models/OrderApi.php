<?php
namespace app\modules\webapi\models;

use app\models\Order;
use Yii;

class OrderApi extends Order {

    public function serializeToJson($array) {
        for($i = 0; $i < count($array); $i++ ) {
            if(!empty($array[$i]['order_info'])) {
                $array[$i]['order_info'] = json_decode($array[$i]['order_info'], true);
                if($array[$i]['order_info'] == null) {
                    $array[$i]['order_info'] = 'badFormat';
                }
            }
            if(!empty($array[$i]['other_users'])) {
                $array[$i]['other_users'] = json_decode($array[$i]['other_users'], true);
                if($array[$i]['other_users'] == null) {
                    $array[$i]['other_users'] = 'badFormat';
                }
            }
        }
        return $array;
    }
    public function convertToJson() {
        $array = array();
        if(!empty($this->order_id)) {
            array_push($array, ['order_id' => $this->order_id]);
        }
    }

    public function convertToObject($params) {
        if(empty($params['order_id'])) {
            //noone can change that via api
            //$this->order_id = $params['order_id'];
        }
        if(empty($params['user_id'])) {
            $this->user_id = $params['user_id'];
        }
        if(empty($params['user_info'])) {
            $this->user_info = $params['user_info'];
        }
        if(empty($params['restaurant_id'])) {
            $this->restaurant_id = $params['restaurant_id'];
        }
        if(empty($params['user_info'])) {
            $this->user_info = $params['user_info'];
        }
        if(empty($params['status'])) {
            $this->status = $params['status'];
        }
        if(empty($params['other_users'])) {
            $this->other_users = $params['other_users'];
        }
    }
}