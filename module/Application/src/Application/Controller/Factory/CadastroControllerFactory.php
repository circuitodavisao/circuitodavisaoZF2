<?php

namespace Application\Controller\Factory;

use Application\Controller\CadastroController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Nome: CadastroControllerFactory.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe para inicializar o controle
 */
class CadastroControllerFactory extends CircuitoControllerFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $sm = $serviceLocator->getServiceLocator();
        $doctrineORMEntityManager = parent::createServiceORM($sm);
        return new CadastroController($doctrineORMEntityManager);
    }

}
