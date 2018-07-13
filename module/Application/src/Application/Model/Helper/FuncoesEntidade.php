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
    static public function tagImgComFotoDaPessoa(Pessoa $pessoa = null, $tamanho = 50, $tipoTamanho = 'px', $extra = '') {
        $resposta = '';
        $imagem = '';
        if ($pessoa) {
            $imagem = FuncoesEntidade::nomeDaImagem($pessoa);
        }
        $resposta = '<img ' . $extra . ' id="fotoPerfil" src="/img/fotos/' . $imagem . '" class="img-thumbnail" width="' . $tamanho . $tipoTamanho . '"  height="' . $tamanho . $tipoTamanho . '" />&nbsp;';

        return $resposta;
    }

    static public function nomeDaImagem(Pessoa $pessoa) {
        $imagem = $pessoa->getFoto();
        if ($imagem == '') {
            $imagem = 'placeholder.png';
        }
        return $imagem;
    }

}
