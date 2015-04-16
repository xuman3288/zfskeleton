<?php
namespace Test;

use Gzfextra\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager        = $e->getApplication()->getServiceManager();
        AbstractTableGateway::setServiceLocator($serviceManager);
        GlobalAdapterFeature::setStaticAdapter($serviceManager->get('db'));
        \Zend\Validator\AbstractValidator::setDefaultTranslator($e->getApplication()->getServiceManager()->get('translator'));
    }
    public function getConfig()
    {
        $configs = include __DIR__ . '/config/module.config.php';
        $configs += include( __DIR__ . '/config/table.config.php');
        return $configs;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
