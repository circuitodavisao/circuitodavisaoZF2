<?php

namespace Application\Model\Entity;

/**
 * Nome: FuncoesEntidade.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com funções de entidade
 */
class FuncoesEntidade {

    /**
     * Retorna tag img com a foto da pessoa passada
     * @param Pessoa $p
     * @return string
     */
    static public function tagImgComFotoDaPessoa(Pessoa $p, $tamanho = 50) {
        $resposta = '';

        $imagem = 'placeholder.png';
        if (!empty($p->getFoto())) {
            $imagem = $p->getFoto();
        }
        $resposta = '<img src="/img/avatars/' . $imagem . '" class="img-thumbnail" width="' . $tamanho . 'px"  height="' . $tamanho . 'px" />&nbsp;';

        return $resposta;
    }

}
