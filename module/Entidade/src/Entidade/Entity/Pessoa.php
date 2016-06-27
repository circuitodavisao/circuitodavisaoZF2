<?php

namespace Entidade\Entity;

/**
 * Nome: Pessoa.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela pessoa
 */
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class Pessoa {

    /**
     * @ORM\OneToMany(targetEntity="GrupoResponsavel", mappedBy="pessoa") 
     */
    protected $grupoResponsavel;

    /**
     * @ORM\OneToMany(targetEntity="EventoFrequencia", mappedBy="pessoa") 
     */
    protected $eventoFrequencia;

    public function __construct() {
        $this->grupoResponsavel = new ArrayCollection();
        $this->eventoFrequencia = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="integer") */
    protected $telefone;

    /** @ORM\Column(type="string") */
    protected $email;

    /** @ORM\Column(type="string") */
    protected $senha;

    /** @ORM\Column(type="string") */
    protected $data_criacao;

    /** @ORM\Column(type="string") */
    protected $hora_criacao;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    /** @ORM\Column(type="string") */
    protected $data_nascimento;

    /** @ORM\Column(type="string") */
    protected $documento;

    /** @ORM\Column(type="string") */
    protected $token;

    /** @ORM\Column(type="string") */
    protected $token_data;

    /** @ORM\Column(type="string") */
    protected $token_hora;

    /** @ORM\Column(type="string") */
    protected $foto;
    protected $tipo;
    protected $transferido;

    /**
     * Recupera as Responsabilidades ativas
     * @return Entidade[]
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
        /* Ordenando */
        if ($responsabilidadesAtivas) {
            for ($i = 0; $i < count($responsabilidadesAtivas); $i++) {
                for ($j = 0; $j < count($responsabilidadesAtivas); $j++) {
                    $r[1] = $responsabilidadesAtivas[$i];
                    $tipo[1] = $r[1]->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId();

                    $r[2] = $responsabilidadesAtivas[$j];
                    $tipo[2] = $r[2]->getGrupo()->getEntidadeAtiva()->getEntidadeTipo()->getId();

                    if ($tipo[1] < $tipo[2]) {
                        $responsabilidadesAtivas[$j] = $r[1];
                        $responsabilidadesAtivas[$i] = $r[2];
                    }
                }
            }
        }
        return $responsabilidadesAtivas;
    }

    /**
     * Retorna o primeiro e ultimo nome da pessoa
     * @return String
     */
    function getNomePrimeiroUltimo() {
        $explodeNome = explode(" ", $this->getNome());
        $primeiroNome = $explodeNome[0];
        $ultimoNome = $explodeNome[(count($explodeNome) - 1)];
        return $primeiroNome . '&nbsp;' . $ultimoNome;
    }

    /**
     * Retorna o primeiro e a sigla do ultimo nome da pessoa
     * @return String
     */
    function getNomePrimeiroPrimeiraSiglaUltimo() {
        $explodeNome = explode(" ", $this->getNome());
        $primeiroNome = $explodeNome[0];
        $ultimoNome = substr($explodeNome[(count($explodeNome) - 1)], 0, 1);
        return $primeiroNome . '&nbsp;' . $ultimoNome . '.';
    }

    /**
     * Retorna o nome formatado
     * @return String
     */
    function getNomeListaDeLancamento() {
        $nome = substr($this->getNome(), 0, 8);
        return $nome . '..';
    }

    /**
     * Verificar se esta transferido ou nao
     * @return boolean
     */
    public function verificarSeFoiTransferido() {
        $resposta = false;
        if ($this->getTransferido() == 'S') {
            $resposta = true;
        }
        return $resposta;
    }

    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getEmail() {
        return $this->email;
    }

    function getSenha() {
        return $this->senha;
    }

    function getData_criacao() {
        return $this->data_criacao;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getData_nascimento() {
        return $this->data_nascimento;
    }

    function getDocumento() {
        return $this->documento;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setSenha($senha) {
        $this->senha = md5($senha);
    }

    function setData_criacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setData_nascimento($data_nascimento) {
        $this->data_nascimento = $data_nascimento;
    }

    function setDocumento($documento) {
        $this->documento = $documento;
    }

    function getToken() {
        return $this->token;
    }

    /**
     * Seta token e data para validacao
     * @param String $token
     */
    function setToken($token) {
        $this->token = $token;
        $timeNow = new DateTime();
        $this->setToken_data($timeNow->format('Y-m-d'));
        $this->setToken_hora($timeNow->format('H:s:i'));
    }

    function getToken_data() {
        return $this->token_data;
    }

    function getToken_data_ano() {
        return substr($this->token_data, 0, 4);
    }

    function getToken_data_mes() {
        return substr($this->token_data, 5, 2);
    }

    function getToken_data_dia() {
        return substr($this->token_data, 8, 2);
    }

    function setToken_data($token_data) {
        $this->token_data = $token_data;
    }

    function getToken_hora() {
        return $this->token_hora;
    }

    function getToken_hora_hora() {
        return substr($this->token_hora, 0, 2);
    }

    function getToken_hora_minutos() {
        return substr($this->token_hora, 3, 2);
    }

    function getToken_hora_segundos() {
        return substr($this->token_hora, 6, 2);
    }

    function setToken_hora($token_hora) {
        $this->token_hora = $token_hora;
    }

    function getHora_criacao() {
        return $this->hora_criacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
    }

    function setHora_criacao($hora_criacao) {
        $this->hora_criacao = $hora_criacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
    }

    /**
     * Retorna as responsailidades da pessoa
     * @return GrupoResponsavel
     */
    function getGrupoResponsavel() {
        return $this->grupoResponsavel;
    }

    function setGrupoResponsavel($grupoResponsavel) {
        $this->grupoResponsavel = $grupoResponsavel;
    }

    function getTelefone() {
        return $this->telefone;
    }

    function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    /**
     * Retorna os eventoFrequencia
     * @return EventoFrequencia
     */
    function getEventoFrequencia() {
        return $this->eventoFrequencia;
    }

    function setEventoFrequencia($eventoFrequencia) {
        $this->eventoFrequencia = $eventoFrequencia;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function getFoto() {
        return $this->foto;
    }

    function setFoto($foto) {
        $this->foto = $foto;
    }

    function getTransferido() {
        return $this->transferido;
    }

    function setTransferido($transferido) {
        $this->transferido = $transferido;
    }

}
