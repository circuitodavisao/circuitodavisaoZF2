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
     * @ORM\OneToMany(targetEntity="GrupoEvento", mappedBy="grupo") 
     */
    protected $grupoEvento;

    /**
     * @ORM\OneToMany(targetEntity="GrupoPessoa", mappedBy="grupo") 
     */
    protected $grupoPessoa;

    public function __construct() {
        $this->entidade = new ArrayCollection();
        $this->grupoResponsavel = new ArrayCollection();
        $this->grupoEvento = new ArrayCollection();
        $this->grupoPessoa = new ArrayCollection();
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
     * Retorna o grupo evento no ciclo selecionado
     * @param int $ciclo
     * @param int $mes
     * @param int $ano
     * @return GrupoEvento
     */
    function getGrupoEventoNoCiclo($ciclo = 1, $mes = 5, $ano = 2016) {
        if (is_null($this->getEventos())) {
            $eventos = null;
            foreach ($this->getGrupoEvento() as $ge) {
                $verificacao = false;
                if ($ciclo == 2 || $ciclo == 3 || $ciclo == 4) {
                    $verificacao = true;
                }
                if ($ciclo == 1 || $ciclo == 5 || $ciclo == 6) {
                    $evento = $ge->getEvento();
                    if (FuncoesLancamento::eventoNoCiclo($evento->getDia(), $ciclo, $mes, $ano)) {
                        $verificacao = true;
                    }
                }
                if ($verificacao) {
                    $eventos[] = $ge;
                }
            }
            $this->setEventos($eventos);
        }
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
     */
    function setRelatorioEnviado() {
        $this->envio = 'S';
        $timeNow = new DateTime();
        $this->setEnvio_data($timeNow->format('Y-m-d'));
        $this->setEnvio_hora($timeNow->format('H:s:i'));
    }

    /**
     * Seta o status de envio para não
     */
    function setRelatorioPendente() {
        $this->envio = 'N';
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

}
