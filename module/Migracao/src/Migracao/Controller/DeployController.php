<?php

namespace Migracao\Controller;

use Application\Controller\CircuitoController;
use Application\Controller\Helper\Constantes;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\ViewModel;

/**
 * Nome: DeployController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ação de deploy
 */
class DeployController extends CircuitoController {

    /**
     * Contrutor sobrecarregado com os serviços de ORM e Autenticador
     */
    public function __construct(
    EntityManager $doctrineORMEntityManager = null) {
        if (!is_null($doctrineORMEntityManager)) {
            parent::__construct($doctrineORMEntityManager);
        }
    }

    /**
     * Função padrão, traz a tela para login
     * GET /deploy
     */
    public function indexAction() {

        $token = $this->getEvent()->getRouteMatch()->getParam(Constantes::$ID, 0);
        if ($token === 'c76ec8866438d1e6ddc90909b0debbe3') {
            $stringHashtag = '###';
            $gitUser = 'lpmagalhaes';
            $gitPassword = 'leonardo142857';

            $linkGit = 'github.com/circuitodavisao/circuitodavisaoZf2.git master';
		echo 'deploy automatico';
            echo $stringHashtag . 'Iniciando o deploy' . $stringHashtag . PHP_EOL;
            $comando = 'git pull https://' . $gitUser . ':' . $gitPassword . '@' . $linkGit;
            echo '<pre>';
            passthru($comando);
            echo '</pre>';
            echo $stringHashtag . 'Fim do deploy' . $stringHashtag . PHP_EOL;
        } else {
            echo "Sem token";
        }
    }

}
