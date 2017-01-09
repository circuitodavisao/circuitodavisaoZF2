<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Form\AtualizarCadastroForm;
use Application\Form\GrupoForm;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: PassoAPasso.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar blocos com passo a passo
 */
class PassoAPasso extends AbstractHelper {

    private $form;

    public function __construct() {
        
    }

    public function __invoke($form) {
        $this->setForm($form);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $id = 'passos';
        $class = 'stepwizard';
        if ($this->getForm() instanceof GrupoForm) {
            $numeroDePassos = 4;
        }
        if ($this->getForm() instanceof AtualizarCadastroForm) {
            $numeroDePassos = 3;
        }
        $conteudo = '';
        $conteudo .= '<div class="stepwizard-row">';
        for ($indiceDePonto = 1; $indiceDePonto <= $numeroDePassos; $indiceDePonto++) {
            $nomePonto = '';
            /* Cadastro de grupo */
            if ($this->getForm() instanceof GrupoForm) {
                switch ($indiceDePonto) {
                    case 1:
                        $nomePonto = $this->view->translate(Constantes::$TRADUCAO_PASSO_A_PASSO_SELECIONE_O_ALUNO);
                        break;
                    case 2:
                        $nomePonto = $this->view->translate(Constantes::$TRADUCAO_PASSO_A_PASSO_DADOS_PESSOAIS);
                        break;
                    case 3:
                        $nomePonto = $this->view->translate(Constantes::$TRADUCAO_PASSO_A_PASSO_EMAIL);
                        break;
                    case 4:
                        $nomePonto = $this->view->translate(Constantes::$TRADUCAO_PASSO_A_PASSO_HIERARQUIA);
                        break;
                    default:
                        break;
                }
            }
            /* Atualização de grupo */
            if ($this->getForm() instanceof AtualizarCadastroForm) {
                switch ($indiceDePonto) {
                    case 1:
                        $nomePonto = 'Endereço';
                        break;
                    case 2:
                        $nomePonto = 'Telefone';
                        break;
                    case 3:
                        $nomePonto = 'Codigo de Confirmação';
                        break;
                    default:
                        break;
                }
            }
            $conteudo .= $this->montarUmPontoDoPassoAPasso($indiceDePonto, $nomePonto);
        }
        $conteudo .= '</div>';
        $html .= $this->view->blocoDiv($id, $class, $conteudo);
        return $html;
    }

    private function montarUmPontoDoPassoAPasso($id, $nomePonto) {
        $html = '';
        $corPonto = 'default';
        if ($id === 1) {
            $corPonto = 'primary';
        }
        $class = 'stepwizard-step';
        if ($this->getForm() instanceof GrupoForm) {
            $class .= ' stepwizard-step-cadastro-grupo';
        }
        if ($this->getForm() instanceof AtualizarCadastroForm) {
            $class .= ' stepwizard-step-atualizacao-grupo';
        }
        $conteudo = '';
        $conteudo .= '<button id="botaoPasso' . $id . '" type="button" class="btn btn-' . $corPonto . ' btn-circle" disabled="disabled">' . $id . '</button>';
        $conteudo .= '<p>' . $this->view->translate($nomePonto) . '</p>';
        $html .= $this->view->blocoDiv($id, $class, $conteudo);
        return $html;
    }

    function getForm() {
        return $this->form;
    }

    function setForm($form) {
        $this->form = $form;
        return $this;
    }

}
