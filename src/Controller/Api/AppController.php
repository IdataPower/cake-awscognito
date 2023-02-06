<?php
namespace PowerSystem\CognitoSDK\Controller\Api;

use PowerSystem\CognitoSDK\Controller\AppController as BaseController;

class AppController extends BaseController
{
	public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Auth');

        $this->loadComponent('PowerSystem/ApiGatewaySDK.ApiRequest');

        $this->Auth->config('authenticate', [
            'PowerSystem/CognitoSDK.AwsCognitoJwt' => [
            	'userModel' => 'PowerSystem/CognitoSDK.ApiUsers'
            ]
        ]);

        $this->Auth->config('storage', 'Memory');
        $this->Auth->config('unauthorizedRedirect', false);
        $this->Auth->config('loginAction', false);
        $this->Auth->config('loginRedirect', false);
    }

}
