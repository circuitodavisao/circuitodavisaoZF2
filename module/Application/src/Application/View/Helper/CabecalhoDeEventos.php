<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Funcoes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CabecalhoDeEventos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar o cabecalhos de eventos para ciclo, mes e entidade
 */
class CabecalhoDeEventos extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $grupoEventoNoPeriodo = $this->view->grupo->getGrupoEventoNoPeriodo($this->view->periodo); 
        if (!empty($grupoEventoNoPeriodo)) {
            $html .= '<tr>';
            $html .= '<th class="tdTipo"></th>';
            $html .= '<th class="tdNome text-right">Totais</th>';
            foreach ($grupoEventoNoPeriodo as $grupoEvento) {
                $diaDaSemanaAjustado = Funcoes::diaDaSemanaPorDia($grupoEvento->getEvento()->getDia());
                $eventoNome = Funcoes::nomeDoEvento($grupoEvento->getEvento()->getTipo_id());

                $html .= '<th class="text-center">';
                $html .= '<div style="font-size:9px; width:100%">'
                        . $this->view->translate($eventoNome)
                        . '</div>';
                $html .= '<div style="font-size:8px; width:100%">'
                        . $this->view->translate($diaDaSemanaAjustado) . $grupoEvento->getEvento()->getHoraFormatoHoraMinuto()
                        . '</div>';

                /* Totais */
//                $evento = $grupoEvento->getEvento();
//                $html .= "<div style='width:100%' id='total_{$evento->getId()}'>";
//                $eventoFrequencia = $evento->getEventoFrequencia();
//                $total = 0;
//                if (count($eventoFrequencia) > 0) {
//                    $grupoPessoas = $grupo->getGrupoPessoaAtivasEDoMes($mesSelecionado, $anoSelecionado);
//                    $pessoasParaComparar = array();
//                    foreach ($grupo->getResponsabilidadesAtivas() as $grupoResponsavel) {
//                        $pessoasParaComparar[] = $grupoResponsavel->getPessoa()->getId();
//                    }
//                    if ($grupoPessoas) {
//                        foreach ($grupoPessoas as $grupoPessoa) {
//                            $pessoasParaComparar[] = $grupoPessoa->getPessoa()->getId();
//                        }
//                    }
//                    $criteria = Criteria::create()
//                            ->andWhere(Criteria::expr()->eq("ano", $anoSelecionado))
//                            ->andWhere(Criteria::expr()->eq("mes", $mesSelecionado))
//                            ->andWhere(Criteria::expr()->eq("ciclo", $this->view->cicloSelecionado))
//                            ->andWhere(Criteria::expr()->eq("frequencia", "S"))
//                            ->andWhere(Criteria::expr()->in("pessoa_id", $pessoasParaComparar))
//                    ;
//                    $eventosFiltrados = $eventoFrequencia->matching($criteria);
//                    $total = count($eventosFiltrados);
//                }
//                $html .= $total;
//                $html .= "</div>";

                $html .= "</th>";
            }
            $html .= '</tr>';
        }
        return $html;
    }

}
