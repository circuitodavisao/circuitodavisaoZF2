<?php

namespace Application\Controller\Helper;

class Correios {

    static public function endereco($cep) {
        $cep = eregi_replace("([^0-9])", '', $cep);
        $resultado = self::cep($cep);
        if (count($resultado))
            return $resultado[0];
        else
            return null;
    }

    static public function cep($endereco) {
        include('phpQuery-onefile.php');
        $html = self::simpleCurl('http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaCepEndereco.cfm', array(
                    'relaxation' => $endereco,
                    'tipoCEP' => 'ALL',
                    'semelhante' => 'N',
        ));
        $document = \phpQuery::newDocumentHTML($html, $charset = 'utf-8');
        $pesquisa = array();

        // Selects all elements with a given class
        $matches = $document->find('.tmptabela');
        $explodeMatches = explode('>', $matches);
        $dados = array();
        $dados['logradouro'] = str_replace('</td', '', $explodeMatches[13]);
        $dados['bairro'] = str_replace('</td', '', $explodeMatches[15]);
        $explodeCidadeUf = explode('/', str_replace('</td', '', $explodeMatches[17]));
        $dados['cidade'] = $explodeCidadeUf[0];
        $dados['uf'] = $explodeCidadeUf[1];
        $pesquisa[] = $dados;
        return $pesquisa;
    }

    static public function rastreio($codigo) {
        $html = self::simpleCurl('http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI=' . $codigo);
        phpQuery::newDocumentHTML($html, $charset = 'utf-8');

        $rastreamento = array();
        $c = 0;
        foreach (pq('tr') as $tr) {
            $c++;
            if (count(pq($tr)->find('td')) == 3 && $c > 1)
                $rastreamento[] = array('data' => pq($tr)->find('td:eq(0)')->text(), 'local' => pq($tr)->find('td:eq(1)')->text(), 'status' => pq($tr)->find('td:eq(2)')->text());
        }
        if (!count($rastreamento))
            return false;
        return $rastreamento;
    }

    static private function simpleCurl($url, $post = array(), $get = array()) {
        $url = explode('?', $url, 2);
        if (count($url) === 2) {
            $temp_get = array();
            parse_str($url[1], $temp_get);
            $get = array_merge($get, $temp_get);
        }
        //die($url[0]."?".http_build_query($get));
        $ch = curl_init($url[0] . "?" . http_build_query($get));

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
    }

}
