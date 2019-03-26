<?php

namespace Application\Model\Entity;

/**
 * Nome: Registro.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela registro
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="registro")
 */
class Registro extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="RegistroAcao", inversedBy="registro")
     * @ORM\JoinColumn(name="registro_acao_id", referencedColumnName="id")
     */
    private $registroAcao;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="registro")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /** @ORM\Column(type="integer") */
    protected $registro_acao_id;

    /** @ORM\Column(type="integer") */
    protected $grupo_id;

    /** @ORM\Column(type="string") */
    protected $extra;

    /** @ORM\Column(type="string") */
    protected $ip;

    function getRegistroAcao() {
        return $this->registroAcao;
    }

	function getGrupo() {
        return $this->grupo;
    }

    function getRegistro_acao_id() {
        return $this->registro_acao_id;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function getExtra() {
        return $this->extra;
    }

    function setRegistroAcao($registroAcao) {
        $this->registroAcao = $registroAcao;
    }

	function setGrupo($grupo){
		$this->grupo = $grupo;
	}

    function setRegistro_acao_id($registro_acao_id) {
        $this->registro_acao_id = $registro_acao_id;
    }

	function setGrupo_id($grupo_id){
		$this->grupo_id = $grupo_id;
	}

    function setExtra($extra) {
        $this->extra = $extra;
    }

	function setIp($ip){
		$this->ip = $ip;
	}

	function getIp(){
		return $this->ip;
	}
}
