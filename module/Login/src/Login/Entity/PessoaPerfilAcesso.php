<?php

namespace Login\Entity;

/**
 * Nome: PessoaPerfilAcesso.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada da tabela associativa pessoa_perfil_acesso
 */
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class PessoaPerfilAcesso {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $id_pessoa;

    /**
     * @ORM\Column(type="integer")
     */
    protected $id_perfil_acesso;

    /** @ORM\Column(type="string") */
    protected $data_criacao;

    /** @ORM\Column(type="string") */
    protected $data_inativacao;

    function getId() {
        return $this->id;
    }

    function getId_pessoa() {
        return $this->id_pessoa;
    }

    function getId_perfil_acesso() {
        return $this->id_perfil_acesso;
    }

    function getData_criacao() {
        return $this->data_criacao;
    }

    function getData_inativacao() {
        return $this->data_inativacao;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setId_pessoa($id_pessoa) {
        $this->id_pessoa = $id_pessoa;
    }

    function setId_perfil_acesso($id_perfil_acesso) {
        $this->id_perfil_acesso = $id_perfil_acesso;
    }

    function setData_criacao($data_criacao) {
        $this->data_criacao = $data_criacao;
    }

    function setData_inativacao($data_inativacao) {
        $this->data_inativacao = $data_inativacao;
    }

}
