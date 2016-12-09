<?php

namespace Cadastro\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: PassoAPasso.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para montar blocos com passo a passo
 */
class PassoAPasso extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $id = 'passos';
        $class = 'stepwizard';
        $conteudo = '';
        $conteudo .= '<div class="stepwizard-row">';
        for ($indiceDePonto = 1; $indiceDePonto <= 4; $indiceDePonto++) {
            $nomePonto = '';
            switch ($indiceDePonto) {
                case 1:
                    $nomePonto = 'Select the Student';
                    break;
                case 2:
                    $nomePonto = 'Personal Data';
                    break;
                case 3:
                    $nomePonto = 'Email';
                    break;
                case 4:
                    $nomePonto = 'Hierarchy';
                    break;
                default:
                    break;
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
        $conteudo = '';
        $conteudo .= '<button id="botaoPasso' . $id . '" type="button" class="btn btn-' . $corPonto . ' btn-circle" disabled="disabled">' . $id . '</button>';
        $conteudo .= '<p>' . $this->view->translate($nomePonto) . '</p>';
        $html .= $this->view->blocoDiv($id, $class, $conteudo);
        return $html;
    }

}
