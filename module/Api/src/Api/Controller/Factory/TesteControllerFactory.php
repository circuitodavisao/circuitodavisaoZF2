<?php

namespace Api\Controller\Factory;

use Api\Controller\TesteController;
use Application\Controller\Factory\CircuitoControllerFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TesteControllerFactory extends CircuitoControllerFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $sm = $serviceLocator->getServiceLocator();
        $doctrineORMEntityManager = parent::createServiceORM($sm);
        return new TesteController($doctrineORMEntityManager);
    }

}
