<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="envio")
 */
class Envio extends CircuitoEntity {

    /** @ORM\Column(type="string") */
    protected $grupo_id;

    /** @ORM\Column(type="integer") */
    protected $status;

   function getGrupo_id() {
        return $this->grupo_id;
    }

    function getStatus() {
        return $this->status;
    }

    function setGrupo_id($elemento) {
        $this->grupo_id = $elemento;
    }

    function setStatus($elemento) {
        $this->status = $elemento;
    }

}
