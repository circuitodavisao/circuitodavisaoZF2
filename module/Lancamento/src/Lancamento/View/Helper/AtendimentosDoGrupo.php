<?php

namespace Lancamento\View\Helper;

use Cadastro\Form\ConstantesForm;
use Lancamento\Controller\Helper\ConstantesLancamento;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: AtendimentosDoGrupo.php
 * @author Lucas Filipe de Carvalho Cunha <lucascarvalho.esw@gmail.com>
 * Descricao: Classe helper view para listar os atendimentos de  um grupo especifico.
 */
class AtendimentosDoGrupo extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<div class="panel-body bg-light">';
        $html .= '<table class="table">';
        $html .= '<thead>';
        $html .= '<th class="text-center">Dia do Atendimento</th>';
        $html .= '<th class="text-center hidden-xs">Quem</th>';
        $html .= '<th></th>';
        $html .= '</thead>';
        $html .= '<tbody>';




        foreach ($this->view->atendimentosGrupo as $ag) {
            $html .= '<tr>';
            $html .= '<td class="text-center">' . $ag->getDia() . '</td>';
            if ($ag->getQuem() == 0) {
                $labelQuem = 'AMBOS';
            } else {
                $labelQuem = 'LEONARDO';
            }
            $html .= '<td class="text-center hidden-xs">' . $labelQuem . '</td>';
            $html .= '<td class="text-center">';
            $stringNomeDaFuncaoOnClickEdicao = 'funcaoLancamento("' . ConstantesLancamento::$PAGINA_LANCAR_ATENDIMENTO_EDIT . '", ' . $ag->getId() . ')';
            $stringNomeDaFuncaoOnClickExclusao = 'funcaoLancamento("' . ConstantesLancamento::$PAGINA_ATENDIMENTO_EXCLUSAO . '", ' . $ag->getId() . ')';
            $html .= $this->view->botaoLink(ConstantesForm::$STRING_ICONE_PENCIL, ConstantesForm::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickEdicao));
            $html .= $this->view->botaoLink(ConstantesForm::$STRING_ICONE_TIMES, ConstantesForm::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

}
