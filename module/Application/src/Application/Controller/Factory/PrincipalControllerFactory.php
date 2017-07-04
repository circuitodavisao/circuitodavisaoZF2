<?php

namespace Application\Controller\Factory;

use Application\Controller\PrincipalController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Nome: PrincipalControllerFactory.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe para inicializar o controle
 */
class PrincipalControllerFactory extends CircuitoControllerFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $sm = $serviceLocator->getServiceLocator();
        $doctrineORMEntityManager = parent::createServiceORM($sm);
        return new PrincipalController($doctrineORMEntityManager);
    }

}
