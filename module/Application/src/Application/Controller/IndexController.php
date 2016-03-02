<?php

namespace Application\Controller;

use Application\Entity\Teste;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    public function indexAction() {
        $objectManager = $this
                ->getServiceLocator()
                ->get('Doctrine\ORM\EntityManager');

        $teste = new Teste();
        $teste->setNome('Leonardo Pereira Magalhaes');
        $teste->setTelefone(85071955);

        $objectManager->persist($teste);
        $objectManager->flush();

        var_dump($teste->getId());
        return new ViewModel();
    }

}
