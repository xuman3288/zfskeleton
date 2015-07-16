<?php

namespace Application\Controller;

use FileUpload\FileUpload;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class TestController extends AbstractActionController
{

    public function indexAction()
    {
        if($this->request->isPost()) {

            $ret = FileUpload::piecesSaveTo('data/res',FileUpload::getSessionId(), $_POST);
            return new JsonModel(['code' => 0,'ret' =>$ret]);
        }
        return new ViewModel();
    }


}

