<?php

namespace Entidade\Entity;

/**
 * Nome: Grupo.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo
 */
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Lancamento\Controller\Helper\FuncoesLancamento;
use Lancamento\Controller\Helper\LancamentoORM;

/** @ORM\Entity */
class Grupo {

    protected $ciclo;
    protected $eventos;

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
        $this->grupoPaiFilhoFilhos = new ArrayCollection();
        $this->grupoAluno = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $data_criacao;

    /** @ORM\Column(type="string") */
    protected $hora_criacao;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    /** @ORM\Column(type="string") */
    protected $envio;

    /** @ORM\Column(type="string") */
    protected $envio_data;

    /** @ORM\Column(type="string") */
    protected $envio_hora;

    /**
     * Seta data e hora de criação
     */
    function setDataEHoraDeCriacao() {
        $timeNow = new DateTime();
        $this->setData_criacao($timeNow->format('Y-m-d'));
        $this->setHora_criacao($timeNow->format('H:s:i'));
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
     * Verificar se a data de inativação está nula
     * @return boolean
     */
    public function verificarSeEstaAtivo() {
        $resposta = false;
        if (is_null($this->getData_inativacao())) {
            $resposta = true;
        }
        return $resposta;
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

    function getId() {
        return $this->id;
    }

    function getData_criacao() {
        return $this->data_criacao;
    }

    function getHora_criacao() {
        return $this->hora_criacao;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
    }

    function setEntidade($entidade) {
        $this->entidade = $entidade;
    }

    function setGrupoResponsavel($grupoResponsavel) {
        $this->grupoResponsavel = $grupoResponsavel;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setData_criacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }

    function setHora_criacao($hora_criacao) {
        $this->hora_criacao = $hora_criacao;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
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
        $grupoEventos = $this->getGrupoEvento();
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
                    if ($trocar == 1) {
                        $grupoEventos[$i] = $evento2;
                        $grupoEventos[$j] = $evento1;
                    }
                }
            }
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

    /**
     * Retorna o grupo evento no ciclo selecionado
     * @param int $ciclo
     * @param int $mes
     * @param int $ano
     * @return GrupoEvento
     */
    function getGrupoEventoNoCiclo($ciclo = 1, $mes = 5, $ano = 2016) {
        if (is_null($this->getEventos())) {
            $eventos = null;
            if (!empty($this->getGrupoEventoOrdenadosPorDiaDaSemana())) {
                if ($ciclo == 1) {
                    $primeiroDiaDaSemana = date('N', mktime(0, 0, 0, $mes, 1, $ano));
                    if ($primeiroDiaDaSemana == 1) {
                        $primeiroDiaDaSemana = 8;
                    } else {
                        $primeiroDiaDaSemana++;
                    }
                }
                $ultimoDiaDaSemana = date('N', mktime(0, 0, 0, $mes, cal_days_in_month(CAL_GREGORIAN, $mes, $ano), $ano));
                if ($ultimoDiaDaSemana == 1) {
                    $ultimoDiaDaSemana = 8;
                } else {
                    $ultimoDiaDaSemana++;
                }
                foreach ($this->getGrupoEventoOrdenadosPorDiaDaSemana() as $ge) {
                    $validacaoCelulaExcluidaMesmoDia = false;
                    /* Validação de célula , quando excluida no dia sem lançamento não aparecer */
                    if ($ge->getEvento()->getEventoTipo()->getId() == 2) {
                        if ($ge->getData_criacao() == $ge->getData_inativacao()) {
                            if (!count($ge->getEvento()->getEventoFrequencia())) {
                                $validacaoCelulaExcluidaMesmoDia = true;
                            }
                        }
                    }

                    if (!$validacaoCelulaExcluidaMesmoDia) {
                        /* Condição para data de cadastro */
                        $verificacaoData = false;
                        $diaAtual = date('d');
                        $mesAtual = date('m'); /* Mes com zero */
                        $anoAtual = date('Y');
                        $cicloAtual = FuncoesLancamento::cicloAtual($mes, $ano);
                        if ($ge->getData_criacaoAno() <= $ano) {
                            if ($ge->getData_criacaoAno() == $ano) {
                                if ($ge->getData_criacaoMes() <= $mes) {
                                    if ($ge->getData_criacaoMes() == $mes) {
                                        $ge->setNovo(true);
                                        if ($ciclo == $cicloAtual) {
                                            if ($ge->getData_criacaoDia() <= $diaAtual) {
                                                $verificacaoData = true;
                                            }
                                        } else {
                                            $primeiroDiaCiclo = FuncoesLancamento::periodoCicloMesAno($ciclo, $mes, $ano, '', 1);
                                            if ($ge->getData_criacaoDia() < $primeiroDiaCiclo) {
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
                        $cicloTotal = FuncoesLancamento::totalCiclosMes($mes, $ano);
                        if ($verificacaoData && ($ciclo == 1 || $ciclo == $cicloTotal)) {
                            if ($ciclo == 1) {
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
            $this->setEventos($eventos);
        }
        return $this->getEventos();
    }

    function getGrupoEventoCelula() {
        $eventos = null;
        if (!empty($this->getGrupoEvento())) {
            foreach ($this->getGrupoEvento() as $ge) {
                if ($ge->verificarSeEstaAtivo() && $ge->getEvento()->verificaSeECelula()) {
                    $eventos[] = $ge;
                }
            }
        }
        $this->setEventos($eventos);
        return $this->getEventos();
    }

    function getGrupoEventoCulto() {
        $eventos = null;
        if (!empty($this->getGrupoEvento())) {
            foreach ($this->getGrupoEvento() as $ge) {
                if ($ge->verificarSeEstaAtivo() && $ge->getEvento()->verificaSeECulto()) {
                    $eventos[] = $ge;
                }
            }
        }
        $this->setEventos($eventos);
        return $this->getEventos();
    }

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
                $condicao[3] = (!$gp->verificarSeEstaAtivo() && $verificacaoData);
                if ($condicao[1] || $condicao[2] || $condicao[3]) {
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

    function getEnvio() {
        return $this->envio;
    }

    function getEnvio_data() {
        return $this->envio_data;
    }

    function getEnvio_hora() {
        return $this->envio_hora;
    }

    function setEnvio($envio) {
        $this->envio = $envio;
    }

    /**
     * Seta o status de envio para sim e alterar data e hora de envio
     * @param LancamentoORM $lancamentoORM
     */
    function setRelatorioEnviado(LancamentoORM $lancamentoORM) {
        $this->envio = 'S';
        $timeNow = new DateTime();
        $this->setEnvio_data($timeNow->format('Y-m-d'));
        $this->setEnvio_hora($timeNow->format('H:s:i'));
        $lancamentoORM->getGrupoORM()->persistirGrupo($this);
    }

    /**
     * Seta o status de envio para não
     * @param LancamentoORM $lancamentoORM
     */
    function setRelatorioPendente(LancamentoORM $lancamentoORM) {
        $this->envio = 'N';
        $lancamentoORM->getGrupoORM()->persistirGrupo($this);
    }

    /**
     * Verificar o status de envio o relatório
     * @return boolean
     */
    public function verificarSeFoiEnviadoORelatorio() {
        $resposta = false;
        if ($this->getEnvio() == 'S') {
            $resposta = true;
        }
        return $resposta;
    }

    function setEnvio_data($envio_data) {
        $this->envio_data = $envio_data;
    }

    function setEnvio_hora($envio_hora) {
        $this->envio_hora = $envio_hora;
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

}
