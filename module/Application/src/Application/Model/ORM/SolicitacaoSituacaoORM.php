<?php

namespace Application\Model\ORM;

use Application\Controller\Helper\Constantes;
use Application\Model\Entity\Solicitacao;
use Application\Model\ORM\CircuitoORM;
use Exception;

/**
 * Nome: SolicitacaoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine a entity solicitacao_situacao
 */
class SolicitacaoSituacaoORM extends CircuitoORM {

  public function encontrarSolicitacoesAceitasAgendadasAtivas() {
      $resposta = null;
      try {
          $solicitacaoSituacao = $this->getEntityManager()
                  ->getRepository($this->getEntity())
                  ->findBy(array('situacao_id' => 3, 'data_inativacao' => null));
          if ($solicitacaoSituacao) {
              $resposta = $solicitacaoSituacao;
          }
          return $resposta;
      } catch (Exception $exc) {
          echo $exc->getTraceAsString();
      }
  }
}
