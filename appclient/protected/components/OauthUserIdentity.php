<?php

use Guzzle\Http\StaticClient as Guzzle;

class OauthUserIdentity extends CUserIdentity
{

    protected $config;
    protected $access_token;
    protected $code;
    public $id;
    public $email;
    public $name;

    public function __construct($code = false)
    {
        $this->config = Yii::app()->params['oauth'];
        $this->code = $code;
    }

    public function getLoginUrl($return = false)
    {
        if ($return) {
            Yii::app()->session['login_redirect'] = $return;
        } else {
            unset(Yii::app()->session['login_redirect']);
        }

        $state = md5(microtime()); // Simple generated string for state parameter
        Yii::app()->session['login_state'] = $state;

        $url = $this->config['auth_url'];
        $url .= '?client_id=' . $this->config['client_id'];
        $url .= '&response_type=code';
        $url .= '&scope=' . $this->config['scope'];
        $url .= '&state=' . $state;

        return $url;
    }

    public function authenticate()
    {
        if ($this->code) {
            $response = Guzzle::post($this->config['token_url'], array(
                    'headers' => array('Accept' => 'application/json'),
                    'body' => array(
                        'grant_type' => 'authorization_code',
                        'client_id' => $this->config['client_id'],
                        'client_secret' => $this->config['client_secret'],
                        'state' => Yii::app()->session['login_state'],
                        'code' => $this->code,
                    ),
                    'auth' => array($this->config['client_id'], $this->config['client_secret']),
                    'exceptions' => false,
                ));

            if ($response->isSuccessful()) {
                $results = $response->json();
                if (is_array($results) && isset($results['access_token'])) {
                    $this->access_token = $results['access_token'];
                    $userInfo = $this->getUserInformation();
                    if($userInfo){
                        $this->id = $userInfo['name'];
                        $this->name = $userInfo['name'];
                        $this->email = $userInfo['email'];
                        $this->setState('access_token',$results['access_token']);
                        $this->errorCode = self::ERROR_NONE;
                        echo $results['access_token'];
                    } else {
                        $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
                    }

                } else {
                    Yii::app()->user->setFlash('danger', "There was a problem with the response from the Oauth server, maybe try again in a moment? (".__LINE__.")");
                    $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
                }
            } else {
                Yii::app()->user->setFlash('danger', "There was a problem with the response from the Oauth server, maybe try again in a moment? (".__LINE__.")");
                $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
            }
        } else {
            Yii::app()->user->setFlash('danger', "There was a problem with the response from the Oauth server, maybe try again in a moment? (".__LINE__.")");
            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
        }

        return !$this->errorCode;
    }

    public function getUserInformation()
    {
        $url = $this->config['api_url'] . '/profile';
        $response = Guzzle::get($url, array(
            'headers' => array(
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->access_token,
            ),
            'exceptions' => false,
        ));
        if ($response->isSuccessful()) {
            $userInfo = $response->json();
            if (is_array($userInfo)) {
                return $userInfo;
            } else {
                Yii::app()->user->setFlash('danger', "There was a problem with the response from OAuth server, maybe try again in a moment? (".__LINE__.")");
                return false;
            }
        } else {
            Yii::app()->user->setFlash('danger', "There was a problem with the response from OAuth server, maybe try again in a moment? (".__LINE__."). More Info: ".$response->getBody());
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

//    public function loadIdentity(UserBase $user)
//    {
//        $this->setState('user', $user);
//        $this->setState('role', $user->role);
//        $this->id = $user->id;
//        $this->username = $user->email;
//        $this->name = $user->name;
//        $this->errorCode = self::ERROR_NONE;
//    }

}
