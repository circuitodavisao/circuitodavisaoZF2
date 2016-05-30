<?php

namespace Lancamento\View\Helper;

use Doctrine\Common\Collections\Criteria;
use Lancamento\Controller\Helper\FuncoesLancamento;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CabecalhoDeTotaisDosEventos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o cabecalhos dos totais dos eventos para ciclo, mes e entidade
 */
class CabecalhoDeTotaisDosEventos extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $mesSelecionado = FuncoesLancamento::mesPorAbaSelecionada($this->view->abaSelecionada);
        $anoSelecionado = FuncoesLancamento::anoPorAbaSelecionada($this->view->abaSelecionada);
        $grupo = $this->view->entidade->getGrupo();
        $eventos = $grupo->getGrupoEventoNoCiclo($this->view->cicloSelecionado);
        if (count($eventos) > 0) {
           foreach ($eventos as $ge) {
                /* Totais */
                $evento = $ge->getEvento();
               
                $html .= "<td id='total_{$evento->getId()}'>";
                $eventoFrequencia = $evento->getEventoFrequencia();
                $total = 0;
                if (count($eventoFrequencia) > 0) {
                    $criteria = Criteria::create()
                            ->andWhere(Criteria::expr()->eq("ano", $anoSelecionado))
                            ->andWhere(Criteria::expr()->eq("mes", $mesSelecionado))
                            ->andWhere(Criteria::expr()->eq("ciclo", $this->view->cicloSelecionado))
                            ->andWhere(Criteria::expr()->eq("frequencia", "S"))
                    ;
                    $eventosFiltrados = $eventoFrequencia->matching($criteria);
                    $total = count($eventosFiltrados);
                }
                $html .= $total;
                $html .= "</td>";
            }
        }
        return $html;
    }

}
