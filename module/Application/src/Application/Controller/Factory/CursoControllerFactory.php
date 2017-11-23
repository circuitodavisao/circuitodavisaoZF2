<?php

namespace Application\Controller\Factory;

use Application\Controller\CursoController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Nome: CursoControllerFactory.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe para inicializar o controle
 */
class CursoControllerFactory extends CircuitoControllerFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $sm = $serviceLocator->getServiceLocator();
        $doctrineORMEntityManager = parent::createServiceORM($sm);
        return new CursoController($doctrineORMEntityManager);
    }

}
