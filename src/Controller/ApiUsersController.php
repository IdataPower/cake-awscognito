<?php
namespace PowerSystem\CognitoSDK\Controller;

use PowerSystem\CognitoSDK\Controller\AppController;
use PowerSystem\CognitoSDK\Model\Table\ApiUsersTable;
use PowerSystem\CognitoSDK\Controller\Traits\BaseCrudTrait;
use PowerSystem\CognitoSDK\Controller\Traits\ImportApiUsersTrait;
use PowerSystem\CognitoSDK\Controller\Traits\AwsCognitoTrait;
use Muffin\Footprint\Auth\FootprintAwareTrait;

class ApiUsersController extends AppController
{

    use FootprintAwareTrait;
    use BaseCrudTrait;
    use ImportApiUsersTrait;
    use AwsCognitoTrait;

    public $paginate = [
        'limit' => 100,
        'order' => ['ApiUsers.aws_cognito_username' => 'asc'],
    ];

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Search.Prg', [
            'actions' => ['index']
        ]);
    }
}
