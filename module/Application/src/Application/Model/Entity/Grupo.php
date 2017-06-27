<?php

namespace Application\Model\Entity;

/**
 * Nome: Grupo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo
 */
use Application\Controller\Helper\Funcoes;
use DateTime;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class Grupo extends CircuitoEntity {

    protected $ciclo;
    protected $eventos;

    /**
     * @ORM\OneToOne(targetEntity="GrupoCv", mappedBy="grupo")
     */
    private $grupoCv;

    /**
     * @ORM\OneToMany(targetEntity="Entidade", mappedBy="grupo") 
     */
    protected $entidade;

    /**
     * @ORM\OneToMany(targetEntity="GrupoResponsavel", mappedBy="grupo") 
     */
    protected $grupoResponsavel;

    /**
     * @ORM\OneToMany(targetEntity="GrupoAluno", mappedBy="grupo") 
     */
    protected $grupoAluno;

    /**
     * @ORM\OneToMany(targetEntity="GrupoEvento", mappedBy="grupo") 
     */
    protected $grupoEvento;

    /**
     * @ORM\OneToMany(targetEntity="GrupoPessoa", mappedBy="grupo") 
     */
    protected $grupoPessoa;

    /**
     * @ORM\OneToMany(targetEntity="GrupoAtendimento", mappedBy="grupo") 
     */
    protected $grupoAtendimento;

    /**
     * @ORM\OneToMany(targetEntity="GrupoPaiFilho", mappedBy="grupoPaiFilhoPai") 
     */
    protected $grupoPaiFilhoFilhos;

    /**
     * @ORM\OneToOne(targetEntity="GrupoPaiFilho", mappedBy="grupoPaiFilhoFilho") 
     */
    protected $grupoPaiFilhoPai;

    public function __construct() {
        $this->entidade = new ArrayCollection();
        $this->grupoResponsavel = new ArrayCollection();
        $this->grupoEvento = new ArrayCollection();
        $this->grupoPessoa = new ArrayCollection();
        $this->grupoAtendimento = new ArrayCollection();
        $this->grupoPaiFilhoFilhos = new ArrayCollection();
        $this->grupoAluno = new ArrayCollection();
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
        foreach ($this->getEntidade() as $e) {
            if ($e->verificarSeEstaAtivo()) {
                $entidadeAtiva = $e;
                break;
            }
        }
        return $entidadeAtiva;
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
    function getResponsabilidadesAtivas() {
        $responsabilidadesAtivas = array();
        /* Responsabilidades */
        $responsabilidadesTodosStatus = $this->getGrupoResponsavel();
        if ($responsabilidadesTodosStatus) {
            /* Verificar responsabilidades ativas */
            foreach ($responsabilidadesTodosStatus as $responsabilidadeTodosStatus) {
                if ($responsabilidadeTodosStatus->verificarSeEstaAtivo()) {
                    $responsabilidadesAtivas[] = $responsabilidadeTodosStatus;
                }
            }
        }
        return $responsabilidadesAtivas;
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
     * Recupera as pessoas das responsabilidades ativas
     * @return Pessoa[]
     */
    function getGrupoPaiFilhoFilhosAtivos() {
        $grupoPaiFilhoFilhosAtivos = array();
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

    function getNomeLideresAtivos() {
        $pessoas = $this->getPessoasAtivas();
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
        $grupoEventosCelulas = null;
        $grupoEventos = null;
        if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {

            $grupoEventosCelulas = $grupoSelecionado->getGrupoEventoAtivosPorTipo(GrupoEvento::CELULA);
            while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPai()->getGrupoPaiFilhoPai();
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                    break;
                }
            }
            $grupoEventos = $grupoSelecionado->getGrupoEventoAtivosPorTipo(GrupoEvento::CULTO);
        } else {
            $grupoEventos = $grupoSelecionado->getGrupoEventoAtivos();
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

                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPai()->getGrupoPaiFilhoPai();
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
                    break;
                }
            }
            $grupoEventos = $grupoSelecionado->getGrupoEventoAtivosPorTipo(GrupoEvento::REVISAO);
        } else if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
            while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPai()->getGrupoPaiFilhoPai();
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
                    break;
                }
            }
            $grupoEventos = $grupoSelecionado->getGrupoEventoAtivosPorTipo(GrupoEvento::REVISAO);
        } else {
            $grupoEventos = $grupoSelecionado->getGrupoEventoAtivosPorTipo(GrupoEvento::REVISAO);
        }

        return $grupoEventos;
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

                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPai()->getGrupoPaiFilhoPai();
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
                    break;
                }
            }
            $grupoIgreja = $grupoSelecionado;
        } else if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
            while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPai()->getGrupoPaiFilhoPai();
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
                    break;
                }
            }
            $grupoIgreja = $grupoSelecionado;
        } else if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::IGREJA) {
            $grupoIgreja = $grupoSelecionado;
        } else {
            $grupoIgreja = null;
        }

        return $grupoIgreja;
    }

    /**
     * Retorna o grupo equipe do Grupo
     * @return GrupoEvento
     */
    function getGrupoEquipe() {
        $grupoSelecionado = $this;
        $grupoEquipe = null;
        if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {
            while ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::SUBEQUIPE) {

                $grupoSelecionado = $grupoSelecionado->getGrupoPaiFilhoPai()->getGrupoPaiFilhoPai();
                if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
                    break;
                }
            }
            $grupoEquipe = $grupoSelecionado;
        } else if ($grupoSelecionado->getEntidadeAtiva()->getEntidadeTipo()->getId() === Entidade::EQUIPE) {
            $grupoEquipe = $grupoSelecionado;
        } else {
            $grupoEquipe = null;
        }

        return $grupoEquipe;
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
                if ($tipo === GrupoEvento::CULTO && $grupoEvento->getEvento()->verificaSeECulto()) {
                    $grupoEventos[] = $grupoEvento;
                }
                if ($tipo === GrupoEvento::CELULA && $grupoEvento->getEvento()->verificaSeECelula()) {
                    $grupoEventos[] = $grupoEvento;
                }
                if ($tipo === GrupoEvento::REVISAO && $grupoEvento->getEvento()->verificaSeERevisao()) {
                    $grupoEventos[] = $grupoEvento;
                }
            }
        }
        return $grupoEventos;
    }

    /**
     * Retorna o grupo aluno
     * @return GrupoAluno
     */
    function getGrupoAlunoAtivos() {
        $grupoAlunos = null;
        foreach ($this->getGrupoAluno() as $ga) {
            if ($ga->verificarSeEstaAtivo()) {
                $grupoAlunos[] = $ga;
            }
        }
        return $grupoAlunos;
    }

    /**
     * Retorna o grupo aluno que não tem cadastro
     * @return GrupoAluno
     */
    function getGrupoAlunoAtivosSemCadastro() {
        $grupoAlunos = null;
        foreach ($this->getGrupoAluno() as $ga) {
            if ($ga->verificarSeEstaAtivo()) {
                $aluno = $ga->getTurmaAluno()->getPessoa();
                if (!$aluno->verificarSeTemAlgumaResponsabilidadeAtiva()) {
                    $grupoAlunos[] = $ga;
                }
            }
        }
        return $grupoAlunos;
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

                /* Evento criado antes do inicio do periodo */
                $validacaoDataDeCriacaoAntesDoInicioDoPeriodo = false;
                if ($dataDoGrupoEventoParaComparar <= $dataDoInicioDoPeriodoParaComparar) {
                    $validacaoDataDeCriacaoAntesDoInicioDoPeriodo = true;
                }

                /* Evento criado no meio do periodo */
                $stringFimDoPeriodo = $arrayPeriodo[6] . '-' . $arrayPeriodo[5] . '-' . $arrayPeriodo[4];
                $dataDoFimDoPeriodoParaComparar = strtotime($stringFimDoPeriodo);
                $validacaoDataDeCriacaoNoMeioDoPeriodo = false;
                if ($dataDoGrupoEventoParaComparar > $dataDoInicioDoPeriodoParaComparar && $dataDoGrupoEventoParaComparar <= $dataDoFimDoPeriodoParaComparar) {
                    $validacaoDataDeCriacaoNoMeioDoPeriodo = true;
                }


                if ($validacaoDataDeCriacaoAntesDoInicioDoPeriodo || $validacaoDataDeCriacaoNoMeioDoPeriodo) {
                    $grupoEventosNoPeriodo[] = $grupoEvento;
                }
            }
        }
        return $grupoEventosNoPeriodo;
    }

    function getGrupoPessoasNoPeriodo() {
        $grupoPessoasNoPeriodo = array();
        $timeZone = new DateTimeZone('UTC');
        $grupoPessoas = $this->getGrupoPessoa();
        if (!empty($grupoPessoas)) {
            foreach ($grupoPessoas as $grupoPessoa) {

                $dataDoGrupoPessoaParaComparar = strtotime($grupoPessoa->getData_criacaoStringPadraoBrasil());
                $dataDoInicioDoPeriodoParaComparar = strtotime(Funcoes::montaPeriodo()[1] . '/' . date('m') . '/' . date('Y'));

                $data1 = DateTime::createFromFormat('d/m/Y', $dataDoGrupoPessoaParaComparar, $timeZone);
                $data2 = DateTime::createFromFormat('d/m/Y', $dataDoInicioDoPeriodoParaComparar, $timeZone);

                if ($data1 <= $data2) {
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

    function getGrupoAluno() {
        return $this->grupoAluno;
    }

    function setGrupoAluno($grupoAluno) {
        $this->grupoAluno = $grupoAluno;
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

}
