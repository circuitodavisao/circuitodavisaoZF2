<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Constantes;
use Application\Controller\Helper\Funcoes;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: TemplateFormularioRodape.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar a listagem de eventos
 */
class ListagemDeEventos extends AbstractHelper {

    private $titulo;
    private $grupoEventos;

    public function __construct() {
        
    }

    public function __invoke($titulo, $grupoEventos) {
        $this->setTitulo($titulo);
        $this->setGrupoEventos($grupoEventos);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';

        $tipoCelula = ($this->view->tipoEvento == 1);
        $tipoCulto = ($this->view->tipoEvento == 2);
        $tipoRevisao = ($this->view->tipoEvento == 3);
        $tipoRevisionistas = ($this->view->tipoEvento == 4);
        $tipoFichasRevisionistas = ($this->view->tipoEvento == 5);
        $tipoAtivosRevisionistas = ($this->view->tipoEvento == 6);
        $tipoLideresRevisao = ($this->view->tipoEvento == 7);
        $tipoAtivacaoFichas = ($this->view->tipoEvento == 8);
        $tipoListarRevisaoTurma = ($this->view->tipoEvento == 9);
        $tipoListarDiscipulados = ($this->view->tipoEvento == 10);
        $tipoListarLideres = ($this->view->tipoEvento == 11);
        $tipoSelecionarRevisaoCracha = ($this->view->tipoEvento == 12);
        $tipoListarRevisionistas = ($this->view->tipoEvento == 13);

        $html .= $this->view->templateFormularioTopo($this->getTitulo());
        $html .= '<div class="panel-body bg-light">';
        if (!empty($this->getGrupoEventos())) {
            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';

            /* Caso seja evento do tipo Célula */
            if ($tipoCelula) {
                $html .= '<th class="hidden-xs text-center">';
                $html .= 'Data de Criação';
                $html .= '</th>';
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_DIA_DA_SEMANA_SIMPLIFICADO) . ' / ' . $this->view->translate(Constantes::$TRADUCAO_HORA);
                $html .= '</th>';
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_NOME_HOSPEDEIRO);
                $html .= '</th>';
                $html .= '<th class="text-center visible-lg visible-md visible-sm">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_TELEFONE_HOSPEDEIRO);
                $html .= '</th>';
                $html .= '<th class="text-center visible-lg visible-md visible-sm">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_LOGRADOURO);
                $html .= '</th>';
            }
            if ($tipoCulto) {
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_DIA_DA_SEMANA_SIMPLIFICADO) . ' / ' . $this->view->translate(Constantes::$TRADUCAO_HORA);
                $html .= '</th>';
                $html .= '<th class="text-center visible-lg visible-md visible-sm">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_NOME);
                $html .= '</th>';
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_EQUIPES);
                $html .= '</th>';
            }
            if ($tipoRevisao) {
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_DATA_SIMPLIFICADO);
                $html .= '</th>';
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_OBSERVACAO);
                $html .= '</th>';
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_IGREJAS);
                $html .= '</th>';
            }
            if ($tipoRevisionistas || $tipoFichasRevisionistas || $tipoAtivosRevisionistas || $tipoLideresRevisao || $tipoListarLideres || $tipoSelecionarRevisaoCracha || $tipoListarRevisionistas) {
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_DATA_SIMPLIFICADO);
                $html .= '</th>';
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_OBSERVACAO);
                $html .= '</th>';
            }
            if ($tipoListarRevisaoTurma) {
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_REVISAO);
                $html .= '</th>';
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_OBSERVACAO);
                $html .= '</th>';
            }
            if ($tipoListarDiscipulados) {
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_DIA_DA_SEMANA_SIMPLIFICADO) . ' / ' . $this->view->translate(Constantes::$TRADUCAO_HORA);
                $html .= '</th>';
                $html .= '<th class="text-center visible-lg visible-md visible-sm">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_NOME);
                $html .= '</th>';
                $html .= '<th class="text-center">';
                $html .= $this->view->translate(Constantes::$TRADUCAO_EQUIPES);
                $html .= '</th>';
            }
            $html .= '<th class="text-center"></th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            foreach ($this->getGrupoEventos() as $grupoEvento) {
                $evento = $grupoEvento->getEvento();
                $diaDaSemanaAjustado = Funcoes::diaDaSemanaPorDia($evento->getDia());

                $html .= '<tr>';
                if ($tipoCelula) {
                    /* Verificar se a celula foi alterada recentimente para nao permitir */
                    $arrayPeriodo = Funcoes::montaPeriodo($periodo = 0);
                    $stringComecoDoPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
                    $dataDoInicioDoPeriodoParaComparar = strtotime($stringComecoDoPeriodo);
                    $dataParaComparar = strtotime($evento->getData_criacaoStringPadraoBanco());

                    $validarCadastroDepoisDoComecoDoPeriodo = false;
                    if ($dataParaComparar > $dataDoInicioDoPeriodoParaComparar) {
                        $validarCadastroDepoisDoComecoDoPeriodo = true;
                    }

                    $html .= '<th class="hidden-xs text-center">';
                    $html .= $evento->getData_criacaoStringPadraoBrasil();
                    $html .= '</th>';
                    $html .= '<td class="text-center">' . $this->view->translate($diaDaSemanaAjustado) . '/' . $evento->getHoraFormatoHoraMinutoParaListagem() . '</td>';
                    $celula = $evento->getEventoCelula();
                    $stringNomeDaFuncaoOnClick = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_EVENTO_CELULA . '", ' . $celula->getId() . ')';
                    $stringNomeDaFuncaoOnClickExclusao = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_EVENTO_EXCLUSAO . '", ' . $evento->getId() . ')';

                    $html .= '<td class="text-center">' . $celula->getNome_hospedeiroPrimeiroNome() . '</td>';
                    $html .= '<td class="text-center visible-lg visible-md visible-sm">' . $celula->getTelefone_hospedeiroFormatado() . '</td>';
                    $html .= '<td class="text-center visible-lg visible-md visible-sm">' . $celula->getLogradouro() . '&nbsp;' . $celula->getComplemento() . '</td>';
                    $html .= '<td class="text-center">';
                    if ($this->view->mostrarOpcoes) {
                        if (!$validarCadastroDepoisDoComecoDoPeriodo) {
                            $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PENCIL, Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
                        }
                    }
                    $html .= '</td>';
                }
                if ($tipoCulto) {

                    $html .= '<td class="text-center">' . $this->view->translate($diaDaSemanaAjustado) . '/' . $evento->getHoraFormatoHoraMinutoParaListagem() . '</td>';
                    $stringNomeDaFuncaoOnClick = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_EVENTO_CULTO . '", ' . $evento->getId() . ')';
                    $stringNomeDaFuncaoOnClickExclusao = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_EVENTO_EXCLUSAO . '", ' . $evento->getId() . ')';
                    $grupoEventoAtivos = $evento->getGrupoEventoAtivos();
                    $texto = '';
                    foreach ($grupoEventoAtivos as $gea) {
                        if ($this->view->extra != $gea->getGrupo()->getId()) {
                            $texto .= $gea->getGrupo()->getEntidadeAtiva()->infoEntidade() . '<br />';
                        }
                    }
                    $html .= '<td class="text-center visible-lg visible-md visible-sm">' . $evento->getNome() . '</span></td>';
                    $html .= '<td class="text-center">' . $this->view->BotaoPopover(count($grupoEventoAtivos) - 1, $texto) . '</td>';
                    $html .= '<td class="text-center">';
                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PENCIL, Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_TIMES, Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
                    $html .= '</td>';
                }
                if ($tipoRevisao) {

                    $html .= '<td class="text-center">' . Funcoes::mudarPadraoData($evento->getData()->forma, 1) . '</td>';
                    $stringNomeDaFuncaoOnClick = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_CADASTRO_REVISAO . '", ' . $evento->getId() . ')';
                    $stringNomeDaFuncaoOnClickExclusao = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_CADASTRO_REVISAO . '", ' . $evento->getId() . ')';
                    $grupoEventoAtivos = $evento->getGrupoEventoAtivos();
                    $texto = '';
                    foreach ($grupoEventoAtivos as $gea) {
                        if ($this->view->extra != $gea->getGrupo()->getId()) {
                            $texto .= $gea->getGrupo()->getEntidadeAtiva()->infoEntidade() . '<br />';
                        }
                    }
                    $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $evento->getNome() . '</span><span class="visible-sm visible-xs">' . $evento->getNomeAjustado() . '</span></td>';
                    $html .= '<td class="text-center">' . $this->view->BotaoPopover(count($grupoEventoAtivos) - 1, $texto) . '</td>';
                    $html .= '<td class="text-center">';
                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PENCIL, Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_TIMES, Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
                    $html .= '</td>';
                }
                if ($tipoRevisionistas) {

                    $html .= '<td class="text-center">' . Funcoes::mudarPadraoData($evento->getData(), 1) . '</td>';

                    $stringNomeDaFuncaoOnClickInserir = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_SELECIONAR_REVISIONISTA . '", ' . $evento->getId() . ')';
                    $grupoEventoAtivos = $evento->getGrupoEventoAtivos();
                    $texto = '';
                    foreach ($grupoEventoAtivos as $gea) {
                        if ($this->view->extra != $gea->getGrupo()->getId()) {
                            $texto .= $gea->getGrupo()->getEntidadeAtiva()->infoEntidade() . '<br />';
                        }
                    }
                    $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $evento->getNome() . '</span><span class="visible-sm visible-xs">' . $evento->getNomeAjustado() . '</span></td>';

                    $html .= '<td class="text-center">';

                    $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_NOVO_REVISIONISTA), Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickInserir));
                    $html .= '</td>';
                }
                if ($tipoFichasRevisionistas || $tipoListarLideres || $tipoSelecionarRevisaoCracha || $tipoListarRevisionistas) {

                    $html .= '<td class="text-center">' . Funcoes::mudarPadraoData($evento->getData(), 1) . '</td>';


                    $grupoEventoAtivos = $evento->getGrupoEventoAtivos();
                    $texto = '';
                    foreach ($grupoEventoAtivos as $gea) {
                        if ($this->view->extra != $gea->getGrupo()->getId()) {
                            $texto .= $gea->getGrupo()->getEntidadeAtiva()->infoEntidade() . '<br />';
                        }
                    }
                    $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $evento->getNome() . '</span><span class="visible-sm visible-xs">' . $evento->getNomeAjustado() . '</span></td>';

                    $html .= '<td class="text-center">';
                    if ($tipoFichasRevisionistas) {
                        $stringNomeDaFuncaoOnClickInserir = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_SELECIONAR_FICHA_REVISIONISTA . '", ' . $evento->getId() . ')';
                    }
                    if ($tipoListarLideres) {
                        $stringNomeDaFuncaoOnClickInserir = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_LISTA_LIDERES . '", ' . $evento->getId() . ')';
                    }
                    if ($tipoSelecionarRevisaoCracha) {
                        $stringNomeDaFuncaoOnClickInserir = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_SELECIONAR_REVISIONISTA_CRACHA . '", ' . $evento->getId() . ')';
                    }
                    if ($tipoListarRevisionistas) {
                        $stringNomeDaFuncaoOnClickInserir = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_LISTA_REVISIONISTAS . '", ' . $evento->getId() . ')';
                    }
                    $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_SELECIONE), Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickInserir));
                    $html .= '</td>';
                }
                if ($tipoLideresRevisao) {

                    $html .= '<td class="text-center">' . Funcoes::mudarPadraoData($evento->getData(), 1) . '</td>';

                    $stringNomeDaFuncaoOnClickInserir = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_SELECIONAR_LIDER_REVISAO . '", ' . $evento->getId() . ')';
                    $grupoEventoAtivos = $evento->getGrupoEventoAtivos();
                    $texto = '';
                    foreach ($grupoEventoAtivos as $gea) {
                        if ($this->view->extra != $gea->getGrupo()->getId()) {
                            $texto .= $gea->getGrupo()->getEntidadeAtiva()->infoEntidade() . '<br />';
                        }
                    }
                    $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $evento->getNome() . '</span><span class="visible-sm visible-xs">' . $evento->getNomeAjustado() . '</span></td>';

                    $html .= '<td class="text-center">';

                    $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_NOVO_REVISIONISTA), Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickInserir));
                    $html .= '</td>';
                }
                if ($tipoAtivosRevisionistas) {

                    $html .= '<td class="text-center">' . Funcoes::mudarPadraoData($evento->getData(), 1) . '</td>';

                    $stringNomeDaFuncaoOnClickInserir = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_SELECIONAR_FICHA_ATIVAS . '", ' . $evento->getId() . ')';

                    $grupoEventoAtivos = $evento->getGrupoEventoAtivos();
                    $texto = '';
                    foreach ($grupoEventoAtivos as $gea) {
                        if ($this->view->extra != $gea->getGrupo()->getId()) {
                            $texto .= $gea->getGrupo()->getEntidadeAtiva()->infoEntidade() . '<br />';
                        }
                    }
                    $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $evento->getNome() . '</span><span class="visible-sm visible-xs">' . $evento->getNomeAjustado() . '</span></td>';

                    $html .= '<td class="text-center">';

                    $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_NOVO_REVISIONISTA), Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickInserir));
                    $html .= '</td>';
                }
                if ($tipoAtivacaoFichas) {

                    $html .= '<td class="text-center">' . Funcoes::mudarPadraoData($evento->getData(), 1) . '</td>';

                    $stringNomeDaFuncaoOnClickInserir = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_ATIVAR_FICHA_REVISAO . '", ' . $evento->getId() . ')';
                    $grupoEventoAtivos = $evento->getGrupoEventoAtivos();
                    $texto = '';
                    foreach ($grupoEventoAtivos as $gea) {
                        if ($this->view->extra != $gea->getGrupo()->getId()) {
                            $texto .= $gea->getGrupo()->getEntidadeAtiva()->infoEntidade() . '<br />';
                        }
                    }
                    $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $evento->getNome() . '</span><span class="visible-sm visible-xs">' . $evento->getNomeAjustado() . '</span></td>';

                    $html .= '<td class="text-center">';

                    $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_SELECIONAR), Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickInserir));
                    $html .= '</td>';
                }
                if ($tipoListarRevisaoTurma) {

                    $html .= '<td class="text-center">' . Funcoes::mudarPadraoData($evento->getData(), 1) . '</td>';

                    $stringNomeDaFuncaoOnClickInserir = 'mostrarSplash(); funcaoCircuito("' . Constantes::$ROUTE_CURSO . 'SelecionarPessoasRevisao", ' . $evento->getId() . ')';
                    $grupoEventoAtivos = $evento->getGrupoEventoAtivos();
                    $texto = '';
                    foreach ($grupoEventoAtivos as $gea) {
                        if ($this->view->extra != $gea->getGrupo()->getId()) {
                            $texto .= $gea->getGrupo()->getEntidadeAtiva()->infoEntidade() . '<br />';
                        }
                    }
                    $html .= '<td class="text-center"><span class="visible-lg visible-md">' . $evento->getNome() . '</span><span class="visible-sm visible-xs">' . $evento->getNomeAjustado() . '</span></td>';

                    $html .= '<td class="text-center">';

                    $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_NOVO_REVISIONISTA), Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickInserir));
                    $html .= '</td>';
                }
                if ($tipoListarDiscipulados) {
                    $html .= '<td class="text-center">' . $this->view->translate($diaDaSemanaAjustado) . '/' . $evento->getHoraFormatoHoraMinutoParaListagem() . '</td>';
                    $stringNomeDaFuncaoOnClick = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_EVENTO_DISCIPULADO . '", ' . $evento->getId() . ')';
                    $stringNomeDaFuncaoOnClickExclusao = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_EVENTO_EXCLUSAO . '", ' . $evento->getId() . ')';
                    $grupoEventoAtivos = $evento->getGrupoEventoAtivos();
                    $texto = '';
                    foreach ($grupoEventoAtivos as $gea) {
                        if ($this->view->extra != $gea->getGrupo()->getId()) {
                            $texto .= $gea->getGrupo()->getEntidadeAtiva()->infoEntidade() . '<br />';
                        }
                    }
                    $html .= '<td class="text-center visible-lg visible-md visible-sm">' . $evento->getNome() . '</span></td>';
                    $html .= '<td class="text-center">' . $this->view->BotaoPopover(count($grupoEventoAtivos) - 1, $texto) . '</td>';
                    $html .= '<td class="text-center">';
                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PENCIL, Constantes::$STRING_HASHTAG, 3, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClick));
                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_TIMES, Constantes::$STRING_HASHTAG, 4, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickExclusao));
                    $html .= '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        } else {
            $stringTipoEvento = '';
            if ($tipoCelula) {
                $stringTipoEvento = 'Células';
            }
            if ($tipoCulto) {
                $stringTipoEvento = 'Cultos';
            }
            if ($tipoRevisao) {
                $stringTipoEvento = 'Revisões de Vidas';
            }
            $html .= '<div class="alert alert-warning"><i class="fa fa-warning pr10" aria-hidden="true"></i>&nbsp;Sem ' . $stringTipoEvento . '!</div>';
        }
        $html .= '</div>';
        /* Fim panel-body */
        if ($this->view->mostrarOpcoes) {
            $html .= '<div class="panel-footer text-right">';
            /* Botões */
            if ($tipoCelula) {
                if (count($this->getGrupoEventos()) < 2) {
                    $stringNomeDaFuncaoOnClickCadastro = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_EVENTO_CELULA . '", 0)';
                    $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PLUS . ' ' . $this->view->translate(Constantes::$TRADUCAO_NOVA_CELULA), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
                } else {
                    $html .= '<div class="alert alert-micro alert-warning">';
                    $html .= '<i class="fa fa-warning pr10" aria-hidden="true"></i>';
                    $html .= $this->view->translate(Constantes::$TRADUCAO_NUMERO_MAXIMO_CELULAS);
                    $html .= '</div>';
                }
            }
            if ($tipoCulto) {
                $stringNomeDaFuncaoOnClickCadastro = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_EVENTO_CULTO . '", 0)';
                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PLUS . ' ' . $this->view->translate(Constantes::$TRADUCAO_NOVO_CULTO), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
            }
            if ($tipoRevisao) {
                $stringNomeDaFuncaoOnClickCadastro = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_CADASTRO_REVISAO . '", 0)';
                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PLUS . ' ' . $this->view->translate(Constantes::$TRADUCAO_NOVO_REVISAO), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
            }
            if ($tipoListarRevisaoTurma) {
                $stringNomeDaFuncaoOnClickVoltar = 'mostrarSplash(); funcaoCircuito("' . Constantes::$ROUTE_CURSO . Constantes::$PAGINA_LISTAR_TURMA . '", 0)';
                $html .= $this->view->botaoLink($this->view->translate(Constantes::$TRADUCAO_VOLTAR), Constantes::$STRING_HASHTAG, 2, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickVoltar));
            }
            if ($tipoListarDiscipulados) {
                $stringNomeDaFuncaoOnClickCadastro = 'mostrarSplash(); funcaoCircuito("cadastro' . Constantes::$PAGINA_EVENTO_DISCIPULADO . '", 0)';
                $html .= $this->view->botaoLink(Constantes::$STRING_ICONE_PLUS . ' ' . $this->view->translate(Constantes::$TRADUCAO_NOVO_DISCIPULADO), Constantes::$STRING_HASHTAG, 0, $this->view->funcaoOnClick($stringNomeDaFuncaoOnClickCadastro));
            }

            /* Fim Botões */
            $html .= '</div>';
        }
        /* Fim panel-footer */
        $html .= $this->view->templateFormularioRodape();
        return $html;
    }

    function getGrupoEventos() {
        return $this->grupoEventos;
    }

    function setGrupoEventos($grupoEventos) {
        $this->grupoEventos = $grupoEventos;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

}
