<h1>Circuito da Visão - O Facilitador do seu Ministério </h1>
Software de controle e supervisão ministerial da Sara Nossa Terra.
<h2>História</h2>
  Software WEB nascido em 2013 na SNT Ceilândia como controle administrativo do Instituto de Vencedores e posteriormente Revisão de Vidas, ganhando assim notoriedade ao olhar dos Bispos presidentes Robson e Lúcia Rodovalho, que lançaram o desafio de expandir o circuito para toda a regional do Distrito Federal. O desafio foi alcançado, e hoje o circuito da visão atende todas as igrejas do Distrito Federal nas mais diversas tarefas de controle e supervisão, com mais de 2000 acessos diarios e mais de 35 mil acessos mensais, alcançando no final do ano de 2014 a aprovação da expansão de seus serviços junto ao conselho de bispos da SNT, começando a partir de 2015 a implantação do circuito da visão em todo o Brasil.
<h2>Sede</h2>
  Sara Nossa Terra da Ceilândia - DF
<h2>Colaboradores</h2>
  Pr. Diego Kort (Fundador) <br/>
  Leonardo Pereira (Fundador) - [@lpmagalhaes](https://github.com/lpmagalhaes) <br/>
  Lucas Carvalho - [@Lcunha](https://github.com/Lcunha) <br/>
<h2>Ambientes:</h2>
  Produção - (www.circuitodavisao.com.br) <br/>
  Homologação - (www.circuitodavisaoteste.com.br) <br/>
<h2>Metodologia de trabalho</h2>
  <ul>
    <li>Desenvolvimento
    <ol>
      <li>
        Fazer clone o do repositório central executando, git clone https://github.com/lpmagalhaes/circuitodavisaoZF2.git
      </li>
      <li>
        Criar a branch local desenvolvimento que aponta para a branch remota orign/desenvolvimento no repositório central, executando:
        <ul>
        <li>
        git fetch origin
        </li>
        <li>
        git checkout -t origin/desenvolvimento
        </li>
        </ul>
      </li>
      <li>
      Para cada funcionalidade criar uma branch apartir da branch de desenvolvimento, executando:
      <ul>
      <li>
      git checkout -b nomeDaFuncionalidade desenvolvimento
      </li>
      </ul>
      </li>
      <li>
      Edite seus arquivo, suba para o servidor de homologação via FTP (O acesso está em modelo/DaoGeral.php)
      </li>
      <li>
      Compartilhe a branch com a equipe de desenvolvimento, executando:
       <ul>
      <li>
      git push origin nomeDaFuncionalidade
       </li>
      </ul>
      </li>
      <li>
      Finalizando a nova funcionalidade fará o merge com a branch de desenvolvimento, antes obtendo a ultima versão da mesma, executando:
      <ul>
      <li>
      git pull origin desenvolvimento
      </li>
      <li>
      git checkout desenvolvimento
      </li>
      <li>
      git merge nomeDaFuncionalidade
      </li>
      <li>
      git push origin desenvolvimento
      </li>
      </ul>
      </li>
    </ol> 
    </li>
  </ul>
<h2>Especificações Técnicas </h2>
  Paradigma de Programação: Orientado a Objetos.<br/>
  Linguagem de Programação: PHP <br/>
  Linguagem de script WEB: JavaScript com biblioteca Jquery. <br/>
  Linguagens de marcação: HTML5 + CSS3. <br/>
  Banco de Dados: PostgreSQL <br/>
  Controle de Versão: Git + GitHub. <br/>
  Servidor Web: Apache <br/>
  OS: Ubuntu 14.04.3 LTS (GNU/Linux 3.14.32-xxxx-grs-ipv6-64 x86_64)