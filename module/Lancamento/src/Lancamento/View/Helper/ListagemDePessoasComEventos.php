<?php

namespace Lancamento\View\Helper;

use Doctrine\Common\Collections\Criteria;
use Lancamento\Controller\Helper\FuncoesLancamento;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemDePessoasComEventos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de eventos com frequencia
 */
class ListagemDePessoasComEventos extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $mesSelecionado = FuncoesLancamento::mesPorAbaSelecionada($this->view->abaSelecionada);
        $anoSelecionado = FuncoesLancamento::anoPorAbaSelecionada($this->view->abaSelecionada);
        $pessoas = array();
        $grupo = $this->view->entidade->getGrupo();
        foreach ($grupo->getResponsabilidadesAtivas() as $gr) {
            $p = $gr->getPessoa();
            $p->setTipo('LP');
            $pessoas[] = $p;
        }
        if (count($grupo->getGrupoPessoa()) > 0) {
            foreach ($grupo->getGrupoPessoa() as $gp) {
                $p = $gp->getPessoa();
                $p->setTipo($gp->getGrupoPessoaTipo()->getNomeSimplificado());
                $pessoas[] = $p;
            }
        }
        foreach ($pessoas as $pessoa) {
            $html .= '<tr>';
            $html .= '<td class="tdTipo">' .
                    '<button style="width: 100%;" class="btn btn-dark btn-xs">' . $pessoa->getTipo() . '</button>' .
                    '</td>';
            $html .= '<td class="tdNome text-left" class="text-left">&nbsp;' . $pessoa->getNomeListaDeLancamento() . '</td>';
            /* Listagem dos eventos */
            $grupo = $this->view->entidade->getGrupo();
            $eventos = $grupo->getGrupoEventoNoCiclo($this->view->cicloSelecionado);
            if (count($eventos) > 0) {
                foreach ($eventos as $ge) {
                    $valor = '';
                    $class = 'btn-default';
                    $classIco = 'fa-thumbs-down';
                    $evento = $ge->getEvento();
                    $eventoFrequencia = $evento->getEventoFrequencia();
                    $idEventoFrequencia = 'ico_' . $pessoa->getId() . '_' . $evento->getId();
                    if (count($eventoFrequencia) > 0) {
                        $criteria = Criteria::create()
                                ->andWhere(Criteria::expr()->eq("pessoa_id", $pessoa->getId()))
                                ->andWhere(Criteria::expr()->eq("ano", $anoSelecionado))
                                ->andWhere(Criteria::expr()->eq("mes", $mesSelecionado))
                                ->andWhere(Criteria::expr()->eq("ciclo", $this->view->cicloSelecionado))
                        ;
                        $eventosFiltrados = $eventoFrequencia->matching($criteria);
                        if ($eventosFiltrados->count() == 1) {
                            $valor = $eventosFiltrados->first()->getFrequencia();
                            if ($valor == 'S') {
                                $class = 'btn-success';
                                $classIco = 'fa-thumbs-up';
                            } else {
                                $class = 'btn-default';
                                $classIco = 'fa-thumbs-down';
                            }
                        }
                    }
                    $html .= '<td>';
                    $html .= '<div class="btn-group">';
                    $html .= '<button id="b_' . $idEventoFrequencia . '" type="button" class="btn ' . $class . ' btn-sm"'
                            . ' onclick=\'mudarFrequencia(';
                    $html .= "\"$idEventoFrequencia\", {$this->view->cicloSelecionado}, {$this->view->abaSelecionada}";
                    $html .= ');\'>';
                    $html .= '<i id="i_' . $idEventoFrequencia . '" class="fa ' . $classIco . '"></i>';
                    $html .= '</button>';
                    $html .= '</div>';
                    $html .= '</td>';
                }
            }
            $html .= '</tr>';
        }
        return $html;
    }

}
