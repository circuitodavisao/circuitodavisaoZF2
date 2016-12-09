<?php

namespace Entidade\Entity;

/**
 * Nome: GrupoAtendimento.php
 * @author Lucas Carvalho <lucascarvalho.esw@gmail.com>
 * Descricao: Entidade anotada da tabela grupo_atendimento
 */

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;

/**
 * @ORM\Entity 
 * @ORM\Table(name="grupo_atendimento")
 */
class GrupoAtendimento {

    
    protected $inputFilter;
    protected $inputFilterCadastrarAtendimento;
    
    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="grupoAtendimento")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    private $grupo;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO") 
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /** @ORM\Column(type="integer") */
    protected $grupo_id;
    
    /** @ORM\Column(type="string") */
    protected $data_criacao;

    /** @ORM\Column(type="string") */
    protected $hora_criacao;
    
    /** @ORM\Column(type="string") */
    protected $dia;
    
    /** @ORM\Column(type="integer") */
    protected $quem;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    /** @ORM\Column(type="string") */
    protected $hora_inativacao;
    
    /** @ORM\Column(type="string") */
    protected $observacao;

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

    function getGrupo() {
        return $this->grupo;
    }

    function getId() {
        return $this->id;
    }

    function getGrupo_id() {
        return $this->grupo_id;
    }

    function getData_criacao() {
        return $this->data_criacao;
    }

    function getHora_criacao() {
        return $this->hora_criacao;
    }

    function getDia() {
        return $this->dia;
    }

    function getQuem() {
        return $this->quem;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function getHora_inativacao() {
        return $this->hora_inativacao;
    }

    function getObservacao() {
        return $this->observacao;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setGrupo_id($grupo_id) {
        $this->grupo_id = $grupo_id;
    }

    function setData_criacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }

    function setHora_criacao($hora_criacao) {
        $this->hora_criacao = $hora_criacao;
    }

    function setDia($dia) {
        $this->dia = $dia;
    }

    function setQuem($quem) {
        $this->quem = $quem;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

    function setHora_inativacao($hora_inativacao) {
        $this->hora_inativacao = $hora_inativacao;
    }

    function setObservacao($observacao) {
        $this->observacao = $observacao;
    }
    
    /**
     * Seta data e hora de criação
     */
    function setDataEHoraDeCriacao() {
        $timeNow = new DateTime();
        $this->setData_criacao($timeNow->format('Y-m-d'));
        $this->setHora_criacao($timeNow->format('H:s:i'));
    }
    

    function getInputFilterCadastrarAtendimento() {
        if (!$this->inputFilterCadastrarAtendimento) {
            $inputFilter = new InputFilter();
            $inputFilter->add(array(
                'name' => 'dataAtendimento',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'date',
                        'options' => array (
                                            'format' => "d/m/Y" 
                                            ) 
			)
                                        
                ),
            ));
            
            $this->inputFilterCadastrarAtendimento = $inputFilter;
        }
        return $this->inputFilterCadastrarAtendimento;
    }

    function setInputFilter($inputFilter) {
        $this->inputFilter = $inputFilter;
    }


}
