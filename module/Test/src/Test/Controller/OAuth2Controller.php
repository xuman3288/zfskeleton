<?php

namespace Test\Controller;

use Predis\Client;
use Test\Model\TestTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use OAuth2;

class OAuth2Controller extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function testAction()
    {
        $pdo = TestTable::getInstance()->getAdapter()->getDriver()->getConnection()->getResource();
        $storage = new OAuth2\Storage\Pdo($pdo);
        $server = new OAuth2\Server($storage);
        $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage)); // or any grant type you like!
        $server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
        exit;
    }
    public function tokenAction()
    {
        return $this->getServiceLocator()->get('MyOAuth2Provider')
            ->handleTokenRequest()
            ->makeHttpResponse();
    }

    public function authorizationAction()
    {
        $provider = $this->getServiceLocator()->get('MyOAuth2Provider');

        //Reject the request if it does not comply with OAuth 2.0 rules
        if (!$provider->validateAuthorizeRequest())
            return $provider->makeHttpResponse();

        //If the user has submitted the logon form, validate their password
        $view = new ViewModel();
        if ($this->getRequest()->isPost())
        {
            $userId = $this->params()->fromPost('user_id');
            $password = $this->params()->fromPost('password');
            if ($this->_passwordIsCorrect($userId, $password))
            {
                return $provider
                    ->handleAuthorizeRequest(true, $userId)
                    ->makeHttpResponse();
            }
            else
                $view->message = 'Your user ID or password was incorrect.';
        }
        return $view;
    }

    protected function _passwordIsCorrect($userId, $password)
    {
        //Your logic
        return true;
    }
    public function myApiEndpointAction()
    {
        //Authenticate
        /** @var \Codeacious\OAuth2Provider\Provider $provider */
        $provider = $this->getServiceLocator()->get('MyOAuth2Provider');
        if (!$provider->verifyResourceRequest())
            return $provider->makeHttpResponse();

        //Get the authenticated user
        $userId = $provider->getIdentity()->getUserId();

        //Your logic here
    }

    public function test2Action()
    {
        $predis = new Client(['password' => 'passpasspasspasspasspasspass']);
        echo $predis->get('name');
        $predis->set('name',time());
        //$storage = new OAuth2\Storage\Redis($predis);
        //$storage->setClientDetails('10001','pass', 'http://zf2-skeleton.localhost/test/o-auth2/test3',null,1);
        exit;
    }

    public function test3Action()
    {
        return new JsonModel(['code' => 1]);
    }
}

