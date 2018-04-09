<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: ListagemLideresTransferencia.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de lideres para transferir
 */
class ListagemLideresTransferencia extends AbstractHelper {

    private $discipulos;

    public function __construct() {
        
    }

    public function __invoke($discipulos) {
        $this->setDiscipulos($discipulos);
        return $this->renderHtml();
    }

    public function verificarSeMostrarONo($grupo, $solicitacoes) {
        $mostrar = true;
        foreach ($solicitacoes as $solicitacao) {
            if ($grupo->getId() == $solicitacao->getObjeto1()) {
                $mostrar = false;
            }
        }
        return $mostrar;
    }

    public function renderHtml() {
        $html = '';
        $entidade = $this->view->grupo->getEntidadeAtiva();
        $nomeLideres = $this->view->grupo->getNomeLideresAtivos();
        $informacao = $nomeLideres . ' - ' . $entidade->infoEntidade();
        $html .= '<li id="lider_' . $this->view->grupo->getId() . '">' . $informacao;
        foreach ($this->getDiscipulos() as $gpFilho) {
            $grupo = $gpFilho->getGrupoPaiFilhoFilho();
            $mostrar = $this->verificarSeMostrarONo($grupo, $this->view->solicitacoes);
            if ($grupo->verificarSeEstaAtivo()) {
                if ($mostrar) {
                    $mostrarFolder = false;
                    if ($grupo->getGrupoPaiFilhoFilhosAtivosReal()) {
                        $mostrarFolder = true;
                    }
                    $entidade = $grupo->getEntidadeAtiva();
                    $nomeLideres = $grupo->getNomeLideresAtivos();
                    $informacao = $nomeLideres . ' - ' . $entidade->infoEntidade();
                    $class = '';
                    if ($grupo->getGrupoPaiFilhoFilhosAtivosReal()) {
                        $class = '';
                    }
                    $html .= '<li id="' . $grupo->getId() . '" class="' . $class . '">' . $informacao;
                    if ($dispulos144 = $grupo->getGrupoPaiFilhoFilhosAtivosReal()) {
                        $html .= '<ul>';
                        foreach ($dispulos144 as $gpFilho144) {
                            $grupoFilho144 = $gpFilho144->getGrupoPaiFilhoFilho();
                            $mostrar = $this->verificarSeMostrarONo($grupoFilho144, $this->view->solicitacoes);
                            if ($grupoFilho144->verificarSeEstaAtivo()) {
                                if ($mostrar) {
                                    $entidade = $grupoFilho144->getEntidadeAtiva();
                                    $nomeLideres = $grupoFilho144->getNomeLideresAtivos();
                                    $informacao = $nomeLideres . ' - ' . $entidade->infoEntidade();
                                    $class = '';
                                    if ($grupoFilho144->getGrupoPaiFilhoFilhosAtivosReal()) {
                                        $class = '';
                                    }
                                    $html .= '<li id="' . $grupoFilho144->getId() . '" class="' . $class . '">' . $informacao;
                                    if ($dispulos1728 = $grupoFilho144->getGrupoPaiFilhoFilhosAtivosReal()) {
                                        $html .= '<ul>';
                                        foreach ($dispulos1728 as $gpFilho1728) {
                                            $grupoFilho1728 = $gpFilho1728->getGrupoPaiFilhoFilho();
                                            $mostrar = $this->verificarSeMostrarONo($grupoFilho1728, $this->view->solicitacoes);
                                            if ($grupoFilho1728->verificarSeEstaAtivo()) {
                                                if ($mostrar) {
                                                    $entidade = $grupoFilho1728->getEntidadeAtiva();
                                                    $nomeLideres = $grupoFilho1728->getNomeLideresAtivos();
                                                    $informacao = $nomeLideres . ' - ' . $entidade->infoEntidade();
                                                    $class = '';
                                                    if ($grupoFilho1728->getGrupoPaiFilhoFilhosAtivosReal()) {
                                                        $class = '';
                                                    }
                                                    $html .= '<li id="' . $grupoFilho1728->getId() . '" class="' . $class . '">' . $informacao;
                                                    if ($dispulos20736 = $grupoFilho1728->getGrupoPaiFilhoFilhosAtivosReal()) {
                                                        $html .= '<ul>';
                                                        foreach ($dispulos20736 as $gpFilho20736) {
                                                            $grupoFilho20736 = $gpFilho20736->getGrupoPaiFilhoFilho();
                                                            $mostrar = $this->verificarSeMostrarONo($grupoFilho20736, $this->view->solicitacoes);
                                                            if ($grupoFilho20736->verificarSeEstaAtivo()) {
                                                                if ($mostrar) {
                                                                    $entidade = $grupoFilho20736->getEntidadeAtiva();
                                                                    $nomeLideres = $grupoFilho20736->getNomeLideresAtivos();
                                                                    $informacao = $nomeLideres . ' - ' . $entidade->infoEntidade();
                                                                    $html .= '<li id="' . $grupoFilho20736->getId() . '">' . $informacao;
                                                                }
                                                            }
                                                        }
                                                        $html .= '</ul>';
                                                    }
                                                }
                                            }
                                            $html .= '</li>';
                                        }
                                        $html .= '</ul>';
                                    }
                                }
                            }
                            $html .= '</li>';
                        }
                        $html .= '</ul>';
                    }
                    $html .= '</li>';
                }
            }
        }
        return $html;
    }

    function getDiscipulos() {
        return $this->discipulos;
    }

    function setDiscipulos($discipulos) {
        $this->discipulos = $discipulos;
    }

}
