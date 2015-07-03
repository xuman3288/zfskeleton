<?php

namespace Test\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OAuth2Controller extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }


}

