<?php

namespace Application\Controller\Factory;

use Application\Controller\Factory\CircuitoControllerFactory;
use Application\Controller\LancamentoController;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\Exception\ExtensionNotLoadedException;

/**
 * Nome: LancamentoControllerFactory.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe para inicializar o controle
 */
class LancamentoControllerFactory extends CircuitoControllerFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $sm = $serviceLocator->getServiceLocator();
        $doctrineORMEntityManager = parent::createServiceORM($sm);

        // Serviço de tradução
        try {
            $translator = $sm->get('translator');
        } catch (ServiceNotCreatedException $e) {
            $translator = null;
        } catch (ExtensionNotLoadedException $e) {
            $translator = null;
        }

        return new LancamentoController($doctrineORMEntityManager, $translator);
    }

}
