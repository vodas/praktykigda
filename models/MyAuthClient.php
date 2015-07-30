<?php
namespace app\models;

use yii\authclient\OAuth1;

class MyAuthClient extends OAuth1
{
    public $authUrl = 'https://api.twitter.com/oauth/authorize';

    public $requestTokenUrl = 'https://api.twitter.com/oauth/request_token';

    public $accessTokenUrl = 'https://api.twitter.com/oauth/access_token';

    public $apiBaseUrl = 'https://api.twitter.com/1.1/';

    protected function initUserAttributes()
    {
        return $this->api('userinfo', 'GET');
    }
}