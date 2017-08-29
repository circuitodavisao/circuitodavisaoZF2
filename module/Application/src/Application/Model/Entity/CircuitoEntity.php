<?php

namespace Application\Model\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Nome: CircuitoEntity.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base
 */
class CircuitoEntity {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="datetime", name="data_criacao") */
    protected $data_criacao;

    /** @ORM\Column(type="string") */
    protected $hora_criacao;

    /** @ORM\Column(type="datetime", name="data_inativacao") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;

    /**
     * Seta data e hora de criação
     */
    function setDataEHoraDeCriacao() {
        $timeNow = new DateTime();
        $this->setData_criacao($timeNow);
        $this->setHora_criacao($timeNow->format('H:s:i'));
    }

    /**
     * Seta data e hora de criação
     */
    function setDataEHoraDeInativacao() {
        $timeNow = new DateTime();
        $this->setData_inativacao($timeNow);
        $this->setHora_inativacao($timeNow->format('H:s:i'));
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

    function getId() {
        return $this->id;
    }

    function getData_criacao() {
        return $this->data_criacao;
    }

    function getHora_criacao() {
        return $this->hora_criacao;
    }

    /**
     * Retorna a data de inativacao
     * @return DateTime
     */
    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
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

    function getData_criacaoAno() {
        return $this->getData_criacao()->format('Y');
    }

    function getData_criacaoMes() {
        return $this->getData_criacao()->format('m');
    }

    function getData_criacaoDia() {
        return $this->getData_criacao()->format('d');
    }

    function getData_inativacaoAno() {
        if ($this->getData_inativacao()) {
            return $this->getData_inativacao()->format('Y');
        }
    }

    function getData_inativacaoMes() {
        if ($this->getData_inativacao()) {
            return $this->getData_inativacao()->format('m');
        }
    }

    function getData_inativacaoDia() {
        if ($this->getData_inativacao()) {
            return $this->getData_inativacao()->format('d');
        }
    }

    function getData_criacaoStringPadraoBrasil() {
        return $this->getData_criacao()->format('d/m/Y');
    }

    function getData_criacaoStringPadraoBanco() {
        return $this->getData_criacao()->format('Y-m-d');
    }

    function getData_inativacaoStringPadraoBanco() {
        return $this->getData_inativacao()->format('Y-m-d');
    }
    
    function getData_inativacaoStringPadraoBrasil() {
        return $this->getData_inativacao()->format('d/m/Y');
    }

}
