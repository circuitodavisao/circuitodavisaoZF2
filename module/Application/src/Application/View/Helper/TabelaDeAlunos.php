<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: TabelaDeAlunos.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar tabela com alunos
 */
class TabelaDeAlunos extends AbstractHelper {

    private $form;

    public function __construct() {
        
    }

    public function __invoke($form) {
        $this->setForm($form);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<table class="table footable" data-filter="#fooFilter">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th class = "hidden-xs">' . $this->view->translate(Constantes::$TRADUCAO_MATRICULA) . '</th>';
        $html .= '<th>' . $this->view->translate(Constantes::$TRADUCAO_NOME) . '</th>';
        $html .= '<th class ="hidden-xs">' . $this->view->translate(Constantes::$TRADUCAO_DATA_NASCIMENTO) . '</th>';
        $html .= '<td></td>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        if ($this->getForm()->getAlunos()) {
            foreach ($this->getForm()->getAlunos() as $ga) {
                $tipoResponsavel = 0;
                $turmaAluno = $ga->getTurmaAluno();
                $aluno = $turmaAluno->getPessoa();
                $aluno->setMatriculaAtual($turmaAluno->getMatricula());
                if ($aluno->getSexo() === 'M') {
                    $classeAluno = 'alunoM';
                    $tipoResponsavel = 1;
                }
                if ($aluno->getSexo() === 'F') {
                    $classeAluno = 'alunoF';
                    $tipoResponsavel = 2;
                }

                $valorRadio = $aluno->getMatriculaAtual() . '#' .
                        $aluno->getNome() . '#' .
                        $aluno->getDataNascimentoFormatada() . '#' .
                        $tipoResponsavel;
                $html .= '<tr class="' . $classeAluno . '">';
                $html .= '<td class="hidden-xs">' . $aluno->getMatriculaAtual() . '</td>';
                $html .= '<td><a href="#"  ' . $this->view->funcaoOnClick('selecionarAlunoPeloNome(' . $aluno->getId() . ')') . '><span class="hidden-xs">' . $aluno->getNome() . '</span><span class="visible-xs">' . $aluno->getNomePrimeiroPrimeiraSiglaUltimo() . '</span></a></td>';
                $html .= '<td class="hidden-xs">' . $aluno->getDataNascimentoFormatada() . '</td>';
                $html .= '<td>';
                $html .= '<input id="radio' . $aluno->getId() . '" type="radio" name="radioAlunoSelecionado" value="' . $valorRadio . '"  ' . $this->view->funcaoOnClick('mostrarBotaoDeSelecionarAluno()') . '/>';
                $html .= '</td>';
                $html .= '</tr>';
            }
        } else {
            echo '<tr><td>' . $this->view->translate(Constantes::$TRADUCAO_SEM_ALUNOS_CADASTRADOS) . '</td></tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '';
        return $html;
    }

    function getForm() {
        return $this->form;
    }

    function setForm($form) {
        $this->form = $form;
    }

}
