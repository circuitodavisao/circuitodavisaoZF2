<?php

namespace Application\View\Helper;

use Application\Controller\Helper\Funcoes;
use Application\Controller\RelatorioController;
use Application\Model\Entity\Hierarquia;
use Application\Model\Helper\FuncoesEntidade;
use Zend\View\Helper\AbstractHelper;

/**
 * Nome: CircuitoMeAjuda.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para mostrar dados consoldiados
 */
class CircuitoMeAjuda extends AbstractHelper {

    public function __construct() {
        
    }

    public function __invoke() {
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<div class="row alert alert-default mt10">';
        $html .= '<div class="col-xs-12 col-md-12 col-lg-12">';
        $html .= '<div class="panel">';
        $html .= '<div class="panel-body pn">';

        if ($this->view->discipulos) {

            /* Celulas nao realizadas */
            $htmlCelulasNaoRealizadas = '';
            $totalDeCelulasNaoRealizadas = 0;
            foreach ($this->view->discipulos as $gpFilho) {
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $nomeLideres = $grupoFilho->getNomeLideresAtivos();
                $relatorioDiscipulo = $this->view->discipulosRelatorio[$grupoFilho->getId()];
                $relatorioDiscipuloPessoal = $this->view->discipulosRelatorioPessoal[$grupoFilho->getId()];
                $diferenca = $relatorioDiscipulo['celulaQuantidade'] - $relatorioDiscipulo['celulaRealizadas'];
                $diferencaPessoal = $relatorioDiscipuloPessoal['celulaQuantidade'] - $relatorioDiscipuloPessoal['celulaRealizadas'];
                $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden info">';
                $htmlCelulasNaoRealizadas .= '<td colspan="2">EQUIPE - ' . $nomeLideres . '</td>';
                $htmlCelulasNaoRealizadas .= '</tr>';
                if ($diferencaPessoal > 0) {
                    $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden">';
                    $htmlCelulasNaoRealizadas .= '<td>' . $nomeLideres . '</td>';
                    $htmlCelulasNaoRealizadas .= '<td>' . $diferencaPessoal . '</td>';
                    $htmlCelulasNaoRealizadas .= '</tr>';
                }
                $grupoPaiFilhoFilhos144 = $grupoFilho->getGrupoPaiFilhoFilhosAtivos(-1);
                if ($grupoPaiFilhoFilhos144) {
                    foreach ($grupoPaiFilhoFilhos144 as $gpFilho144) {
                        $grupoFilho144 = $gpFilho144->getGrupoPaiFilhoFilho();
                        $nomeLideres144 = $grupoFilho144->getNomeLideresAtivos();
                        $relatorioDiscipulo144 = $this->view->discipulosRelatorio[$grupoFilho144->getId()];
                        $numeroSub = $grupoFilho144->getEntidadeAtiva()->infoEntidade(true);
                        if ($relatorioDiscipulo144) {
                            $diferenca144 = $relatorioDiscipulo144['celulaQuantidade'] - $relatorioDiscipulo144['celulaRealizadas'];
                            $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden">';
                            $htmlCelulasNaoRealizadas .= '<td>' . $numeroSub . '-' . $nomeLideres144 . '</td>';
                            $htmlCelulasNaoRealizadas .= '<td>' . $diferenca144 . '</td>';
                            $htmlCelulasNaoRealizadas .= '</tr>';
                        }

                        $grupoPaiFilhoFilhos1728 = $grupoFilho144->getGrupoPaiFilhoFilhosAtivos(-1);
                        if ($grupoPaiFilhoFilhos1728) {
                            foreach ($grupoPaiFilhoFilhos1728 as $gpFilho1728) {
                                $grupoFilho1728 = $gpFilho1728->getGrupoPaiFilhoFilho();
                                $nomeLideres1728 = $grupoFilho1728->getNomeLideresAtivos();
                                $relatorioDiscipulo1728 = $this->view->discipulosRelatorio[$grupoFilho1728->getId()];
                                $numeroSub = $grupoFilho1728->getEntidadeAtiva()->infoEntidade(true);
                                if ($relatorioDiscipulo1728) {
                                    $diferenca1728 = $relatorioDiscipulo1728['celulaQuantidade'] - $relatorioDiscipulo1728['celulaRealizadas'];
                                    $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden">';
                                    $htmlCelulasNaoRealizadas .= '<td>' . $numeroSub . '-' . $nomeLideres1728 . '</td>';
                                    $htmlCelulasNaoRealizadas .= '<td>' . $diferenca1728 . '</td>';
                                    $htmlCelulasNaoRealizadas .= '</tr>';
                                }

                                $grupoPaiFilhoFilhos20736 = $grupoFilho1728->getGrupoPaiFilhoFilhosAtivos(-1);
                                if ($grupoPaiFilhoFilhos20736) {
                                    foreach ($grupoPaiFilhoFilhos20736 as $gpFilho20736) {
                                        $grupoFilho20736 = $gpFilho20736->getGrupoPaiFilhoFilho();
                                        $nomeLideres20736 = $grupoFilho20736->getNomeLideresAtivos();
                                        $relatorioDiscipulo20736 = $this->view->discipulosRelatorio[$grupoFilho20736->getId()];
                                        $numeroSub = $grupoFilho20736->getEntidadeAtiva()->infoEntidade(true);
                                        if ($relatorioDiscipulo20736) {
                                            $diferenca20736 = $relatorioDiscipulo20736['celulaQuantidade'] - $relatorioDiscipulo20736['celulaRealizadas'];
                                            $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden">';
                                            $htmlCelulasNaoRealizadas .= '<td>' . $numeroSub . '-' . $nomeLideres20736 . '</td>';
                                            $htmlCelulasNaoRealizadas .= '<td>' . $diferenca20736 . '</td>';
                                            $htmlCelulasNaoRealizadas .= '</tr>';
                                        }

                                        $grupoPaiFilhoFilhos248832 = $grupoFilho20736->getGrupoPaiFilhoFilhosAtivos($periodo);
                                        if ($grupoPaiFilhoFilhos248832) {
                                            foreach ($grupoPaiFilhoFilhos248832 as $gpFilho248832) {
                                                $grupoFilho248832 = $gpFilho248832->getGrupoPaiFilhoFilho();
                                                $nomeLideres248832 = $grupoFilho248832->getNomeLideresAtivos();
                                                $relatorioDiscipulo248832 = $this->view->discipulosRelatorio[$grupoFilho248832->getId()];
                                                $numeroSub = $grupoFilho248832->getEntidadeAtiva()->infoEntidade(true);
                                                if ($relatorioDiscipulo248832) {
                                                    $diferenca248832 = $relatorioDiscipulo248832['celulaQuantidade'] - $relatorioDiscipulo248832['celulaRealizadas'];
                                                    $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden">';
                                                    $htmlCelulasNaoRealizadas .= '<td>' . $numeroSub . '-' . $nomeLideres248832 . '</td>';
                                                    $htmlCelulasNaoRealizadas .= '<td>' . $diferenca248832 . '</td>';
                                                    $htmlCelulasNaoRealizadas .= '</tr>';
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden primary">';
                $htmlCelulasNaoRealizadas .= '<td class="text-right">TOTAL</td>';
                $htmlCelulasNaoRealizadas .= '<td>' . $diferenca . '</td>';
                $htmlCelulasNaoRealizadas .= '</tr>';
                $htmlCelulasNaoRealizadas .= '<tr class="linhaCelulasNaoRealizadas hidden">';
                $htmlCelulasNaoRealizadas .= '<td colspan="2"></td>';
                $htmlCelulasNaoRealizadas .= '</tr>';
                $totalDeCelulasNaoRealizadas += $diferenca;
            }

            $htmlCelulasDeElite = '';
            $totalDeCelulasDeElite = 0;
            $totalDeCelulasDeElitePorEquipe = 0;
            $relatorioCelula = $this->view->relatorioCelulas[$this->view->grupo->getId()];
            $mostrar = false;
            foreach ($relatorioCelula as $relatorio) {
                if ($relatorio['resposta'] == 1) {
                    $mostrar = true;
                    $totalDeCelulasDeElite++;
                    $totalDeCelulasDeElitePorEquipe++;
                }
            }
            if ($mostrar) {
                $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden info">';
                $htmlCelulasDeElite .= '<td colspan="2">C&Eacute;LULA PESSOAL</td>';
                $htmlCelulasDeElite .= '</tr>';
                foreach ($relatorioCelula as $relatorio) {
                    if ($relatorio['resposta'] == 1) {
                        $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden">';
                        $htmlCelulasDeElite .= '<td colspan="2">Hosp.: ' . $relatorio['hospedeiro'] . ' - Pessoas: ' . $relatorio['resultado'] . '</td>';
                        $htmlCelulasDeElite .= '</tr>';
                    }
                }
                $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden primary">';
                $htmlCelulasDeElite .= '<td class="text-right">TOTAL</td>';
                $htmlCelulasDeElite .= '<td>' . $totalDeCelulasDeElitePorEquipe . '</td>';
                $htmlCelulasDeElite .= '</tr>';
                $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden">';
                $htmlCelulasDeElite .= '<td colspan="2"></td>';
                $htmlCelulasDeElite .= '</tr>';
            }
            foreach ($this->view->discipulos as $gpFilho) {
                $totalDeCelulasDeElitePorEquipe = 0;
                $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
                $nomeLideres = $grupoFilho->getNomeLideresAtivos();
                $relatorioCelula = $this->view->relatorioCelulas[$grupoFilho->getId()];
                $mostrar = false;
                foreach ($relatorioCelula as $relatorio) {
                    if ($relatorio['resposta'] == 1) {
                        $mostrar = true;
                        $totalDeCelulasDeElite++;
                        $totalDeCelulasDeElitePorEquipe++;
                    }
                }

                $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden info">';
                $htmlCelulasDeElite .= '<td colspan="2">EQUIPE - ' . $nomeLideres . '</td>';
                $htmlCelulasDeElite .= '</tr>';
                if ($mostrar) {
                    foreach ($relatorioCelula as $relatorio) {
                        if ($relatorio['resposta'] == 1) {
                            $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden">';
                            $htmlCelulasDeElite .= '<td colspan="2">Hosp.: ' . $relatorio['hospedeiro'] . ' - Pessoas: ' . $relatorio['resultado'] . '</td>';
                            $htmlCelulasDeElite .= '</tr>';
                        }
                    }
                }
                $grupoPaiFilhoFilhos144 = $grupoFilho->getGrupoPaiFilhoFilhosAtivos(-1);
                if ($grupoPaiFilhoFilhos144) {
                    foreach ($grupoPaiFilhoFilhos144 as $gpFilho144) {
                        $grupoFilho144 = $gpFilho144->getGrupoPaiFilhoFilho();
                        $nomeLideres144 = $grupoFilho144->getNomeLideresAtivos();
                        $relatorioDiscipulo144 = $this->view->relatorioCelulas[$grupoFilho144->getId()];
                        $numeroSub = $grupoFilho144->getEntidadeAtiva()->infoEntidade(true);
                        if ($relatorioDiscipulo144) {
                            $mostrar = false;
                            foreach ($relatorioDiscipulo144 as $relatorio) {
                                if ($relatorio['resposta'] == 1) {
                                    $mostrar = true;
                                    $totalDeCelulasDeElite++;
                                    $totalDeCelulasDeElitePorEquipe++;
                                }
                            }
                            if ($mostrar) {
                                $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden system">';
                                $htmlCelulasDeElite .= '<td>' . $numeroSub . '-' . $nomeLideres144 . '</td>';
                                $htmlCelulasDeElite .= '<td></td>';
                                $htmlCelulasDeElite .= '</tr>';
                                foreach ($relatorioDiscipulo144 as $relatorio) {
                                    if ($relatorio['resposta'] == 1) {
                                        $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden">';
                                        $htmlCelulasDeElite .= '<td colspan="2">Hosp.: ' . $relatorio['hospedeiro'] . ' - Pessoas: ' . $relatorio['resultado'] . '</td>';
                                        $htmlCelulasDeElite .= '</tr>';
                                    }
                                }
                            }
                        }

                        $grupoPaiFilhoFilhos1728 = $grupoFilho144->getGrupoPaiFilhoFilhosAtivos(-1);
                        if ($grupoPaiFilhoFilhos1728) {
                            foreach ($grupoPaiFilhoFilhos1728 as $gpFilho1728) {
                                $grupoFilho1728 = $gpFilho1728->getGrupoPaiFilhoFilho();
                                $nomeLideres1728 = $grupoFilho1728->getNomeLideresAtivos();
                                $relatorioDiscipulo1728 = $this->view->relatorioCelulas[$grupoFilho1728->getId()];
                                $numeroSub = $grupoFilho1728->getEntidadeAtiva()->infoEntidade(true);
                                if ($relatorioDiscipulo1728) {
                                    $mostrar = false;
                                    foreach ($relatorioDiscipulo1728 as $relatorio) {
                                        if ($relatorio['resposta'] == 1) {
                                            $mostrar = true;
                                            $totalDeCelulasDeElite++;
                                            $totalDeCelulasDeElitePorEquipe++;
                                        }
                                    }
                                    if ($mostrar) {
                                        $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden system">';
                                        $htmlCelulasDeElite .= '<td>' . $numeroSub . '-' . $nomeLideres144 . '</td>';
                                        $htmlCelulasDeElite .= '<td></td>';
                                        $htmlCelulasDeElite .= '</tr>';
                                        foreach ($relatorioDiscipulo1728 as $relatorio) {
                                            if ($relatorio['resposta'] == 1) {
                                                $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden">';
                                                $htmlCelulasDeElite .= '<td colspan="2">Hosp.: ' . $relatorio['hospedeiro'] . ' - Pessoas: ' . $relatorio['resultado'] . '</td>';
                                                $htmlCelulasDeElite .= '</tr>';
                                            }
                                        }
                                    }
                                }

                                $grupoPaiFilhoFilhos20736 = $grupoFilho1728->getGrupoPaiFilhoFilhosAtivos(-1);
                                if ($grupoPaiFilhoFilhos20736) {
                                    foreach ($grupoPaiFilhoFilhos20736 as $gpFilho20736) {
                                        $grupoFilho20736 = $gpFilho20736->getGrupoPaiFilhoFilho();
                                        $nomeLideres20736 = $grupoFilho20736->getNomeLideresAtivos();
                                        $relatorioDiscipulo20736 = $this->view->relatorioCelulas[$grupoFilho20736->getId()];
                                        $numeroSub = $grupoFilho20736->getEntidadeAtiva()->infoEntidade(true);
                                        if ($relatorioDiscipulo20736) {
                                            $mostrar = false;
                                            foreach ($relatorioDiscipulo20736 as $relatorio) {
                                                if ($relatorio['resposta'] == 1) {
                                                    $mostrar = true;
                                                    $totalDeCelulasDeElite++;
                                                    $totalDeCelulasDeElitePorEquipe++;
                                                }
                                            }
                                            if ($mostrar) {
                                                $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden system">';
                                                $htmlCelulasDeElite .= '<td>' . $numeroSub . '-' . $nomeLideres144 . '</td>';
                                                $htmlCelulasDeElite .= '<td></td>';
                                                $htmlCelulasDeElite .= '</tr>';
                                                foreach ($relatorioDiscipulo20736 as $relatorio) {
                                                    if ($relatorio['resposta'] == 1) {
                                                        $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden">';
                                                        $htmlCelulasDeElite .= '<td colspan="2">Hosp.: ' . $relatorio['hospedeiro'] . ' - Pessoas: ' . $relatorio['resultado'] . '</td>';
                                                        $htmlCelulasDeElite .= '</tr>';
                                                    }
                                                }
                                            }
                                        }

                                        $grupoPaiFilhoFilhos248832 = $grupoFilho20736->getGrupoPaiFilhoFilhosAtivos($periodo);
                                        if ($grupoPaiFilhoFilhos248832) {
                                            foreach ($grupoPaiFilhoFilhos248832 as $gpFilho248832) {
                                                $grupoFilho248832 = $gpFilho248832->getGrupoPaiFilhoFilho();
                                                $nomeLideres248832 = $grupoFilho248832->getNomeLideresAtivos();
                                                $relatorioDiscipulo248832 = $this->view->relatorioCelulas[$grupoFilho248832->getId()];
                                                $numeroSub = $grupoFilho248832->getEntidadeAtiva()->infoEntidade(true);
                                                if ($relatorioDiscipulo248832) {
                                                    $mostrar = false;
                                                    foreach ($relatorioDiscipulo248832 as $relatorio) {
                                                        if ($relatorio['resposta'] == 1) {
                                                            $mostrar = true;
                                                            $totalDeCelulasDeElite++;
                                                            $totalDeCelulasDeElitePorEquipe++;
                                                        }
                                                    }
                                                    if ($mostrar) {
                                                        $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden system">';
                                                        $htmlCelulasDeElite .= '<td>' . $numeroSub . '-' . $nomeLideres144 . '</td>';
                                                        $htmlCelulasDeElite .= '<td></td>';
                                                        $htmlCelulasDeElite .= '</tr>';
                                                        foreach ($relatorioDiscipulo248832 as $relatorio) {
                                                            if ($relatorio['resposta'] == 1) {
                                                                $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden">';
                                                                $htmlCelulasDeElite .= '<td colspan="2">Hosp.: ' . $relatorio['hospedeiro'] . ' - Pessoas: ' . $relatorio['resultado'] . '</td>';
                                                                $htmlCelulasDeElite .= '</tr>';
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden primary">';
                $htmlCelulasDeElite .= '<td class="text-right">TOTAL</td>';
                $htmlCelulasDeElite .= '<td>' . $totalDeCelulasDeElitePorEquipe . '</td>';
                $htmlCelulasDeElite .= '</tr>';
                $htmlCelulasDeElite .= '<tr class="linhaCelulasDeElite hidden">';
                $htmlCelulasDeElite .= '<td colspan="2"></td>';
                $htmlCelulasDeElite .= '</tr>';
            }

            $html .= '<table class="table table-condensed">';
            $html .= '<thead>';
            $html .= '<tr class="info">';
            $html .= '<th colspan="2" class="text-center">Circuito me Ajuda ' . Funcoes::montaPeriodo(-1)[0] . '</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $html .= '<tr>';
            $html .= '<td class="text-center">C&eacute;lulas <b>N&atilde;o</b> Realizadas: ' . $totalDeCelulasNaoRealizadas . '</td>';
            $funcao = $this->view->funcaoOnClick('$(".linhaCelulasNaoRealizadas").toggleClass("hidden")');
            $html .= '<td>' . $this->view->botaoSimples('<i class="fa fa-eye" />', $funcao, BotaoSimples::botaoMuitoPequenoImportante, BotaoSimples::posicaoAoCentro) . '</td>';
            $html .= '</tr>';
            $html .= $htmlCelulasNaoRealizadas;

            $html .= '<tr>';
            $html .= '<td class="text-center">C&eacute;lulas de <b>Elite</b>: ' . $totalDeCelulasDeElite . '</td>';
            $funcaoCelulasDeElite = $this->view->funcaoOnClick('$(".linhaCelulasDeElite").toggleClass("hidden")');
            $html .= '<td>' . $this->view->botaoSimples('<i class="fa fa-eye" />', $funcaoCelulasDeElite, BotaoSimples::botaoMuitoPequenoImportante, BotaoSimples::posicaoAoCentro) . '</td>';
            $html .= '</tr>';
            $html .= $htmlCelulasDeElite;

            $html .= '</tbody>';
            $html .= '</table>';
        }

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    static public function functionName($param) {
        
    }

}
