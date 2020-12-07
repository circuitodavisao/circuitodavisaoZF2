<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Form\AtualizarCadastroForm;
use Application\Form\CelulaForm;
use Application\Form\EventoForm;
use Application\Form\GrupoForm;
use Application\Form\TransferenciaForm;
use Application\Model\Entity\Entidade;
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
            $numeroDePassos = 3;
//            if ($this->view->entidadeTipo == Entidade::PRESIDENTE ||
//                    $this->view->entidadeTipo == Entidade::NACIONAL ||
//                    $this->view->entidadeTipo == Entidade::REGIONAL ||
//                    $this->view->entidadeTipo == Entidade::COORDENACAO) {
//
//                $numeroDePassos = 3;
//            }
        }
        if ($this->getForm() instanceof AtualizarCadastroForm || $this->getForm() instanceof EventoForm) {
            $numeroDePassos = 3;
        }
        if ($this->getForm() instanceof CelulaForm) {
            $numeroDePassos = 4;
        }
        if ($this->getForm() instanceof TransferenciaForm) {
            $numeroDePassos = 4;
        }

        $conteudo = '';
        $conteudo .= '<div class="stepwizard-row">';
        for ($indiceDePonto = 1; $indiceDePonto <= $numeroDePassos; $indiceDePonto++) {
            $nomePonto = '';
            /* Cadastro de grupo */
            if ($this->getForm() instanceof GrupoForm) {
//                if ($this->view->entidadeTipo == 1 || $this->view->entidadeTipo == 2 || $this->view->entidadeTipo == 3 || $this->view->entidadeTipo == 4) {
                    switch ($indiceDePonto) {
                        case 1:
                            $nomePonto = $this->view->translate(Constantes::$TRADUCAO_PASSO_A_PASSO_DADOS_PESSOAIS);
                            break;
                        case 2:
                            $nomePonto = 'Nome e Email';
                            break;
                        case 3:
                            $nomePonto = $this->view->translate(Constantes::$TRADUCAO_PASSO_A_PASSO_HIERARQUIA);
                            break;
                        default:
                            break;
                    }
//                } else {
//                    switch ($indiceDePonto) {
//                        case 1:
//                            $nomePonto = $this->view->translate(Constantes::$TRADUCAO_PASSO_A_PASSO_SELECIONE_O_ALUNO);
//                            break;
//                        case 2:
//                            $nomePonto = $this->view->translate(Constantes::$TRADUCAO_PASSO_A_PASSO_DADOS_PESSOAIS);
//                            break;
//                        case 3:
//                            $nomePonto = $this->view->translate(Constantes::$TRADUCAO_PASSO_A_PASSO_EMAIL);
//                            break;
//                        case 4:
//                            $nomePonto = $this->view->translate(Constantes::$TRADUCAO_PASSO_A_PASSO_HIERARQUIA);
//                            break;
//                        default:
//                            break;
//                    }
//                }
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
            /* Evento culto */
            if ($this->getForm() instanceof EventoForm) {
              if ($this->view->pagina == Constantes::$PAGINA_EVENTO_DISCIPULADO) {
                switch ($indiceDePonto) {
                    case 1:
                        $nomePonto = 'Dia e Hora';
                        break;
                    case 2:
                        $nomePonto = 'Nome';
                        break;
                    case 3:
                        $nomePonto = 'Confirmação';
                        break;
                    default:
                        break;
                }
              } else {
                switch ($indiceDePonto) {
                    case 1:
                        $nomePonto = 'Dia e Hora';
                        break;
                    case 2:
                        $nomePonto = 'Nome e Equipes';
                        break;
                    case 3:
                        $nomePonto = 'Confirmação';
                        break;
                    default:
                        break;
                }
              }
            }
            /* Evento celula */
            if ($this->getForm() instanceof CelulaForm) {
                switch ($indiceDePonto) {
                    case 1:
                        $nomePonto = 'Dia e Hora';
                        break;
                    case 2:
                        $nomePonto = 'Endereço';
                        break;
                    case 3:
                        $nomePonto = 'Dados Hospedeiro';
                        break;
                    case 4:
                        $nomePonto = 'Confirmação';
                        break;
                    default:
                        break;
                }
            }
            /* Cadastro de transferencia */
            if ($this->getForm() instanceof TransferenciaForm) {
                switch ($indiceDePonto) {
                    case 1:
                        $nomePonto = 'Lider(es) para transferir';
                        break;
                    case 2:
                        $nomePonto = 'Será discipulo de quem?';
                        break;
                    case 3:
                        $nomePonto = 'Confirmação';
                        break;
                    case 4:
                        $nomePonto = 'Senha';
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
            if ($this->view->entidadeTipo == 1 || $this->view->entidadeTipo == 2 || $this->view->entidadeTipo == 3 || $this->view->entidadeTipo == 4) {
                $class .= ' stepwizard-step-cadastro-grupo-acima';
            }
        }
        if ($this->getForm() instanceof AtualizarCadastroForm) {
            $class .= ' stepwizard-step-atualizacao-grupo';
        }
        if ($this->getForm() instanceof EventoForm) {
            $class .= ' stepwizard-step-cadastro-evento-culto';
        }
        if ($this->getForm() instanceof CelulaForm) {
            $class .= ' stepwizard-step-cadastro-evento-celula';
        } if ($this->getForm() instanceof TransferenciaForm) {
            $class .= ' stepwizard-step-cadastro-transferencia';
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
