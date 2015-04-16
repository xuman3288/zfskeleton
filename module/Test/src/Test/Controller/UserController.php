<?php

namespace Test\Controller;

use Test\Form\Test;
use Zend\Mvc\Controller\AbstractActionController;
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
        //$row = TestTable::getInstance()->find(1);
                $this->url();
                return new ViewModel();
    }

    public function processAction()
    {
        // ... do some work ...
                $this->flashMessenger()->addMessage('You are now logged in.');
                $api = $this->forward()->dispatch('Test\Controller\User',['action' => 'api']);//调用其他controller的功能
                return $api;
                //return $this->redirect()->toRoute(null, array('module'=>'test','controller'=>'user','action' => 'success'));
    }

    public function successAction()
    {
        $return = array('success' => true);
                $flashMessenger = $this->flashMessenger();
                if ($flashMessenger->hasMessages()) {
                    $return['messages'] = $flashMessenger->getMessages();
                }
                return new JsonModel($return);
    }

    public function testformAction()
    {
        $form = new Test();
        if($this->request->isPost()) {

            $form->init();
            $form->setData($_POST);
            if($form->isValid()){

            }
        }
        return new ViewModel([
            'form' => $form
        ]);


    }


}

