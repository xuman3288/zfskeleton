<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class TestController extends AbstractActionController
{

    public function indexAction()
    {
        if($this->request->isPost()) {
            return new JsonModel(['code' => 0]);
        }
        return new ViewModel();
    }


}

