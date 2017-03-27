<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoLider.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_lider
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_lider")
 */
class FatoLider extends CircuitoEntity {

    /** @ORM\Column(type="string") */
    protected $numero_identificador;

    /** @ORM\Column(type="integer") */
    protected $lideres;

    function getNumero_identificador() {
        return $this->numero_identificador;
    }

    function getLideres() {
        return $this->lideres;
    }

    function setNumero_identificador($numero_identificador) {
        $this->numero_identificador = $numero_identificador;
    }

    function setLideres($lideres) {
        $this->lideres = $lideres;
    }

}
