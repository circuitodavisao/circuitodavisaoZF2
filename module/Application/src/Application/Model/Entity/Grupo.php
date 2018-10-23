<?php

namespace Application\Model\Entity;

/**
 * Nome: Grupo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo
 */

use Application\Controller\Helper\Funcoes;
use Application\Model\Helper\FuncoesEntidade;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class Grupo extends CircuitoEntity {

    protected $ciclo;
    protected $eventos;

    /**
     * @ORM\OneToOne(targetEntity="FatoRanking", mappedBy="grupo", fetch="EXTRA_LAZY")
     */
    private $fatoRanking;

    /**
     * @ORM\OneToMany(targetEntity="FatoDiscipulado", mappedBy="grupo", fetch="EXTRA_LAZY")
     */
//    private $fatoDiscipulado;

    /**
     * @ORM\OneToOne(targetEntity="GrupoCv", mappedBy="grupo", fetch="EXTRA_LAZY")
     */
    private $grupoCv;

    /**
     * @ORM\OneToMany(targetEntity="Entidade", mappedBy="grupo", fetch="EXTRA_LAZY")
     */
    protected $entidade;

    /**
     * @ORM\OneToMany(targetEntity="GrupoResponsavel", mappedBy="grupo", fetch="EXTRA_LAZY")
     */
    protected $grupoResponsavel;

    /**
     * @ORM\OneToMany(targetEntity="GrupoEvento", mappedBy="grupo", fetch="EXTRA_LAZY")
     */
    protected $grupoEvento;

    /**
     * @ORM\OneToMany(targetEntity="GrupoPessoa", mappedBy="grupo", fetch="EXTRA_LAZY")
     */
    protected $grupoPessoa;

    /**
     * @ORM\OneToMany(targetEntity="GrupoAtendimento", mappedBy="grupo", fetch="EXTRA_LAZY")
     */
    protected $grupoAtendimento;

    /**
     * @ORM\OneToMany(targetEntity="GrupoAtendimentoComentario", mappedBy="grupo", fetch="EXTRA_LAZY")
     */
    protected $grupoAtendimentoComentario;

    /**
     * @ORM\OneToMany(targetEntity="GrupoPaiFilho", mappedBy="grupoPaiFilhoPai", fetch="EXTRA_LAZY")
     */
    protected $grupoPaiFilhoFilhos;

    /**
     * @ORM\OneToMany(targetEntity="GrupoPaiFilho", mappedBy="grupoPaiFilhoFilho", fetch="EXTRA_LAZY")
     */
    protected $grupoPaiFilhoPai;

    /**
     * @ORM\OneToMany(targetEntity="Turma", mappedBy="grupo", fetch="EXTRA_LAZY")
     */
    protected $turma;

     /**
     * @ORM\OneToMany(targetEntity="PessoaCursoAcesso", mappedBy="grupo", fetch="EXTRA_LAZY")
     */
    protected $pessoaCursoAcesso;

    /**
     * @ORM\OneToMany(targetEntity="Solicitacao", mappedBy="grupo", fetch="EXTRA_LAZY")
     */
    protected $solicitacao;

    public function __construct() {
        $this->entidade = new ArrayCollection();
        $this->grupoResponsavel = new ArrayCollection();
        $this->grupoEvento = new ArrayCollection();
        $this->grupoPessoa = new ArrayCollection();
        $this->grupoAtendimento = new ArrayCollection();
        $this->grupoAtendimentoComentario = new ArrayCollection();
        $this->grupoPaiFilhoFilhos = new ArrayCollection();
        $this->grupoPaiFilhoPai = new ArrayCollection();
        $this->turma = new ArrayCollection();
        $this->pessoaCursoAcesso = new ArrayCollection();
		$this->solicitacao = new ArrayCollection();
//        $this->fatoDiscipulado = new ArrayCollection();
    }

    /**
     * Recupera todas as entidades vinculadas aquele grupo
     * @return Entidade
     */
    function getEntidade() {
        return $this->entidade;
    }

    /**
     * Retorna a entidade ativa
     * @return Entidade
     */
    function getEntidadeAtiva() {
        $entidadeAtiva = null;
        foreach ($this->getEntidade() as $entidade) {
            if ($entidade->verificarSeEstaAtivo()) {
                $entidadeAtiva = $entidade;
                break;
            }
        }
        if ($entidadeAtiva === null) {
			$ultimaEntidade = null;
            foreach ($this->getEntidade() as $entidade) {
                if (!$entidade->verificarSeEstaAtivo()) {
					if($ultimaEntidade === null){
						$ultimaEntidade = $entidade;
					}
					if($entidade->getId() > $ultimaEntidade->getId()){
						$ultimaEntidade = $entidade;
					}
                }
            }
			if($ultimaEntidade){
				$entidadeAtiva = $ultimaEntidade;
			}
        }
        return $entidadeAtiva;
    }

    function getEntidadeInativaPorDataInativacao($dataInativacao = null) {
        $entidadeInativa = null;

        foreach ($this->getEntidade() as $entidade) {
            if ($entidade->getData_inativacaoStringPadraoBanco() == $dataInativacao) {
                $entidadeInativa = $entidade;
                break;
            }

            if (!$entidade->verificarSeEstaAtivo()) {
                $entidadeInativa = $entidade;
                break;
            }
        }

        return $entidadeInativa;
    }

    /**
     * Retorna o grupo responsavel do grupo
     * @return GrupoResponsavel
     */
    function getGrupoResponsavel() {
        return $this->grupoResponsavel;
    }

    /**
     * Retorna o grupo responsavel ativo
     * @return GrupoResponsavel
     */
    function getGrupoResponsavelAtivo() {
        $grupoResponsavel = null;
        foreach ($this->getGrupoResponsavel() as $gr) {
            if ($gr->verificarSeEstaAtivo()) {
                $grupoResponsavel = $gr;
                break;
            }
        }
        return $grupoResponsavel;
    }

    /**
     * Recupera as pessoas das responsabilidades ativas
     * @return Pessoa[]
     */
    function getResponsabilidadesAtivas($inativos = false) {
        $responsabilidadesAtivas = array();
        /* Responsabilidades */
        $responsabilidadesTodosStatus = $this->getGrupoResponsavel();
        if ($responsabilidadesTodosStatus) {
            /* Verificar responsabilidades ativas */
            foreach ($responsabilidadesTodosStatus as $responsabilidadeTodosStatus) {
                if ($inativos) {
                    $responsabilidadesAtivas[] = $responsabilidadeTodosStatus;
                } else {
                    if ($responsabilidadeTodosStatus->verificarSeEstaAtivo()) {
                        $responsabilidadesAtivas[] = $responsabilidadeTodosStatus;
                    }
                }
            }
        }
        return $responsabilidadesAtivas;
    }

    function verificaSeECasal() {
        $resposta = false;
        if (count($this->getResponsabilidadesAtivas()) == 2) {
            $resposta = true;
        }
        return $resposta;
    }

    /**
     * Recupera o total de grupo atendimentos ativos no mes e ano
     * @return integer
     */
    function totalDeAtendimentos($mes, $ano) {
        $total = 0;
        $grupoAtendimentos = $this->getGrupoAtendimento();
        foreach ($grupoAtendimentos as $grupoAtendimento) {
            if ($grupoAtendimento->verificaSeTemNesseMesEAno($mes, $ano)) {
                $total++;
            }
        }
        return $total;
    }

    /**
     * Recupera o total de grupo atendimentos ativos no mes e ano
     * @return integer
     */
    public static function relatorioDeAtendimentosAbaixo($discipulos, $mes, $ano) {
        $relatorio = array();
        $totalGruposFilhosAtivos = 0;
        $totalGruposAtendidos = 0;
        foreach ($discipulos as $gpFilho) {
            $totalGruposAtendido = 0;
            $grupoFilho = $gpFilho->getGrupoPaiFilhoFilho();
            if ($grupoFilho->getResponsabilidadesAtivas()) {
                foreach ($grupoFilho->getGrupoAtendimento() as $grupoAtendimento) {
                    if ($grupoAtendimento->verificaSeTemNesseMesEAno(
                                    $mes, $ano)) {
                        $totalGruposAtendido++;
                    }
                }
                if ($totalGruposAtendido >= 1) {
                    $totalGruposAtendidos++;
                }

                $totalGruposFilhosAtivos++;
            }
        }

        if ($totalGruposFilhosAtivos) {
            $progresso = ($totalGruposAtendidos / $totalGruposFilhosAtivos) * 100;
        } else {
            $progresso = 0;
        }
        $relatorio[0] = $progresso;
        $relatorio[1] = $totalGruposAtendidos;
        $relatorio[2] = $totalGruposFilhosAtivos;
        return $relatorio;
    }

    /**
     * Recupera os filhos ativos por periodo
     * @return Pessoa[]
     */
    function getGrupoPaiFilhoFilhosAtivos($periodo = -1) {
        $grupoPaiFilhoFilhosAtivos = null;
        /* Responsabilidades */
        $grupoPaiFilhoFilhos = $this->getGrupoPaiFilhoFilhos();
        if ($grupoPaiFilhoFilhos) {
            $arrayPeriodo = Funcoes::montaPeriodo($periodo);
            $stringComecoDoPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
            $stringFimDoPeriodo = $arrayPeriodo[6] . '-' . $arrayPeriodo[5] . '-' . $arrayPeriodo[4];
            /* Verificar responsabilidades ativas */
            foreach ($grupoPaiFilhoFilhos as $gpf) {
                if ($gpf->verificarSeEstaAtivo()) {
                    $dataDoInicioDoPeriodoParaComparar = strtotime($stringFimDoPeriodo);
                    $dataDoGrupoPaiFilhoCriacaoParaComparar = strtotime($gpf->getData_criacaoStringPadraoBanco());
                    if ($dataDoGrupoPaiFilhoCriacaoParaComparar <= $dataDoInicioDoPeriodoParaComparar) {
                        $grupoPaiFilhoFilhosAtivos[] = $gpf;
                    }
                } else {
                    /* Inativo */
                    $dataDoInicioDoPeriodoParaComparar = strtotime($stringComecoDoPeriodo);
                    $dataDoGrupoGrupoPaiFilhoInativadoParaComparar = strtotime($gpf->getData_inativacaoStringPadraoBanco());
                    if ($dataDoGrupoGrupoPaiFilhoInativadoParaComparar >= $dataDoInicioDoPeriodoParaComparar) {
                        $grupoPaiFilhoFilhosAtivos[] = $gpf;
                    }
                }
            }
        }
        return $grupoPaiFilhoFilhosAtivos;
    }

    /**
     * Metódo que retorna os filhos no periodo do mês.
     * @method getGrupoPaiFilhoFilhosPorMes
     * @param  int $mes
     * @return Grupo grupos no período do mês inputado.
     */
    function getGrupoPaiFilhoFilhosPorMes($mes) {
        $grupoPaiFilhoFilhosAtivos = null;
        /* Responsabilidades */
        $grupoPaiFilhoFilhos = $this->getGrupoPaiFilhoFilhos();
        if ($grupoPaiFilhoFilhos) {
            /* Verificar responsabilidades ativas */
            foreach ($grupoPaiFilhoFilhos as $gpf) {
                $arrayPeriodo = Funcoes::montaPeriodo($periodo);
                if ($gpf->verificarSeEstaAtivo()) {
                    $stringFimDoPeriodo = date('Y') . '-' . $mes . '-' . cal_days_in_month(CAL_GREGORIAN, $mes, date('Y'));
                    $dataDoFimDoPeriodoParaComparar = strtotime($stringFimDoPeriodo);
                    $dataDoGrupoPaiFilhoCriacaoParaComparar = strtotime($gpf->getData_criacaoStringPadraoBanco());
                    if ($dataDoGrupoPaiFilhoCriacaoParaComparar <= $dataDoFimDoPeriodoParaComparar) {
                        $grupoPaiFilhoFilhosAtivos[] = $gpf;
                    }
                } else {
                    /* Inativo */
                    $stringFimDoPeriodo = date('Y') . '-' . $mes . '-' . cal_days_in_month(CAL_GREGORIAN, $mes, date('Y'));
                    $dataDoFimDoPeriodoParaComparar = strtotime($stringFimDoPeriodo);
                    $dataDoGrupoGrupoPaiFilhoInativadoParaComparar = strtotime($gpf->getData_inativacaoStringPadraoBanco());
                    if ($dataDoGrupoGrupoPaiFilhoInativadoParaComparar <= $dataDoFimDoPeriodoParaComparar) {
                        $grupoPaiFilhoFilhosAtivos[] = $gpf;
                    }
                }
            }
        }
        return $grupoPaiFilhoFilhosAtivos;
    }

    function getGrupoPaiFilhoFilhosAtivosReal() {
        $grupoPaiFilhoFilhosAtivos = null;
        /* Responsabilidades */
        $grupoPaiFilhoFilhos = $this->getGrupoPaiFilhoFilhos();
        if ($grupoPaiFilhoFilhos) {
            /* Verificar responsabilidades ativas */
            foreach ($grupoPaiFilhoFilhos as $gpf) {
                if ($gpf->verificarSeEstaAtivo()) {
                    $grupoPaiFilhoFilhosAtivos[] = $gpf;
                }
            }
        }
        return $grupoPaiFilhoFilhosAtivos;
    }

    /**
     * Recupera os filhos ativos
     * @return Pessoa[]
     */
    function getGrupoPaiFilhoPaiAtivo() {
        $grupoPaiFilhoPaiAtivo = null;
        /* Responsabilidades */
        $grupoPaiFilhoPais = $this->getGrupoPaiFilhoPai();
        if (count($grupoPaiFilhoPais) > 0) {
            /* Verificar responsabilidades ativas */
            foreach ($grupoPaiFilhoPais as $gpp) {
                if ($gpp->verificarSeEstaAtivo()) {
                    $grupoPaiFilhoPaiAtivo = $gpp;
                    break;
                }
            }
        }
        if (!$grupoPaiFilhoPaiAtivo) {
            foreach ($grupoPaiFilhoPais as $gpp) {
                if (!$gpp->verificarSeEstaAtivo()) {
                    $grupoPaiFilhoPaiAtivo = $gpp;
                    break;
                }
            }
        }
        return $grupoPaiFilhoPaiAtivo;
    }

    /**
     * Recupera os filhos ativos
     * @return Pessoa[]
     */
    function getGrupoPaiFilhoPaiInativo() {
        $grupoPaiFilhoPaiInativo = null;
        /* Responsabilidades */
        $grupoPaiFilhoPais = $this->getGrupoPaiFilhoPai();
        if (count($grupoPaiFilhoPais) > 0) {
            /* Verificar responsabilidades ativas */
            foreach ($grupoPaiFilhoPais as $gpp) {
                if (!$gpp->verificarSeEstaAtivo()) {
                    $grupoPaiFilhoPaiInativo = $gpp;
                    break;
                }
            }
        }
        return $grupoPaiFilhoPaiInativo;
    }

    function getGrupoPaiFilhoPaiPorDataInativacao($dataInativacao) {
        $grupoPaiFilhoPaiInativada = null;
        if ($dataInativacao) {
            /* Responsabilidades */
            $grupoPaiFilhoPais = $this->getGrupoPaiFilhoPai();
            if ($grupoPaiFilhoPais) {
                /* Verificar responsabilidades ativas */
                foreach ($grupoPaiFilhoPais as $gpp) {
                    if ($gpp->getData_inativacaoStringPadraoBanco() === $dataInativacao) {
                        $grupoPaiFilhoPaiInativada = $gpp;
                        break;
                    }
                }
            }
        }
        return $grupoPaiFilhoPaiInativada;
    }

    function getPessoasAtivas() {
        $pessoas = null;
        $grupoResponsavel = $this->getResponsabilidadesAtivas();
        if ($grupoResponsavel) {
            $pessoas = array();
            foreach ($grupoResponsavel as $gr) {
                $p = $gr->getPessoa();
                $pessoas[] = $p;
            }
        }
        return $pessoas;
    }

    function getPessoasInativas() {
        $pessoas = null;
        $comInativos = true;
        $grupoResponsavel = $this->getResponsabilidadesAtivas($comInativos);
        if ($grupoResponsavel) {
            $pessoas = array();
            foreach ($grupoResponsavel as $gr) {
                $p = $gr->getPessoa();
                $pessoas[] = $p;
            }
        }
        return $pessoas;
    }

    function getNomeLideresAtivos() {
        $pessoas = $this->getPessoasAtivas();
        $nomes = '';
        $contador = 1;
        $inativa = false;

        if (!$pessoas || !$this->getGrupoPaiFilhoPaiAtivo()) {
            $inativa = true;
            $pessoas = $this->getPessoasInativas();
            $dataInativacao = $pessoas[0]->getGrupoResponsavel()[0]->getData_inativacaoStringPadraoBrasil();
        }
        foreach ($pessoas as $pessoa) {
            if ($contador === 2) {
                $nomes .= ' e ';
            }
            if (count($pessoas) == 2) {
                $nomes .= $pessoa->getNomePrimeiro();
            } else {
                $nomes .= $pessoa->getNomePrimeiroUltimo();
            }
            $contador++;
        }
        if ($inativa) {
            $nomes = $nomes . ' <span class="hidden-xs">(INATIVO - ' . $dataInativacao . ')</span>';
        }
        return $nomes;
    }

    function getLinksWhatsapp() {
        $pessoas = $this->getPessoasAtivas();
		$links = '';
        foreach ($pessoas as $pessoa) {
			if($pessoa->getTelefone()){
				$links .= ' <a  class="btn btn-success btn-xs" href="https://api.whatsapp.com/send?phone=55'.$pessoa->getTelefone().'"><i class="fa fa-whatsapp"></i></a>';
			}
        }
        return $links;
    }



    function getFotosLideresAtivos($tamanho = 24) {
        $pessoas = $this->getPessoasAtivas();
        $fotos = '';
        foreach ($pessoas as $pessoa) {
            $fotos .= FuncoesEntidade::tagImgComFotoDaPessoa($pessoa, $tamanho, 'px', ' style="padding:1px;" ');
        }
        return $fotos;
    }

    function getNomeLideresInativos() {
        $pessoas = $this->getPessoasInativas();
        $nomes = '';
        $contador = 1;

        foreach ($pessoas as $pessoa) {
            if ($contador === 2) {
                $nomes .= ' & ';
            }
            if (count($pessoas) == 2) {
                $nomes .= $pessoa->getNomePrimeiro();
            } else {
                $nomes .= $pessoa->getNomePrimeiroUltimo();
            }
            $contador++;
        }
        return $nomes;
    }

    function setEntidade($entidade) {
        $this->entidade = $entidade;
    }

    function setGrupoResponsavel($grupoResponsavel) {
        $this->grupoResponsavel = $grupoResponsavel;
    }

    /**
     * Retorna o grupo evento
     * @return GrupoEvento
     */
    function getGrupoEvento() {
        return $this->grupoEvento;
    }

    /**
     * Retorna o grupo evento ordenados por dia da semana
     * @return GrupoEvento
     */
    function getGrupoEventoOrdenadosPorDiaDaSemana() {
        $grupoSelecionado = $this;
        $grupoEventosCelulasTodas = null;
        $grupoEventos = null;
        $grupoEventosCelulas = null;
        if ($grupoSelecionado->getEntidadeAtiva()) {
            if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
                $grupoEventosCelulasTodas = $grupoSelecionado->getGrupoEventoPorTipoEAtivo(EventoTipo::tipoCelula);
                $contadorDeAlteracoes = array();
                if ($grupoEventosCelulasTodas) {
                    foreach ($grupoEventosCelulasTodas as $grupoEvento) {
                        if ($contadorDeAlteracoes[$grupoEvento->getData_criacaoStringPadraoBanco()]) {
                            if ($grupoEvento->getId() > $contadorDeAlteracoes[$grupoEvento->getData_criacaoStringPadraoBanco()]->getId()) {
                                $contadorDeAlteracoes[$grupoEvento->getData_criacaoStringPadraoBanco() . '-' . $grupoEvento->getHora_criacao()] = $grupoEvento;
                            }
                        } else {
                            $contadorDeAlteracoes[$grupoEvento->getData_criacaoStringPadraoBanco()] = $grupoEvento;
                        }
                    }
                    foreach ($contadorDeAlteracoes as $grupoEventoCelula) {
                        $grupoEventosCelulas[] = $grupoEventoCelula;
                    }
                }
                while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
                    $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
                    if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                        break;
                    }
                }
                $grupoEventos = $grupoSelecionado->getGrupoEventoPorTipoEAtivo(EventoTipo::tipoCulto);
            } else {
                /* Lider de equipe ou igreja */
                $grupoEventos = array();
                $todosGruposEventos = $grupoSelecionado->getGrupoEventoAtivos();
                foreach ($todosGruposEventos as $grupoEvento) {
                    if ($grupoEvento->getEvento()->getEventoTipo()->getId() !== EventoTipo::tipoRevisao &&
                            $grupoEvento->getEvento()->getEventoTipo()->getId() !== EventoTipo::tipoDiscipulado) {
                        $grupoEventos[] = $grupoEvento;
                    }
                }
            }
        }

        if ($grupoEventosCelulas) {
            foreach ($grupoEventosCelulas as $eventoCelula) {
                $grupoEventos[] = $eventoCelula;
            }
        }
        for ($i = 0; $i < count($grupoEventos); $i++) {
            for ($j = 0; $j < count($grupoEventos); $j++) {
                $evento1 = $grupoEventos[$i];
                $evento2 = $grupoEventos[$j];
                $trocar = 0;

                if ($evento1->getEvento()->getDiaAjustado() <= $evento2->getEvento()->getDiaAjustado()) {
                    if ($evento1->getEvento()->getDiaAjustado() == $evento2->getEvento()->getDiaAjustado()) {
                        if ($evento1->getEvento()->getHora() < $evento2->getEvento()->getHora()) {
                            $trocar = 1;
                        }
                    } else {
                        $trocar = 1;
                    }
                    if ($trocar === 1) {
                        $grupoEventos[$i] = $evento2;
                        $grupoEventos[$j] = $evento1;
                    }
                }
            }
        }
        return $grupoEventos;
    }

    /**
     * Retorna o grupo evento Revisao
     * @return GrupoEvento
     */
    function getGrupoEventoRevisao() {
        $grupoSelecionado = $this;
        $grupoEventos = null;
        if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
            while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE ||
            $grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {

                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
                    break;
                }
            }
            $grupoEventos = $grupoSelecionado->getGrupoEventoAtivosPorTipo(EventoTipo::tipoRevisao);
        } else if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
            while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
                    break;
                }
            }
            $grupoEventos = $grupoSelecionado->getGrupoEventoAtivosPorTipo(EventoTipo::tipoRevisao);
        } else {
            $grupoEventos = $grupoSelecionado->getGrupoEventoAtivosPorTipo(EventoTipo::tipoRevisao);
        }

        return $grupoEventos;
    }

    /**
     * Retorna o grupo evento
     * @return GrupoEvento
     */
    function getGrupoEventoAtivos() {
        $grupoEventos = null;
        foreach ($this->getGrupoEvento() as $ge) {
            if ($ge->verificarSeEstaAtivo()) {
                $grupoEventos[] = $ge;
            }
        }
        return $grupoEventos;
    }

    /**
     * Retorna o grupo evento
     * @return GrupoEvento
     */
    function getGrupoEventoAtivosPorTipo($tipo = 0) {
        $grupoEventos = null;
        foreach ($this->getGrupoEvento() as $grupoEvento) {
            if ($grupoEvento->verificarSeEstaAtivo()) {
                if ($tipo === 0) {
                    $grupoEventos[] = $grupoEvento;
                }
                if ($tipo === EventoTipo::tipoCulto && $grupoEvento->getEvento()->verificaSeECulto()) {
                    $grupoEventos[] = $grupoEvento;
                }
                if ($tipo === EventoTipo::tipoCelula && $grupoEvento->getEvento()->verificaSeECelula()) {
                    $grupoEventos[] = $grupoEvento;
                }
                if ($tipo === EventoTipo::tipoRevisao && $grupoEvento->getEvento()->verificaSeERevisao()) {
                    $grupoEventos[] = $grupoEvento;
                }
                if ($tipo === EventoTipo::tipoDiscipulado && $grupoEvento->getEvento()->verificaSeEDiscipulado()) {
                    $grupoEventos[] = $grupoEvento;
                }
            }
        }
        return $grupoEventos;
    }

    /**
     * Retorna o grupo evento
     * @return GrupoEvento
     */
    function getGrupoEventoPorTipoEAtivo($tipo = 0, $ativo = 0) {
        $grupoEventos = null;
        foreach ($this->getGrupoEvento() as $grupoEvento) {
            $condicaoTipo = false;
            $condicaoAtivo = false;

            if ($tipo === 0) {
                $condicaoTipo = true;
            }
            if ($tipo === EventoTipo::tipoCulto && $grupoEvento->getEvento()->verificaSeECulto()) {
                $condicaoTipo = true;
            }
            if ($tipo === EventoTipo::tipoCelula && $grupoEvento->getEvento()->verificaSeECelula()) {
                $condicaoTipo = true;
            }
            if ($tipo === EventoTipo::tipoRevisao && $grupoEvento->getEvento()->verificaSeERevisao()) {
                $condicaoTipo = true;
            }
            if ($tipo === EventoTipo::tipoDiscipulado && $grupoEvento->getEvento()->verificaSeEDiscipulado()) {
                $condicaoTipo = true;
            }

            if ($ativo === 0) {
                $condicaoAtivo = true;
            }
            if ($ativo === 1 && $grupoEvento->verificarSeEstaAtivo()) {
                $condicaoAtivo = true;
            }
            if ($ativo === 2 && !$grupoEvento->verificarSeEstaAtivo()) {
                $condicaoAtivo = true;
            }

            if ($condicaoTipo && $condicaoAtivo) {
                $grupoEventos[] = $grupoEvento;
            }
        }
        return $grupoEventos;
    }

    /**
     * Verifica se o grupo participa do evento informado
     * @param int $idEvento
     * @return boolean
     */
    function verificaSeParticipaDoEvento($idEvento) {
        $resposta = false;
        $id = (int) $idEvento;

        if ($this->getGrupoEventoAtivos()) {
            foreach ($this->getGrupoEventoAtivos() as $ge) {
                if ($ge->getEvento_id() == $id) {
                    $resposta = true;
                }
            }
        }
        return $resposta;
    }

    function getGrupoEventoNoPeriodo($periodo, $apenasCelulas = false) {
        $grupoEventosNoPeriodo = array();
        $grupoEventoOrdenadosPorDiaDaSemana = $this->getGrupoEventoOrdenadosPorDiaDaSemana();
        $grupoEventos = $grupoEventoOrdenadosPorDiaDaSemana;
        if ($apenasCelulas) {
            unset($grupoEventos);
            if (!empty($grupoEventoOrdenadosPorDiaDaSemana)) {
                foreach ($grupoEventoOrdenadosPorDiaDaSemana as $grupoEventoTodos) {
                    if ($grupoEventoTodos->getEvento()->verificaSeECelula()) {
                        $grupoEventos[] = $grupoEventoTodos;
                    }
                }
            }
        }

        if (!empty($grupoEventos)) {
            foreach ($grupoEventos as $grupoEvento) {
                $arrayPeriodo = Funcoes::montaPeriodo($periodo);
                $stringComecoDoPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
                $dataDoInicioDoPeriodoParaComparar = strtotime($stringComecoDoPeriodo);
                $dataDoGrupoEventoParaComparar = strtotime($grupoEvento->getData_criacaoStringPadraoBanco());

                $validacaoDataDeCriacaoAntesDoInicioDoPeriodo = false;
                $validacaoDataDeCriacaoNoMeioDoPeriodo = false;

                if ($grupoEvento->verificarSeEstaAtivo()) {
                    /* Evento criado antes do inicio do periodo */
                    if ($dataDoGrupoEventoParaComparar <= $dataDoInicioDoPeriodoParaComparar) {
                        $validacaoDataDeCriacaoAntesDoInicioDoPeriodo = true;
                    }

                    /* Evento criado no meio do periodo */
                    $stringFimDoPeriodo = $arrayPeriodo[6] . '-' . $arrayPeriodo[5] . '-' . $arrayPeriodo[4];
                    $dataDoFimDoPeriodoParaComparar = strtotime($stringFimDoPeriodo);

                    if ($dataDoGrupoEventoParaComparar > $dataDoInicioDoPeriodoParaComparar && $dataDoGrupoEventoParaComparar <= $dataDoFimDoPeriodoParaComparar) {
                        $validacaoDataDeCriacaoNoMeioDoPeriodo = true;
                    }
                }

                /* Evento inativao no meio do periodo */
                if (!$grupoEvento->verificarSeEstaAtivo()) {
                    $stringFimDoPeriodo = $arrayPeriodo[6] . '-' . $arrayPeriodo[5] . '-' . $arrayPeriodo[4];
                    $dataDoFimDoPeriodoParaComparar = strtotime($stringFimDoPeriodo);
                    $dataDoGrupoEventoParaComparar = strtotime($grupoEvento->getData_inativacaoStringPadraoBanco());

                    /* Meio do periodo */
                    $excluidoDepoisQueOEventoOcorreu = true;
                    $diaQueOcorreOEvento = $grupoEvento->getEvento()->getDia();
                    if ($diaQueOcorreOEvento == 1) {
                        $diaQueOcorreOEvento = 7;
                    } else {
                        $diaQueOcorreOEvento--;
                    }
                    $diaDaSemanaQueFoiExcluido = date('w', $dataDoGrupoEventoParaComparar);
                    if ($diaDaSemanaQueFoiExcluido == 0) {
                        $diaDaSemanaQueFoiExcluido = 7;
                    }
                    if ($diaQueOcorreOEvento > $diaDaSemanaQueFoiExcluido) {
                        $excluidoDepoisQueOEventoOcorreu = false;
                    }
                    if ($dataDoGrupoEventoParaComparar >= $dataDoInicioDoPeriodoParaComparar &&
                            $dataDoGrupoEventoParaComparar <= $dataDoFimDoPeriodoParaComparar &&
                            $excluidoDepoisQueOEventoOcorreu) {
                        $validacaoDataDeCriacaoAntesDoInicioDoPeriodo = true;
                    } else {
                        if ($dataDoGrupoEventoParaComparar > $dataDoFimDoPeriodoParaComparar) {
                            $validacaoDataDeCriacaoAntesDoInicioDoPeriodo = true;
                        }
                    }
                }

                if ($validacaoDataDeCriacaoAntesDoInicioDoPeriodo || $validacaoDataDeCriacaoNoMeioDoPeriodo) {
                    $grupoEventosNoPeriodo[] = $grupoEvento;
                }
            }
        }
        return $grupoEventosNoPeriodo;
    }

    function getGrupoPessoasNoPeriodo($periodo) {

        $grupoPessoasNoPeriodo = array();

        $grupoPessoas = $this->getGrupoPessoa();
        if (!empty($grupoPessoas)) {
            foreach ($grupoPessoas as $grupoPessoa) {
                $mostrar = false;
                $dataDeCriacaoDoGrupoPessoa = strtotime($grupoPessoa->getData_criacaoStringPadraoBanco());
                $arrayPeriodo = Funcoes::montaPeriodo($periodo);
                $stringInicioPeriodo = $arrayPeriodo[3] . '-' . $arrayPeriodo[2] . '-' . $arrayPeriodo[1];
                $stringFimPeriodo = $arrayPeriodo[6] . '-' . $arrayPeriodo[5] . '-' . $arrayPeriodo[4];
                $dataDoInicioDoPeriodoParaComparar = strtotime($stringInicioPeriodo);
                $dataDoFimDoPeriodoParaComparar = strtotime($stringFimPeriodo);


                if($grupoPessoa->verificarSeEstaAtivo() && $dataDeCriacaoDoGrupoPessoa <= $dataDoFimDoPeriodoParaComparar){
                  $mostrar = true;
                }

                if(!$grupoPessoa->verificarSeEstaAtivo() && $dataDeCriacaoDoGrupoPessoa <= $dataDoFimDoPeriodoParaComparar){
                  $dataDeInativacaoDoGrupoPessoa = strtotime($grupoPessoa->getData_inativacaoStringPadraoBanco());
                  if($dataDeInativacaoDoGrupoPessoa >= $dataDoInicioDoPeriodoParaComparar){
                    $mostrar = true;
                  }                  
                }


              //  /* Periodo a frente */
              //  if (!$validacaoDataCriacao) {
              //      $arrayPeriodoAFrente = Funcoes::montaPeriodo(1);
              //      $stringPeriodoAFrente = $arrayPeriodoAFrente[3] . '-' . $arrayPeriodoAFrente[2] . '-' . $arrayPeriodoAFrente[1];
              //      $dataDoInicioDoPeriodoAFrenteParaComparar = strtotime($stringPeriodoAFrente);
              //      if ($dataDoGrupoPessoaParaComparar >= $dataDoInicioDoPeriodoAFrenteParaComparar) {
              //          $validacaoDataCriacao = true;
              //      }
              //  }

                if ($mostrar == true) {
                    $grupoPessoasNoPeriodo[] = $grupoPessoa;
                }
            }
        }
        return $grupoPessoasNoPeriodo;
    }

    /**
     * Retorna o grupo evento no ciclo selecionado
     * @param int $ciclo
     * @param int $mes
     * @param int $ano
     * @return GrupoEvento
     */
    function getGrupoEventoNoCiclo($ciclo = 1, $mes = 5, $ano = 2016) {
        $ciclo = (int) $ciclo;
        $mes = str_pad($mes, 2, 0, STR_PAD_LEFT);
        /* Validar Inativado */
        $verificacaoDataInativacao = false;
        if ($this->verificarSeEstaAtivo()) {
            $verificacaoDataInativacao = true;
        } else {
            if ($this->getData_inativacaoAno() == $ano && $this->getData_inativacaoMes() == $mes) {
                $verificacaoDataInativacao = true;
            }
        }
        if ($verificacaoDataInativacao) {
            if (is_null($this->getEventos())) {
                $primeiroDiaDaSemana = date('N', mktime(0, 0, 0, $mes, 1, $ano));
                $diaAtual = date('d');
                $mesAtual = date('m'); /* Mes com zero */
                $anoAtual = date('Y');
                $cicloAtual = Funcoes::cicloAtual($mes, $ano);
//                if ($ciclo === 1) {
//                    if ($primeiroDiaDaSemana === 1) {
//                        $primeiroDiaDaSemana = 8;
//                    } else {
//                        $primeiroDiaDaSemana++;
//                    }
//                }
                $ultimoDiaDaSemana = date('N', mktime(0, 0, 0, $mes, cal_days_in_month(CAL_GREGORIAN, $mes, $ano), $ano));
                if ($ultimoDiaDaSemana == 1) {
                    $ultimoDiaDaSemana = 8;
                } else {
                    $ultimoDiaDaSemana++;
                }
                $eventos = null;
                if (!empty($this->getGrupoEventoOrdenadosPorDiaDaSemana())) {

                    foreach ($this->getGrupoEventoOrdenadosPorDiaDaSemana() as $ge) {
                        $verificacaoDiaInativacao = false;
                        /* Validando dia da inativacao */
                        $primeiroDiaDoCiclo = Funcoes::periodoCicloMesAno($ciclo, $mes, $ano, '', 1);
                        if ($this->verificarSeEstaAtivo()) {
                            $verificacaoDiaInativacao = true;
                        } else {
                            if ($this->getData_inativacaoDia() >= $primeiroDiaDoCiclo) {
                                $verificacaoDiaInativacao = true;
                            }
                        }

                        if ($verificacaoDiaInativacao) {
                            $validacaoCelulaExcluidaMesmoDia = false;
                            /* Validação de célula , quando excluida no dia sem lançamento não aparecer */
                            if ($ge->getEvento()->verificaSeECelula()) {
                                if ($ge->getData_criacao() === $ge->getData_inativacao()) {
                                    if (!count($ge->getEvento()->getEventoFrequencia())) {
                                        $validacaoCelulaExcluidaMesmoDia = true;
                                    }
                                }
                            }

                            if (!$validacaoCelulaExcluidaMesmoDia) {
                                /* Condição para data de cadastro */
                                $verificacaoData = false;

                                if ($ge->getData_criacaoAno() <= $ano) {
                                    if ($ge->getData_criacaoAno() == $ano) {
                                        if ($ge->getData_criacaoMes() <= $mes) {
                                            if ($ge->getData_criacaoMes() == $mes) {
                                                $ge->setNovo(true);
                                                if ($ciclo === $cicloAtual) {
                                                    /* se foi cadastrado antes do dia atual ja esta valido */
                                                    $diaDaCriacao = $ge->getData_criacaoDia();

                                                    if ($diaDaCriacao <= date('d')) {
                                                        $verificacaoData = true;
                                                    } else {
                                                        /* Validar dia cadastro grupo e evento */
                                                        $diaDaSemanaDaCriacao = date('N', mktime(0, 0, 0, $mes, $diaDaCriacao, $ano));
                                                        if ($diaDaSemanaDaCriacao == 1) {
                                                            $diaDaSemanaDaCriacao = 8;
                                                        } else {
                                                            $diaDaSemanaDaCriacao++;
                                                        }
                                                        if (!($ge->getEvento()->getDiaAjustado() < $diaDaSemanaDaCriacao) && $ge->getData_criacaoDia() <= $diaAtual) {
                                                            $verificacaoData = true;
                                                        }
                                                    }
                                                } else {
                                                    $primeiroDiaCiclo = Funcoes::periodoCicloMesAno($ciclo, $mes, $ano, '', 1);
                                                    if ($ge->getData_criacaoDia() <= $primeiroDiaCiclo) {
                                                        $verificacaoData = true;
                                                    }
                                                }
                                            } else {
                                                $verificacaoData = true;
                                            }
                                        }
                                    } else {
                                        $verificacaoData = true;
                                    }
                                }

                                /* Validacao de ciclos inicial e final */
                                $verificacaoDiaSemana = false;
                                $cicloTotal = Funcoes::totalCiclosMes($mes, $ano);
                                if ($verificacaoData && ($ciclo === 1 || $ciclo === $cicloTotal)) {
                                    if ($ciclo === 1) {
                                        if ($ge->getEvento()->getDiaAjustado() >= $primeiroDiaDaSemana) {
                                            $verificacaoDiaSemana = true;
                                        }
                                    }
                                    if ($ciclo == $cicloTotal) {
                                        if ($ge->getEvento()->getDiaAjustado() <= $ultimoDiaDaSemana) {
                                            $verificacaoDiaSemana = true;
                                        }
                                    }
                                } else {
                                    $verificacaoDiaSemana = true;
                                }

                                if ($verificacaoData && $verificacaoDiaSemana) {
                                    $eventos[] = $ge;
                                }
                            }
                        }
                    }
                }
                $this->setEventos($eventos);
            }
        }
        return $this->getEventos();
    }

    function getGrupoEventoCelula() {
        $grupoEventos = null;
        foreach ($this->getGrupoEvento() as $grupoEvento) {
            if ($grupoEvento->verificarSeEstaAtivo() && $grupoEvento->getEvento()->verificaSeECelula()) {
                $grupoEventos[] = $grupoEvento;
            }
        }
        return $grupoEventos;
    }

    function getGrupoEventoCulto() {
        $grupoEventos = null;
        foreach ($this->getGrupoEvento() as $ge) {
            if ($ge->verificarSeEstaAtivo() && $ge->getEvento()->verificaSeECulto()) {
                $grupoEventos[] = $ge;
            }
        }
        return $grupoEventos;
    }

//    function getGrupoEventoRevisao() {
//        $eventos = null;
//        if (!empty($this->getGrupoEvento())) {
//            foreach ($this->getGrupoEvento() as $ge) {
//                if ($ge->verificarSeEstaAtivo() && $ge->getEvento()->verificaSeERevisao()) {
//                    $eventos[] = $ge;
//                }
//            }
//        }
//        $this->setEventos($eventos);
//        return $this->getEventos();
//    }

    function setGrupoEvento($grupoEvento) {
        $this->grupoEvento = $grupoEvento;
    }

    /**
     * Retorna o grupo pessoa
     * @return GrupoPessoa
     */
    function getGrupoPessoa() {
        return $this->grupoPessoa;
    }

    /**
     * Retorna o grupo pessoa ativas no mes infomado
     * @return GrupoPessoa
     */
    function getGrupoPessoaAtivasEDoMes($mes, $ano, $ciclo = 1) {
        $pessoas = null;
        if (!empty($this->getGrupoPessoa())) {
            foreach ($this->getGrupoPessoa() as $gp) {
                /* Condição para data de cadastro */
                $verificacaoData = false;
                if ($gp->getData_criacaoAno() <= $ano) {
                    if ($gp->getData_criacaoAno() == $ano) {
                        if ($gp->getData_criacaoMes() <= $mes) {
                            $verificacaoData = true;
                        }
                    } else {
                        $verificacaoData = true;
                    }
                }
                $condicao[1] = ($gp->verificarSeEstaAtivo() && $verificacaoData);
                $condicao[2] = (!$gp->verificarSeEstaAtivo() && $gp->verificarSeInativacaoFoiNoMesInformado($mes, $ano));
//                $condicao[3] = (!$gp->verificarSeEstaAtivo() && $verificacaoData);
                if ($condicao[1] || $condicao[2]) {
                    $pessoas[] = $gp;
                }
            }
        }
        $this->setGrupoPessoa($pessoas);
        return $this->getGrupoPessoa();
    }

    function setGrupoPessoa($grupoPessoa) {
        $this->grupoPessoa = $grupoPessoa;
    }

    function getCiclo() {
        return $this->ciclo;
    }

    function setCiclo($ciclo) {
        $this->ciclo = $ciclo;
    }

    function getEventos() {
        return $this->eventos;
    }

    function setEventos($eventos) {
        $this->eventos = $eventos;
    }

    /**
     * Pega os grupos filhos
     * @return GrupoPaiFilho
     */
    function getGrupoPaiFilhoFilhos() {
        return $this->grupoPaiFilhoFilhos;
    }

    function setGrupoPaiFilhoFilhos($grupoPaiFilhoFilhos) {
        $this->grupoPaiFilhoFilhos = $grupoPaiFilhoFilhos;
    }

    /**
     * Pega o grupo Pai
     * @return GrupoPaiFilho
     */
    function getGrupoPaiFilhoPai() {
        return $this->grupoPaiFilhoPai;
    }

    function setGrupoPaiFilhoPai($grupoPaiFilhoPai) {
        $this->grupoPaiFilhoPai = $grupoPaiFilhoPai;
    }

    function getGrupoAtendimento() {
        return $this->grupoAtendimento;
    }

    function setGrupoAtendimento($grupoAtendimento) {
        $this->grupoAtendimento = $grupoAtendimento;
        return $this;
    }

    /**
     * Retorn o GrupoCv
     * @return GrupoCv
     */
    function getGrupoCv() {
        return $this->grupoCv;
    }

    function setGrupoCv($grupoCv) {
        $this->grupoCv = $grupoCv;
    }

    /**
     * Retorna o grupo igreja do Grupo
     * @return GrupoEvento
     */
    function getGrupoIgreja() {
        $grupoSelecionado = $this;
        $grupoIgreja = null;
        if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
            while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE ||
            $grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
                    break;
                }
            }
            $grupoIgreja = $grupoSelecionado;
        } else if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
            while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
                    break;
                }
            }
            $grupoIgreja = $grupoSelecionado;
        } else if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
            $grupoIgreja = $grupoSelecionado;
        }
        return $grupoIgreja;
    }

    function contadorDeOndeEstouNaHierarquia() {
        $grupoSelecionado = $this;
        $grupoIgreja = null;
		$contador = 0;
        if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
            while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE ||
            $grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
				$contador++;
				$grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
                    break;
                }
            }
            $grupoIgreja = $grupoSelecionado;
		}
	   	if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
            while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
				$contador++;
                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
                    break;
                }
            }
            $grupoIgreja = $grupoSelecionado;
		}
	   	if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
			$contador++;
		}
        return $contador;
    }

    /**
     * Retorna o grupo equipe do Grupo
     * @return Grupo
     */
    function getGrupoEquipe() {
        $grupoSelecionado = $this;
        $grupoEquipe = null;
		if($grupoSelecionado->getEntidadeAtiva()){
			if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
				while ($grupoSelecionado->getEntidadeAtiva() && $grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
					if ($grupoSelecionado->getGrupoPaiFilhoPaiAtivo()) {
						$grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
						if($grupoSelecionado->getEntidadeAtiva()){
							if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
								break;
							}
						}
					} else {
						break;
					}
				}
				$grupoEquipe = $grupoSelecionado;
			}
			if($grupoSelecionado->getEntidadeAtiva()){
				if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
					$grupoEquipe = $grupoSelecionado;
				}
				if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
					$grupoEquipe = $grupoSelecionado;
				}
			}
		}
        return $grupoEquipe;
    }

    function getGrupoSubEquipe() {
		$grupoSelecionado = $this;
		$grupoSubEquipe = null;
		if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
			$grupoSubEquipe = $grupoSelecionado;
		}
		if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
			$grupoSubEquipe = $grupoSelecionado;
		}

		if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
			while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
				if ($grupoSelecionado->getGrupoPaiFilhoPaiAtivo()) {
					$grupoSubEquipe = $grupoSelecionado;
					$grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPaiAtivo()->getGrupoPaiFilhoPai();
					if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
						break;
					}
				} else {
					break;
				}
			}
			$grupoEquipe = $grupoSelecionado;
		}
		return $grupoSubEquipe;
    }


    function getFatoRanking() {
        return $this->fatoRanking;
    }

    function setFatoRanking($fatoRanking) {
        $this->fatoRanking = $fatoRanking;
    }

    function getTurma() {
		$turmas = $this->turma;
		$turmasAtivas = array();
		foreach($turmas as $turma){
			if($turma->verificarSeEstaAtivo() && $turma->getCurso()->getId() === Curso::INSTITUTO_DE_VENCEDORES){
				$turmasAtivas[] = $turma;
			}
		}
        return $turmasAtivas;
    }

    function setTurma($turma) {
        $this->turma = $turma;
    }

    function getPessoaCursoAcesso() {
        return $this->pessoaCursoAcesso;
    }

    function setPessoaCursoAcesso($pessoaCursoAcesso) {
        $this->pessoaCursoAcesso = $pessoaCursoAcesso;
    }

    function getGrupoAtendimentoComentario() {
        return $this->grupoAtendimentoComentario;
    }

    function getGrupoAtendimentoComentarioAtivos($mes, $ano) {
        $entidades = array();
        foreach ($this->getGrupoAtendimentoComentario() as $grupoAtendimentoComentario) {
            if ($grupoAtendimentoComentario->verificarSeEstaAtivo() &&
                    $grupoAtendimentoComentario->getData_criacaoMes() == $mes &&
                    $grupoAtendimentoComentario->getData_criacaoAno() == $ano) {
                $entidades[] = $grupoAtendimentoComentario;
            }
        }
        return $entidades;
    }

    function setGrupoAtendimentoComentario($grupoAtendimentoComentario) {
        $this->grupoAtendimentoComentario = $grupoAtendimentoComentario;
    }

    function getFatoDiscipulado() {
        return $this->fatoDiscipulado;
    }

    function setFatoDiscipulado($fatoDiscipulado) {
        $this->fatoDiscipulado = $fatoDiscipulado;
    }

	function setSolicitacao($solicitacao){
		$this->solicitacao = $solicitacao;
	}

	function getSolicitacao(){
		return $this->solicitacao;
	}

	function getSolicitacoesNaoRealizadas(){
		$solicitacoes = $this->getSolicitacoesAtivas();
		$solicitacoesNaoRealizadas = array();
		if($solicitacoes){
			foreach($solicitacoes as $solicitacao){
				if($solicitacao->getSolicitacaoSituacaoAtiva()->getSituacao()->getId() !== Situacao::CONCLUIDO){
					$solicitacoesNaoRealizadas[] = $solicitacao;
				}
			}
		}
		return $solicitacoesNaoRealizadas;
	}

	function getSolicitacoesAtivas() {
		$entidades = null;
		foreach ($this->getSolicitacao() as $solicitacao) {
			if ($solicitacao->verificarSeEstaAtivo()) {
				$entidades[] = $solicitacao;
			}
		}
		return $entidades;
	}
}
