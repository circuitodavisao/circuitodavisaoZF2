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

    public function renderHtml() {
        $html = '';
        foreach ($this->getDiscipulos() as $gpFilho) {
            $grupo = $gpFilho->getGrupoPaiFilhoFilho();
            $this->montarLinhaLider($grupo);

            $dispulos144 = $grupo->getGrupoPaiFilhoFilhos();
            foreach ($dispulos144 as $gpFilho144) {
                $grupoFilho144 = $gpFilho144->getGrupoPaiFilhoFilho();
                $this->montarLinhaLider($grupoFilho144);

                $dispulos1728 = $grupoFilho144->getGrupoPaiFilhoFilhos();
                foreach ($dispulos1728 as $gpFilho1728) {
                    $grupoFilho1728 = $gpFilho1728->getGrupoPaiFilhoFilho();
                    $this->montarLinhaLider($grupoFilho1728);
                }
            }
        }
        return $html;
    }

    private function montarLinhaLider($grupo) {
        $entidade = $grupo->getEntidadeAtiva();
        $nomeLideres = $grupo->getNomeLideresAtivos();
        echo '<tr>';
        echo '<td>' . $nomeLideres . '</td>';
        echo '<td>' . $entidade->infoEntidade() . '</td>';
        echo '<td><button class="btn btn-xs btn-primary"><span class="fa fa-paper-plane"></span></button></td>';
        echo '</tr>';
    }

    function getDiscipulos() {
        return $this->discipulos;
    }

    function setDiscipulos($discipulos) {
        $this->discipulos = $discipulos;
    }

}
