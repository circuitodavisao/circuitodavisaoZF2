<?php

namespace Login\Controller\Factory;

use Login\Controller\LoginController;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\Exception\ExtensionNotLoadedException;

/**
 * Nome: LoginControllerFactory.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe para inicializar o controle
 */
class LoginControllerFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $sm = $serviceLocator->getServiceLocator();
        try {
            $doctrineORMEntityManager = $sm->get('Doctrine\ORM\EntityManager');
        } catch (ServiceNotCreatedException $e) {
            $doctrineORMEntityManager = null;
        } catch (ExtensionNotLoadedException $e) {
            $doctrineORMEntityManager = null;
        }
        $controller = new LoginController($doctrineORMEntityManager);
        return $controller;
    }

}
