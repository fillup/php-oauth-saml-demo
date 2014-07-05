<?php

use Guzzle\Http\StaticClient as Guzzle;

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
        if(Yii::app()->user->isGuest){
		    $this->render('index');
        } else {
            $this->render('info', array(
                'access_token' => Yii::app()->user->access_token,
                'token_info' => $this->apiGetTokenInfo(),
                'profile' => $this->apiGetProfile(),
                'documents' => $this->apiGetDocuments(),
                'invalid_token' => $this->apiInvalidToken(),
            ));
        }
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

    public function apiGetTokenInfo()
    {
        if(!Yii::app()->user->isGuest && isset(Yii::app()->user->access_token)){
            $url = Yii::app()->params['oauth']['api_url'] . '/tokenInfo';
            $response = Guzzle::get($url, array(
                'headers' => array(
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.Yii::app()->user->access_token,
                ),
                'exceptions' => false,
            ));
            if($response->isSuccessful()){
                return $response->json();
            } else {
                return $response->getBody();
            }
        }
    }

    public function apiGetProfile()
    {
        if(!Yii::app()->user->isGuest && isset(Yii::app()->user->access_token)){
            $url = Yii::app()->params['oauth']['api_url'] . '/profile';
            $response = Guzzle::get($url, array(
                    'headers' => array(
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer '.Yii::app()->user->access_token,
                    ),
                    'exceptions' => false,
                ));
            if($response->isSuccessful()){
                return $response->json();
            } else {
                return $response->getBody();
            }
        }
    }

    public function apiGetDocuments()
    {
        if(!Yii::app()->user->isGuest && isset(Yii::app()->user->access_token)){
            $url = Yii::app()->params['oauth']['api_url'] . '/documents';
            $response = Guzzle::get($url, array(
                    'headers' => array(
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer '.Yii::app()->user->access_token,
                    ),
                    'exceptions' => false,
                ));
            if($response->isSuccessful()){
                return $response->json();
            } else {
                return $response->getBody();
            }
        }
    }

    public function apiInvalidToken()
    {
        if(!Yii::app()->user->isGuest && isset(Yii::app()->user->access_token)){
            $url = Yii::app()->params['oauth']['api_url'] . '/profile';
            $response = Guzzle::get($url, array(
                    'headers' => array(
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer invalidtoken',
                    ),
                    'exceptions' => false,
                ));
            if($response->isSuccessful()){
                return $response->json();
            } else {
                return json_decode($response->getBody(true),true);
            }
        }
    }
}