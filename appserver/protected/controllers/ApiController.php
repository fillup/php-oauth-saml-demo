<?php

use fkooman\OAuth\ResourceServer\ResourceServer;
use fkooman\OAuth\ResourceServer\ResourceServerException;
use Guzzle\Http\Client;

class ApiController extends Controller
{
    /**
     * @var array
     */
    private $_token;

    public function filters()
    {
        return array(
            'validateOauthToken',
        );
    }

    public function actionProfile()
    {
        if($this->hasScope('profile')){
            $this->returnJson(array(
                'name' => 'testing',
                'email' => 'testing@test.com',
            ));
        } else {
            $this->returnError(new \Exception('You do not have the required scope: profile.',403),403);
        }
    }

    public function actionTokenInfo()
    {
        if($this->hasScope('tokeninfo')){
            $this->returnJson($this->getToken());
        } else {
            $this->returnError(new \Exception('You do not have the required scope: tokeninfo.',403),403);
        }
    }

    public function actionDocuments()
    {
        if($this->hasScope('documents')){
            $this->returnJson(array(
                'name' => 'testing',
                'email' => 'testing@test.com',
                'documents' => array(
                    '1' => array(
                        'name' => 'Document 1',
                        'type' => 'PDF',
                        'last_modified' => date('Y-m-d H:i:s',time()-60*60*24*3),
                    ),
                    '2' => array(
                        'name' => 'Document 2',
                        'type' => 'DOCX',
                        'last_modified' => date('Y-m-d H:i:s',time()-60*60*24*5),
                    ),
                ),
            ));
        } else {
            $this->returnError(new \Exception('You do not have the required scope: documents.',403),403);
        }
    }

    public function actionNotAllowed()
    {
        if($this->hasScope('notallowed')){
            $this->returnJson(array(
                'status' => 'oh wow, you have access to a scope that doesn\' exist!'
            ));
        } else {
            $this->returnError(new \Exception('You do not have the required scope: notallowed.',403),403);
        }
    }

    private function setToken($token)
    {
        $this->_token = $token;
    }

    public function getToken()
    {
        return isset($this->_token) ? $this->_token : array();
    }

    public function hasScope($req_scope)
    {
        $allowed_scopes = explode(' ',$this->_token['scope']);
        if(!in_array($req_scope,$allowed_scopes)){
            return false;
        }
        return true;
    }

    public function filterValidateOauthToken($filterChain)
    {
        /**
         * Validate OAuth token before continuing
         */
        // get the Authorization header (if provided, through ugly Apache function)
        //$requestHeaders = apache_request_headers();
        //$authorizationHeader = isset($requestHeaders['Authorization']) ? $requestHeaders['Authorization'] : false;
        $authorizationHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : false;

        if($authorizationHeader){
            try {
                // initialize the Resource Server, point it to introspection endpoint
                $resourceServer = new ResourceServer(
                    new Client(
                        "http://oauth.local/introspect.php"
                    )
                );
                $resourceServer->setAuthorizationHeader($authorizationHeader);

                // get the query parameter (if provided)
                $accessTokenQueryParameter = isset($_GET['access_token']) ? $_GET['access_token'] : null;
                $resourceServer->setAccessTokenQueryParameter($accessTokenQueryParameter);

                // now verify the token
                $tokenIntrospection = $resourceServer->verifyToken();

                // NOTE: only getActive() is required to be available, any of the other
                // introspection method objects can return "false" when not provided
                // by the introspection endpoint, so you MUST check for that!
                $scope = explode(" ", $tokenIntrospection->getScope());
                if (!is_array($scope) || count($scope) < 1) {
                    $this->returnError(new \Exception("Insufficient privileges, no authorized scopes found.",403),403);
                }

                $this->setToken($tokenIntrospection->getToken());

            } catch (ResourceServerException $e) {
                $this->returnError(new \Exception($e->getMessage().", ".$e->getDescription().' ('.$authorizationHeader.')',
                        $e->getStatusCode()),$e->getStatusCode());
            } catch (Exception $e) {
                $this->returnError(new \Exception($e->getMessage(), 400), 400);
            }

            $filterChain->run();

        } else {
            $e = new \Exception('Missing Authorization header containing Bearer token',403);
            $this->returnError($e,403);
        }
    }

}