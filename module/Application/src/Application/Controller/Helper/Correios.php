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
        $html = self::simpleCurl('https://buscacepinter.correios.com.br/app/endereco/carrega-cep-endereco.php', array(
                    'endereco' => $endereco,
					'tipoCEP' => 'ALL',
					'pagina' => '/app/endereco/index.php',
        ));

        $dados = array();
		$json = json_decode($html);
		$dados['json'] = $json;
		$dados['logradouro'] = $json->dados[0]->logradouroDNEC;
		$dados['bairro'] = $json->dados[0]->bairro;
        $dados['cidade'] = $json->dados[0]->localidade;
        $dados['uf'] = $json->dados[0]->uf;
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
