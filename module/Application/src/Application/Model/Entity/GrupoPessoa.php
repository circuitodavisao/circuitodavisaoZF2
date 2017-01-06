<?php

namespace Application\Model\Entity;

/**
 * Nome: GrupoPessoa.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_pessoa
 * 1 - VISITANTE
 * 2 - CONSOLIDACAO
 * 3 - MEMBRO
 * 4 - ALUNO ATIVO
 * 5 - ALUNO REPROVADO
 * 6 - ALUNO DESISTENTE
 * 7 - ALUNO NAO ENTROU
 * 8 - ALUNO FORMADO
 */
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_pessoa")
 */
class GrupoPessoa {

    /**
     * @ORM\ManyToOne(targetEntity="Pessoa", inversedBy="grupoPessoa")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoPessoa")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /**
     * @ORM\ManyToOne(targetEntity="GrupoPessoaTipo", inversedBy="grupoPessoa")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
    private $grupoPessoaTipo;

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

    /** @ORM\Column(type="integer") */
    protected $pessoa_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="integer") */
    protected $tipo_id; 

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    /** @ORM\Column(type="string") */
    protected $transferido;

    /** @ORM\Column(type="string") */
    protected $nucleo_perfeito;

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
     * Verificar se a data de inativação foi no mes informado
     * @return boolean
     */
    public function verificarSeInativacaoFoiNoMesInformado($mes, $ano) {
        $resposta = false;
        if ($this->getData_inativacaoMes() == $mes && $this->getData_inativacaoAno() == $ano) {
            $resposta = true;
        }
        return $resposta;
    }

    /**
     * Retorna a pessoa
     * @return Pessoa
     */
    function getPessoa() {
        return $this->pessoa;
    }

    /**
     * Retorna o grupo da responsabilidade
     * @return Grupo
     */
    function getGrupo() {
        return $this->grupo;
    }

    /**
     * Identificação da responsabilidade
     * @return int
     */
    function getId() {
        return $this->id;
    }

    function getData_criacao() {
        return $this->data_criacao;
    }

    function getHora_criacao() {
        return $this->hora_criacao;
    }

    function getPessoa_id() {
        return $this->pessoa_id;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
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

    function setPessoa_id($pessoa_id) {
        $this->pessoa_id = $pessoa_id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
    }

    /**
     * Seta data e hora de inativação
     */
    function inativar() {
        $timeNow = new DateTime();
        $this->setData_inativacao($timeNow->format('Y-m-d'));
        $this->setHora_inativacao($timeNow->format('H:s:i'));
    }

    /**
     * Retorna grupo pessoa tipo
     * @return GrupoPessoaTipo
     */
    function getGrupoPessoaTipo() {
        return $this->grupoPessoaTipo;
    }

    function setGrupoPessoaTipo($grupoPessoaTipo) {
        $this->grupoPessoaTipo = $grupoPessoaTipo;
    }

    function getData_criacaoAno() {
        return explode('-', $this->getData_criacao())[0];
    }

    function getData_criacaoMes() {
        return explode('-', $this->getData_criacao())[1];
    }

    function getData_inativacaoAno() {
        return explode('-', $this->getData_inativacao())[0];
    }

    function getData_inativacaoMes() {
        return explode('-', $this->getData_inativacao())[1];
    }

    function getTransferido() {
        return $this->transferido;
    }

    function setTransferido($transferido) {
        $this->transferido = $transferido;
    }

    function getNucleo_perfeito() {
        return $this->nucleo_perfeito;
    }

    function setNucleo_perfeito($nucleo_perfeito) {
        $this->nucleo_perfeito = $nucleo_perfeito;
    }

    function getTipo_id() {
        return $this->tipo_id;
    }

    function setTipo_id($tipo_id) {
        $this->tipo_id = $tipo_id;
    }

}
