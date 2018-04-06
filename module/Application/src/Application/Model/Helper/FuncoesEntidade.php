<?php

namespace Application\Model\Helper;

use Application\Model\Entity\Pessoa;

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
    static public function tagImgComFotoDaPessoa(Pessoa $p, $tamanho = 50, $tipoTamanho = 'px') {
        $resposta = '';

        $imagem = FuncoesEntidade::nomeDaImagem($p);
        $resposta = '<img src="/img/avatars/' . $imagem . '" class="img-thumbnail" width="' . $tamanho . $tipoTamanho . '"  height="' . $tamanho . $tipoTamanho . '" />&nbsp;';

        return $resposta;
    }

    static public function nomeDaImagem(Pessoa $pessoa) {
        $imagem = 'placeholder.png';
        if (!empty($pessoa->getFoto())) {
            $imagem = $pessoa->getFoto();
        }
        return $imagem;
    }

}
