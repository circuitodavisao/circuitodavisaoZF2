<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoLider.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_celula_discipulado
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_celula_discipulado")
 */
class FatoCelulaDiscipulado extends CircuitoEntity {

    /** @ORM\Column(type="string") */
    protected $numero_identificador;

    /** @ORM\Column(type="integer") */
    protected $grupo_evento_id;

    function getNumero_identificador() {
        return $this->numero_identificador;
    }

    function setNumero_identificador($numero_identificador) {
        $this->numero_identificador = $numero_identificador;
    }

	function getGrupo_evento_id(){
		return $this->grupo_evento_id;
	}

	function setGrupo_evento_id($grupo_evento_id){
		$this->grupo_evento_id = $grupo_evento_id;
	}

}
