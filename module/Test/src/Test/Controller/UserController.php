<?php

namespace Test\Controller;

use Test\Form\Test;
use Test\Model\TestTable;
use Zend\Json\Server\Client;
use Zend\Json\Server\Server;
use Zend\Mail\Headers;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{

    protected $acceptCriteria = array(
        'Zend\\View\\Model\\JsonModel' => array(
            'application/json'
        ),
        'Zend\\View\\Model\\FeedModel' => array(
            'application/rss+xml'
        )
    );

    public function apiAction()
    {
        $this->getServiceLocator()->get(111);
        $viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);

        // Potentially vary execution based on model returned
        if ($viewModel instanceof JsonModel) {
            // ...
            return new JsonModel(['code' => 0]);
        }
        return new JsonModel(['code' => 1]);
    }

    public function indexAction()
    {
        $row = TestTable::getInstance()->find(1);
        return var_dump($row);
        $this->url();
        return new ViewModel();
    }

    public function processAction()
    {
        // ... do some work ...
        $this->flashMessenger()->addMessage('You are now logged in.');
        $api = $this->forward()->dispatch('Test\Controller\User', ['action' => 'api']); //调用其他controller的功能
        return $api;
        //return $this->redirect()->toRoute(null, array('module'=>'test','controller'=>'user','action' => 'success'));
    }

    public function successAction()
    {
        $return         = array('success' => true);
        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            $return['messages'] = $flashMessenger->getMessages();
        }
        return new JsonModel($return);
    }

    public function testformAction()
    {
        $form = new Test();
        if ($this->request->isPost()) {

            $form->init();
            $form->setData($_POST);
            if ($form->isValid()) {

            }
        }
        return new ViewModel([
            'form' => $form
        ]);


    }

    public function testDBAction()
    {
        var_dump(TestTable::getInstance()->find(1));
        return new JsonModel(['ww' => 111]);
    }

    public function testMailAction()
    {
        $smtpOption = new SmtpOptions(
            array(
                'name'              => 'ztgame.com',
                'host'              => 'smtp.126.com',
                'connection_class'  => 'login',
                'connection_config' => array(
                    'username' => 'caigen3288@126.com',
                    'password' => 'sgllgizofvxihool',
                ),
            ));
        $smtp       = new Smtp($smtpOption);
        $message    = new Message();
        $message->setHeaders(Headers::fromString('Content-Type:text/html'))
            ->addTo('xuman3288@gmail.com', 'Fly126')
            ->addFrom('caigen3288@126.com', 'caigen')
            ->setSubject('Giant Mobile')
            ->setBody('hi,this is a mail with validate link:url');
        $smtp->send($message);
        return new JsonModel([1]);
    }

    public function getMenuAction()
    {
        $menus = array(

            'admin' => array(
                'text'     => '武极天下',
                'index'    => 0,
                'expanded' => true,
                'items'    => array(
                    0 => array(
                        'text'  => '用户管理',
                        'index' => 0,
                        'url'   => './admin/user',
                    ),
                    1 => array(
                        'text'  => '角色管理',
                        'index' => 1,
                        'url'   => './admin/role',
                    ),
                    2 => array(
                        'text'  => '权限管理',
                        'index' => 2,
                        'url'   => './admin/permission',
                    ),
                    4 => array(
                        'text'  => '个人信息',
                        'index' => 4,
                        'url'   => './ui/admin/index/self',
                    ),
                ),
            ),
        );
    }

    public function testServerAction()
    {
        $server = new Server();
        $server->setClass('Test\Rpc\TestRpc');
        $server->handle();
        exit;
    }

    public function testClientAction()
    {
        $client = new Client('http://zf2-skeleton.localhost/test/user/test-server');
        echo $client->call('test', [1, 2]);
        exit;
    }

    public function formConfigAction()
    {
        //form element
        return new ViewModel([
            'config' => [
                [
                    'type'   => 'kendoDropDownList',
                    'label'  => '渠道',
                    'name'   => 'channel',
                    'config' => [
                        'dataTextField'  => 'name',
                        'dataValueField' => 'id',
                        'dataSource'     => [
                            ['name' => "安卓混服", 'id' => 1],
                            ['name' => "IOS官服", 'id' => 2],
                            ['name' => "腾讯", 'id' => 3]
                        ]
                    ]
                ],
                [
                    'type'   => 'kendoDropDownList',
                    'label'  => '游戏',
                    'name'   => 'game',
                    'config' => [
                        'dataTextField'  => 'name',
                        'dataValueField' => 'id',
                        'dataSource'     => [
                            ['name' => "武极天下", 'id' => 1],
                            ['name' => "征途", 'id' => 2],
                            ['name' => "黑猫警长", 'id' => 3]
                        ]
                    ]
                ],
                [
                    'type'  => 'input',
                    'label' => '状态',
                    'name'  => 'channel',
                ]
            ]
        ]);
    }
}

