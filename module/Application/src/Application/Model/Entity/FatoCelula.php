<?php

namespace Application\Model\Entity;

/**
 * Nome: FatoCelula.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela fato_celula
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="fato_celula")
 */
class FatoCelula extends CircuitoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="FatoCiclo", inversedBy="fatoCelula")
     * @ORM\JoinColumn(name="fato_ciclo_id", referencedColumnName="id")
     */
    private $fatoCiclo;

    /** @ORM\Column(type="integer") */
    protected $fato_ciclo_id;

    /** @ORM\Column(type="integer") */
    protected $realizada;

    function getFato_ciclo_id() {
        return $this->fato_ciclo_id;
    }

    function getRealizada() {
        return $this->realizada;
    }

    function setFato_ciclo_id($fato_ciclo_id) {
        $this->fato_ciclo_id = $fato_ciclo_id;
    }

    function setRealizada($realizada) {
        $this->realizada = $realizada;
    }

    /**
     * Retorna o fato ciclo
     * @return FatoCiclo
     */
    function getFatoCiclo() {
        return $this->fatoCiclo;
    }

    function setFatoCiclo($fatoCiclo) {
        $this->fatoCiclo = $fatoCiclo;
    }

}
