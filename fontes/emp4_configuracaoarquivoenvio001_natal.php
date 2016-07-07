<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

set_time_limit(0);
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta_plugin.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));


require_once(modification("libs/db_sql.php"));
require_once(modification("classes/db_termo_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("libs/db_app.utils.php"));


$iInstit = db_getsession("DB_instit");
$clrotulo = new rotulocampo;
$clrotulo->label("e80_data");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e80_codage");
$clrotulo->label("e50_codord");
$clrotulo->label("e50_numemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_emiss");
$clrotulo->label("e82_codord");
$clrotulo->label("e87_descgera");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
$clrotulo->label("k17_slip");


?>


<html>
<head>
<style type="">

  .valor {
    width: 100px;
  }
  #iTipoTransmissao{
    width: 100px;
  }

.configurada {
    background-color: #d1f07c;
}
.ComMov {
    background-color: rgb(222, 184, 135);
}
.naOPAuxiliar {
    background-color: #ffff99;
}
.configuradamarcado {
    background-color: #EFEFEF;
}
.ComMovmarcado {
    background-color: #EFEFEF;
}
.naOPAuxiliarmarcado {
    background-color: #EFEFEF;
}
.normalmarcado{ background-color:#EFEFEF}
</style>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js");
  db_app::load("dbtextField.widget.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("DBLancador.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");
  db_app::load("dbcomboBox.widget.js");
  db_app::load("widgets/DBAncora.widget.js");
  db_app::load("dbtextFieldData.widget.js");
  db_app::load("DBCodigoBarra.widget.js");
?>

</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >

<center>
<form name="form1" method="post">

    <fieldset style="margin-top: 50px; width: 600px;">
    <legend><strong>Filtros de Pesquisa</strong></legend>
      <table border="0" align='left' >

        <tr>
          <td colspan="1">
            <strong>Forma de Consulta:</strong>
          </td>
          <td nowrap>
            <?
              $aformaConsulta = array("0" => "Empenho" , "1" => "Slip");
              db_select('iFomaconsulta', $aformaConsulta, true, 1, "onChange = 'js_formaConsulta();'");
            ?>
          </td>
        </tr>

        <tr>
          <td>
            <strong>Data Inicial:</strong>
          </td>

          <td nowrap>
            <?
            db_inputdata("datainicial",null,null,null,true,"text", 1);
            ?>
          </td>

          <td>
            <strong>Data Final:</strong>
          </td>

          <td nowrap align="">
            <?
            db_inputdata("datafinal",null,null,null,true,"text", 1);
            ?>
          </td>

        </tr>

          <tr>
            <td nowrap title="<?=@$Te82_codord?>">
             <?db_ancora(@$Le82_codord,"js_pesquisae82_codord(true);",1);  ?>
            </td>
            <td nowrap>
              <?
              db_input('e82_codord',10,$Ie82_codord,true,'text',1," onchange='js_pesquisae82_codord(false);'");
              ?>
            </td>
            <td>
              <?
              db_ancora("<b>at�:</b>","js_pesquisae82_codord02(true);",1);
              ?>
            </td>
            <td nowrap align="left">
             <?
              db_input('e82_codord2',10,$Ie82_codord,true,'text',1,
                     "onchange='js_pesquisae82_codord02(false);'","e82_codord02");
             ?>
            </td>
          </tr>


          <tr id='ctnSlip' style="display:none;">
             <td nowrap title="<?=@$Tk17_slip?>">
               <? db_ancora("<b>Slip</b>","js_pesquisak17_slip(true);",1);  ?>
             </td>
             <td nowrap>
               <? db_input('k17_slip',10,$Ie82_codord,true,'text',1, "onchange='js_pesquisak17_slip(false);'")?>
               </td>
               <td>
               <? db_ancora("<b>at�:</b>","js_pesquisak17_slip02(true);",1);  ?>
             </td>
             <td nowrap align="left">
               <? db_input('k17_slip02',10,$Ie82_codord,true,'text',1,
                           "onchange='js_pesquisak17_slip02(false);'")?>
             </td>
          </tr>


         <tr id='ctnEmpenho' style='display:none;'>
           <td  nowrap title="<?=$Te60_numemp?>" colspan="1">
             <?
             db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",1);
             ?>
           </td>
           <td nowrap>
             <input name="e60_codemp" id='e60_codemp'
                   title='<?=$Te60_codemp?>' size="10" type='text'  />
           </td>
         </tr>


         <tr>
           <td nowrap title="<?=@$Tz01_numcgm?>">
             <?
              db_ancora("<b>Credor:</b>","js_pesquisaz01_numcgm(true);",1);
             ?>
           </td>
           <td  colspan='4' nowrap>
             <?
               db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',1," onchange='js_pesquisaz01_numcgm(false);'");
               db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
             ?>
           </td>
         </tr>


          <tr nowrap>
            <td nowrap title="<?=@$To15_codigo?>">
              <? db_ancora(@$Lo15_codigo,"js_pesquisac62_codrec(true);",1); ?>
            </td>
            <td colspan=3 nowrap>
              <?
                db_input('o15_codigo',10,$Io15_codigo,true,'text',1," onchange='js_pesquisac62_codrec(false);'");
                db_input('o15_descr',40,$Io15_descr,true,'text',3,'');
              ?>
            </td>
         </tr>

      </table>
    </fieldset>
    <div style="margin-top: 10px;">
      <input type="button" id="pesquisar"  value="Pesquisar" onclick="js_pesquisar();">
      <input type="button" id="btnGerarArquivoTXT"  value="Emitir Arquivo Texto" />
    </div>

<fieldset style="margin-top: 10px; width: 900px">
  <legend>
    <strong>
      Movimentos Encontrados
    </strong>
  </legend>
  <table border="0">
    <tr>
      <td>
        <div id='ctnGridConfiguracao'></div>
      </td>
    </tr>
  </table>
 <div style="margin-top: 10px; width: 100%;">
  <fieldset style="border-left: none; border-right: none; border-bottom: none;" >
    <legend><strong>Legenda</strong></legend>
    <label for="configuradas" style='padding:1px;border: 1px solid black; background-color:#d1f07c; float:left; '>
      <strong>Atualizados OBN</strong>
    </label>
    <label for="normais" style='margin-left: 10px; padding:1px;border: 1px solid black;background-color:white; float:left; '>
      <strong>Atualizados CNAB240</strong>
    </label>
  </fieldset>
 </div>
</fieldset>


</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>

<script>



var sUrlRPC              = "emp4_configuracaoarquivoenvio_natal.RPC.php";
var sArquivoMensagens    = "financeiro.caixa.emp4_configuracaoarquivoenvio001";

var iCodigoRecursoFundeb = null;
var oDBCodigoBarra       = null;

function js_criaCodigoBarra(){

      oDBCodigoBarra = new DBCodigoBarra("txtCodigoBarra", "oDBCodigoBarra");
      oDBCodigoBarra.setLabelCodigoBarra("C�digo de Barras:");
      oDBCodigoBarra.setMensagemLeitura('Aguardando leitura.');
      oDBCodigoBarra.criaComponentes();
      oDBCodigoBarra.setCallBackInicioLeitura(function() {

        oTxtValor.setValue('');
        oTxtData.setValue('');
        $('iTipoFatura').value = 1;
      });

      oDBCodigoBarra.setCallBackAposLeitura(function (oDados) {

        if (oDados == false) {

          $('iTipoFatura').value = '';
          $('txtValor').value    = '';
          return false;
        }

        oTxtValor.setValue(oDados.valor);
        if (oDados.data_pagamento != '') {

          var aData = oDados.data_pagamento.split('-');
          oTxtData.setData(aData[2], aData[1], aData[0]);
        }

        $('iTipoFatura').value = oDados.tipo;
        $('txtValor').focus();
        if (oDados.preencher_linha) {
          $('txtLinhaDigitavel').value = oDados.linha;
        }
      });

      oDBCodigoBarra.show('codigodebarras', 'linhadigitavel');
      $('btnCodigoBarra').style.display        = 'none';
}

$('btnGerarArquivoTXT').observe('click', function() {
  window.location = 'emp4_empageconfgera001.php?geraUnidade';
});

//================== setamos os tipos de fatura inicial ==================

function js_tipoFatura(iTipoFatura){

  switch (iTipoFatura) {

    case "1" :

      $("iTipoFatura").options.length = 0;
      $("iTipoFatura").options[0]     = new Option("Selecione"   , "0");
      $("iTipoFatura").options[1]     = new Option("Fatura"      , "1");
      $("iTipoFatura").options[2]     = new Option("Conv�nio"    , "2");
      $("iTipoFatura").options[3]     = new Option("GPS"         , "3");
      $("iTipoFatura").options[4]     = new Option("DARF"        , "4");
      $("iTipoFatura").options[5]     = new Option("DARF Simples", "5");

    break;

    case "2" :

      $("iTipoFatura").options.length = 0;
      $("iTipoFatura").options[0]     = new Option("Selecione"   , "0");
      $("iTipoFatura").options[1]     = new Option("Conv�nio"    , "2");
      $("iTipoFatura").options[2]     = new Option("Fatura"      , "1");
      $("iTipoFatura").options[3]     = new Option("GPS"         , "3");
      $("iTipoFatura").options[4]     = new Option("DARF"        , "4");
      $("iTipoFatura").options[5]     = new Option("DARF Simples", "5");

    break;

    default :

      $("iTipoFatura").options.length = 0;
      $("iTipoFatura").options[0]     = new Option("Selecione"   , "0");
      $("iTipoFatura").options[1]     = new Option("Fatura"      , "1");
      $("iTipoFatura").options[2]     = new Option("Conv�nio"    , "2");
      $("iTipoFatura").options[3]     = new Option("GPS"         , "3");
      $("iTipoFatura").options[4]     = new Option("DARF"        , "4");
      $("iTipoFatura").options[5]     = new Option("DARF Simples", "5");
    break;

  }

}


//================== Retorna Tipo de transmissao para o movimento selecionado ============//

function js_getTipoTransmissao(iMovimento, iCodigoRecurso) {

    var msgDiv      = "Buscando Dados <br>Aguarde ...";
    var oParametros = new Object();

    js_divCarregando(_M(sArquivoMensagens + ".buscando_tipo_transmissao"),'msgBox');

    oParametros.exec       = "getTipoTransmissao";
    oParametros.iMovimento = iMovimento;
    oParametros.iCodigoRecurso = iCodigoRecurso;

    new Ajax.Request(sUrlRPC,
                   {method: "post",
                    parameters:'json='+Object.toJSON(oParametros),
                    onComplete: js_retornoTipoTransmissao
                   });
}
function js_retornoTipoTransmissao(oAjax){

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == '2') {
    alert(oRetorno.sMessage.urlDecode());
    return false;
  }

  $("iTipoTransmissao").options.length = 0;

  oRetorno.aDados.each(function (oDado, iInd) {

    if (iInd == 0) {
      js_exibeCamposObn(oDado.e57_sequencial);
    }

    var oOption = new Option(oDado.e57_descricao.urlDecode(), oDado.e57_sequencial);
    if (iCodigoRecursoFundeb == oRetorno.iCodigoRecurso && oDado.e57_sequencial != 1) {

      oOption.selected = true;
      js_exibeCamposObn(oDado.e57_sequencial);
    }
    $("iTipoTransmissao").appendChild(oOption);


  });

}

//=========== define stilos para a forma de consulta...empenho, slip

function js_formaConsulta() {

  var iFomaconsulta = $F('iFomaconsulta');

  switch (iFomaconsulta) {

    case '0' :

      $('ctnEmpenho').style.display = "table-row";
      $('ctnSlip')   .style.display = "none";
    break;

    case '1' :

      $('ctnEmpenho').style.display = "none";
      $('ctnSlip')   .style.display = "table-row";
    break;
  }
}
js_formaConsulta();

//================== Pesquisar Registros ============//

function js_pesquisar(){

  var sRpcPesquisa   = "emp4_manutencaoPagamentoRPC.php";

  var dtInicial      = $F("datainicial");
  var dtFinal        = $F("datafinal");
  var iOrdemInicial  = $F('e82_codord');
  var iOrdemFinal    = $F('e82_codord02');
  var iSlipInicial   = $F('k17_slip');
  var iSlipFinal     = $F('k17_slip02');
  var iEmpenho       = $F('e60_codemp');
  var iCredor        = $F('z01_numcgm');
  var iRecurso       = $F('o15_codigo');
  var iFormaConsulta = $F('iFomaconsulta');

 // var msgDiv               = "Pesquisando Registros <br> Aguarde ...";

  var oParam               = new Object();
      oParam.lObn          = true;
      oParam.dtDataIni     = dtInicial;
      oParam.dtDataFim     = dtFinal;
      oParam.iNumCgm       = iCredor;
      oParam.iRecurso      = iRecurso;
  switch (iFormaConsulta) {

  case '0' :  // Empenho

    var sExec            = 'getMovimentos';
    oParam.iOrdemIni     = iOrdemInicial;
    oParam.iOrdemFim     = iOrdemFinal;
    oParam.iCodEmp       = iEmpenho;
    oParam.iOPauxiliar   = '';
    oParam.iAutorizadas  = '';
    oParam.iOPManutencao = '';
    oParam.orderBy       = '';
    oParam.lVinculadas   = false;
  break;

  case '1' :  // Slip

    var sExec        = 'getMovimentosSlip';
      oParam.iOrdemIni     = iSlipInicial;
      oParam.iOrdemFim     = iSlipFinal;
  break;

  }

  //js_divCarregando(msgDiv,'msgBox');

  js_divCarregando(_M(sArquivoMensagens + '.pesquisando_registro') , 'msgBox');

  oParam.lTratarMovimentosConfigurados = true;
  var sParam  = js_objectToJson(oParam);
  url         = 'emp4_manutencaoPagamentoRPC.php';
  var sJson   = '{"exec":"'+sExec+'","params":['+sParam+']}';
  var oAjax   = new Ajax.Request(
                         url,
                         {
                          method    : 'post',
                          parameters: 'json='+sJson,
                          onComplete: js_retornoPesquisa
                          }
                        );
}

var aValoresMovimentos = [];

function js_retornoPesquisa(oAjax){

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    oGridConfiguracao.clearAll(true);
    var iFormaPesquisa = $F('iFomaconsulta');

    switch (iFormaPesquisa) {

      case "0" : // Empenho

          if (oRetorno.aNotasLiquidacao.length > 0) {

              oRetorno.aNotasLiquidacao.each(function (oDado, iInd) {

                var aRow   = new Array();
                var faturaAnexo = (oDado.fatura == "t") ? "Sim" : "N�o";
        
                aRow[0] = oDado.e81_codmov; // movimento
                aRow[1] = oDado.e60_codemp + "/" + oDado.e60_anousu; // empenho
                aRow[2] = oDado.o15_codigo ; // recurso

                /**
                 * Busca pela conta configurada na agenda
                 */
                for (var i = 0; i < oDado.aContasVinculadas.length; i++) {

                  if (oDado.e85_codtipo == oDado.aContasVinculadas[i].e83_codtipo) {

                    var sCodigoConta    = oDado.aContasVinculadas[i].e83_conta;
                    var sDescricaoConta = oDado.aContasVinculadas[i].e83_descr.urlDecode();
                    break;
                  }
                }

                aValoresMovimentos[oDado.e81_codmov] = oDado.e81_valor;
                aRow[3] = sCodigoConta + " - " + sDescricaoConta ; // conta pagadora
                aRow[4] = oDado.z01_numcgm  + " - " + oDado.z01_nome.urlDecode(); // credor
                aRow[5] = js_formatar(oDado.e81_valor, "f"); // valor
                aRow[6] = (oDado.fatura == "t") ? "Sim" : "N�o";
                aRow[7] = "<input type='button' value='Editar' onclick='js_criaJanelaDetalhes("+oDado.e81_codmov+", "+oDado.o15_codigo+");'  ";
        
                oGridConfiguracao.addRow(aRow);
                if (oDado.e25_empagetipotransmissao == '2') {
                  oGridConfiguracao.aRows[iInd].setClassName('configurada');
                }
              });
              oGridConfiguracao.renderRows();
            }

      break;


      case "1" : // Slip

        if (oRetorno.aSlips.length > 0) {

            oRetorno.aSlips.each(function (oDado, iInd) {

              var aRow   = new Array();

              aRow[0] = oDado.e81_codmov;
              aRow[1] = oDado.k17_codigo ;
              aRow[2] = oDado.c61_codigo ;
              aRow[3] = oDado.k17_credito + " - " + oDado.e83_descr.urlDecode() ;
              aRow[4] = oDado.z01_numcgm  + " - " + oDado.z01_nome.urlDecode();
              aRow[5] = js_formatar(oDado.k17_valor, "f");
              
              aRow[6] = (oDado.fatura == "t") ? "Sim" : "N�o";
              aRow[7] = "<input type='button' value='Editar' onclick='js_criaJanelaDetalhes("+oDado.e81_codmov+", "+oDado.c61_codigo+");'  ";
         
              aValoresMovimentos[oDado.e81_codmov] = oDado.k17_valor;
        
              oGridConfiguracao.addRow(aRow);
              if (oDado.e25_empagetipotransmissao == '2') {
                oGridConfiguracao.aRows[iInd].setClassName('configurada');
              }
            });

            oGridConfiguracao.renderRows();

          }

      break;

    }
}


//================== Retorna Detalhes configurados para o movimento selecionado ============//

function js_getDetalhes(iMovimento) {

   // var msgDiv      = "Buscando Registros <br> Aguarde ...";
    var oParametros = new Object();
    $('TotalForCol2').innerHTML = '0,00';

    js_divCarregando(_M("financeiro.caixa.emp4_configuracaoarquivoenvio001.buscando_detalhes"),'msgBox');


    oParametros.exec       = "getDetalhes";
    oParametros.iMovimento = iMovimento;
    new Ajax.Request(sUrlRPC,
                    {method: "post",
                     parameters:'json='+Object.toJSON(oParametros),
                     onComplete: js_retornoGetDetalhes
                    });
}

function js_retornoGetDetalhes(oAjax){

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    oGridConfiguracaoDetalhe.clearAll(true);
    var nValorTotal = 0;
    if (oRetorno.aDados.length > 0) {

        oRetorno.aDados.each(function (oDado, iInd) {
            var aRow  = [];
              aRow[0] = oDado.e74_codigodebarra ;
              aRow[1] = js_formatar(oDado.e74_valornominal, "f")  ;
              aRow[2] = js_formatar(oDado.e74_valorjuros, "f")    ;
              aRow[3] = js_formatar(oDado.e74_valordesconto, "f") ;
              aRow[4] = js_formatar(oDado.e74_datavencimento,'d');
              aRow[5] = oDado.sFatura.urlDecode();
              aRow[6] = oDado.e74_linhadigitavel;
              aRow[8]  = '-';
              aRow[9]  = '-';
              aRow[10] = '-';
              aRow[11] = '-';
              aRow[12] = '-';
              aRow[13] = '-';
              aRow[14] = '-';
              aRow[15] = '-';
              aRow[16] = '-';
              aRow[17] = '-';
              aRow[18] = '-';

              oGridConfiguracaoDetalhe.addRow(aRow);
              nValorTotal += new Number(oDado.e74_valornominal).valueOf();
          });
        oGridConfiguracaoDetalhe.renderRows();
    }

    if (oRetorno.aDadosPagamento.length > 0) {
  
        oRetorno.aDadosPagamento.each(function (oDadoPagamento, iInd) {

            var aRow   = [];
              aRow[0]  = "-" ;
              aRow[1]  = js_formatar(oDadoPagamento.valorprincipal, "f");
              aRow[2]  = "-" ;
              aRow[3]  = "-" ;
              aRow[4]  = js_formatar(oDadoPagamento.datavencimento,'d');
              aRow[5]  = oDadoPagamento.sFatura.urlDecode();
              aRow[6]  = "-";
              aRow[7]  = oDadoPagamento.codreceita; 
              aRow[8]  = oDadoPagamento.codidentificacao;
              aRow[9]  = oDadoPagamento.numreferencia;
              aRow[10] = oDadoPagamento.mesanocompetencia;
              aRow[11] = js_formatar(oDadoPagamento.periodoapuracao, 'd');
              aRow[12] = js_formatar(oDadoPagamento.valorINSS, "f");
              aRow[13] = js_formatar(oDadoPagamento.valoroutras, "f");
              aRow[14] = js_formatar(oDadoPagamento.atualizacaomonetaria, "f");
              aRow[15] = js_formatar(oDadoPagamento.valorreceitabruta, "f");
              aRow[16] = js_formatar(oDadoPagamento.valormulta, "f");
              aRow[17] = oDadoPagamento.percentualreceita;
              aRow[18] = js_formatar(oDadoPagamento.jurosencargos, "f");
              oGridConfiguracaoDetalhe.addRow(aRow);
              nValorTotal += new Number(oDadoPagamento.valorprincipal).valueOf();
          });
        oGridConfiguracaoDetalhe.renderRows();
    }
    $('TotalForCol2').innerHTML = js_formatar(nValorTotal, 'f');



}

//================== Persiste os Detalhes no Banco ============//

function js_salvarDetalhes(iMovimento){
    var nTotalMovimentos = js_formatar(aValoresMovimentos[iMovimento], 'f');
    var nTotalLancamentos = 0;
alert(nTotalMovimentos);
    var iTipoTransmissao             = $F('iTipoTransmissao');
    //var msgDiv                       = "Salvando Registros <br> Aguarde ...";
    var oParametros                  = new Object();
        oParametros.exec             = 'salvarDetalhes';
        oParametros.iMovimento       = iMovimento;
        oParametros.iTipoTransmissao = iTipoTransmissao;
        oParametros.fatura        = $F('iFatura');
        oParametros.aDetalhes        = new Array();

    if (iTipoTransmissao == "") {

      alert('Selecione um tipo de Transmiss�o.');
      return false;
    }

    if (iTipoTransmissao == "1") {
      oGridConfiguracaoDetalhe.clearAll(true);
    }

    oGridConfiguracaoDetalhe.aRows.each(function (oRow, iIndice) {

      var oDetalhes                   = new Object();
      oDetalhes.iCodigoBarras         = oRow.aCells[1].getValue() === '' ? null : oRow.aCells[1].getValue();
      oDetalhes.nValor                = oRow.aCells[2].getValue();
      oDetalhes.nJuros                = oRow.aCells[3].getValue();
      oDetalhes.nDesconto             = oRow.aCells[4].getValue();
      oDetalhes.dtData                = oRow.aCells[5].getValue();
      oDetalhes.iFatura               = oRow.aCells[6].getValue();
      oDetalhes.iLinhaDigitavel       = oRow.aCells[7].getValue();
      oDetalhes.iCodReceita           = oRow.aCells[8].getValue(); 
      oDetalhes.iCodIdent             = oRow.aCells[9].getValue();
      oDetalhes.iNumReferencia        = oRow.aCells[10].getValue();
      oDetalhes.sMesAnoCompetencia    = oRow.aCells[11].getValue();
      oDetalhes.dtPeriodoApuracao     = oRow.aCells[12].getValue();
      oDetalhes.nValorINSS            = oRow.aCells[13].getValue();
      oDetalhes.nValorOutras          = oRow.aCells[14].getValue();
      oDetalhes.nAtualizacaoMonetaria = oRow.aCells[15].getValue();
      oDetalhes.nValorReceitaBruta    = oRow.aCells[16].getValue();
      oDetalhes.nValorMulta           = oRow.aCells[17].getValue();
      oDetalhes.nPercentualReceita    = oRow.aCells[18].getValue();
      oDetalhes.nJurosEncargos        = oRow.aCells[19].getValue();

      oParametros.aDetalhes.push(oDetalhes);
      nTotalLancamentos += js_formatar(oRow.aCells[2].getValue(), 'f');
    });
  
    /**
     * Movimento do tipo OBN
     *  - valida valor total lancado, deve ser igual ao do movimento
     */
    if (iTipoTransmissao == 2 || iTipoTransmissao == 3 && nTotalLancamentos > 0 && nTotalLancamentos != nTotalMovimentos) {
      return alert('Valor total dos lan�amentos deve ser igual ao do movimento: ' + js_formatar(nTotalMovimentos, 'f'));
    }

    //js_divCarregando(msgDiv,'msgBox');
    js_divCarregando(_M(sArquivoMensagens + ".salvando_detalhes"), 'msgBox');

    new Ajax.Request(sUrlRPC,
                            {method: "post",
                             parameters:'json='+Object.toJSON(oParametros),
                             onComplete: js_retornoSalvarDetalhes
                            });
}

function js_retornoSalvarDetalhes(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    js_getDetalhes(oRetorno.iMovimento);

    alert(oRetorno.sMessage.urlDecode());
}


//================== Adicionar linha de detalhe NA GRID - NAO SALVA NADA NO BANCO AQUI ============//

function js_incluirDetalhe(iMovimento){

  var aRow         = new Array();
  var oDetalhes    = new Object();

  var iCodigoBarra          = $F('txtCodigoBarra');
  var iLinhaDigitavel       = $F('txtLinhaDigitavel').replace(/[^0-9]/g, '');
  var nValor                = $F('txtValor');
  var nJuros                = $F('txtJuros');
  var nDesconto             = $F('txtDesconto');
  var dtData                = $F('txtData');
  var iFatura               = $F('iTipoFatura');
  var sFatura               = "Fatura";
  var iCodReceita           = $F('txtCodReceita');
  var iCodIdent             = $F('txtCodIdent');
  var iNumReferencia        = $F('txtNumReferencia');
  var sMesAnoCompetencia    = $F('txtMesCompetencia')+'/'+$F('txtAnoCompetencia');
  var dtPeriodoApuracao     = $F('txtPeriodoApuracao');
  var nValorINSS            = $F('txtValorINSS');
  var nValorOutras          = $F('txtValorOutras');
  var nAtualizacaoMonetaria = $F('txtAtualizacaoMonetaria');
  var nValorReceitaBruta    = $F('txtValorReceitaBruta');
  var nValorMulta           = $F('txtValorMulta');
  var nPercentualReceita    = $F('txtPercentualReceita');
  var nJurosEncargos        = $F('txtJurosEncargos');

  if (iFatura == '2') {
    sFatura = "Conv�nio";
  } else if (iFatura == '3') {
    sFatura = "GPS";
  } else if (iFatura == '4') {
    sFatura = "DARF";
  } else if (iFatura == '5') {
    sFatura = "DARF Simples";
  }

  if (iFatura == '1' || iFatura == '2') {
    
    if (iCodigoBarra == '') {

      alert('Obrigat�rio preenchimento do c�digo de barras.');
      //$('txtCodigoBarra').focus();
      return false;
    }

    if (iCodigoBarra.length > 44) {

      alert('O c�digo de barras deve ser no padr�o de 44 posi��es');
      return false;
    }

    var lCodigoBarraDuplicado = false;
    oGridConfiguracaoDetalhe.aRows.each(function(oRows) {

      if (iCodigoBarra == oRows.aCells[1].getValue()) {

        lCodigoBarraDuplicado = true;
        return false;
      }
    });

    if (lCodigoBarraDuplicado) {

      alert ('O c�digo de barras "'+iCodigoBarra+'" j� foi lan�ado.');
      return false;
    }
  } else {

    if (iCodReceita == '') {

      alert('Obrigat�rio preenchimento do campo do C�digo da Receita do Tributo.');
      $('txtCodReceita').focus();
      return false;
    }
    if (iCodIdent == '') {
      
      alert('Obrigat�rio preenchimento do campo do C�digo de Identifica��o do Tributo.');
      $('txtCodIdent').focus();
      return false;
    }
  }

  if (nValor == '' && (iFatura == '1' || iFatura == '2' || iFatura == '4' || iFatura == '5')) {

    alert('Obrigat�rio preenchimento do valor.');
    return false;
  }

  if (nJuros == '') {
    nJuros = "0";
  }

  if (nDesconto == '') {
    nDesconto = "0";
  }

  if (nValorINSS == '') {
    nValorINSS = "0";
  }

  if (nValorOutras == '') {
    nValorOutras = "0";
  }

  if (nValorMulta == '') {
    nValorMulta = "0";
  }

  if (nValorReceitaBruta == '') {
    nValorReceitaBruta = "0";
  }

  if (nPercentualReceita == '') {
    nPercentualReceita = "0";
  }

  if (nJurosEncargos == '') {
    nJurosEncargos = "0";
  }

  if (nAtualizacaoMonetaria == '') {
    nAtualizacaoMonetaria = "0";
  }

  if (iCodReceita == '') {
    iCodReceita = null;
  }

  if (iCodIdent == '') {
    iCodIdent = null;
  }

  if (iNumReferencia == '') {
    iNumReferencia = null;
  }

  if (dtData == '' && iFatura != '3') {

    alert('Obrigat�rio preenchimento da data.');
    return false;
  } else if (iFatura == '3') {

    dtData = null;
  }

  if (dtPeriodoApuracao == '') {

    if (iFatura == '4' || iFatura == '5') {

      alert('Obrigat�rio preenchimento do per�odo de apura��o.');
      return false;
    } else {

      dtPeriodoApuracao = null;
    }
  }

  aRow[0]  = iCodigoBarra;
  aRow[1]  = js_formatar(nValor, "f");
  aRow[2]  = js_formatar(nJuros, "f");
  aRow[3]  = js_formatar(nDesconto, "f");
  aRow[4]  = (dtData == null) ? '-' : dtData;
  aRow[5]  = sFatura;
  aRow[6]  = iLinhaDigitavel;
  aRow[7]  = iCodReceita; 
  aRow[8]  = iCodIdent;
  aRow[9]  = iNumReferencia;
  aRow[10] = sMesAnoCompetencia;
  aRow[11] = dtPeriodoApuracao;
  aRow[12] = js_formatar(nValorINSS, "f");
  aRow[13] = js_formatar(nValorOutras, "f");
  aRow[14] = js_formatar(nAtualizacaoMonetaria, "f");
  aRow[15] = js_formatar(nValorReceitaBruta, "f");
  aRow[16] = js_formatar(nValorMulta, "f");
  aRow[17] = js_formatar(nPercentualReceita, "f");
  aRow[18] = js_formatar(nJurosEncargos, "f");

  oGridConfiguracaoDetalhe.addRow(aRow);
  oGridConfiguracaoDetalhe.renderRows();
  $('TotalForCol2').innerHTML = js_formatar(js_strToFloat($('TotalForCol2').innerHTML) +  Number(nValor), 'f');

  $('txtCodigoBarra').value          = '';
  $('txtValor').value                = '';
  $('txtJuros').value                = '';
  $('txtDesconto').value             = '';
  $('txtData').value                 = '';
  $('txtLinhaDigitavel').value       = '';
  $('txtCodIdent').value             = '';
  $('txtCodReceita').value           = '';
  $('txtMesCompetencia').value       = '';
  $('txtAnoCompetencia').value       = '';
  $('txtValorINSS').value            = '';
  $('txtValorOutras').value          = '';
  $('txtAtualizacaoMonetaria').value = '';
  $('txtValorReceitaBruta').value    = '';
  $('txtValorMulta').value           = '';
  $('txtJurosEncargos').value        = '';
  $('txtPeriodoApuracao').value      = '';
  $('txtPercentualReceita').value    = '';
  $('txtNumReferencia').value        = '';
  $('txtCodigoBarra').focus();

  $('iTipoFatura').disabled = true;
}

//================== Excluir Detalhes ============//
function js_removerDetalhes(iMovimento){

  var aListaCheckbox         = oGridConfiguracaoDetalhe.getSelection('object');

  if (aListaCheckbox.length == 0) {
    alert('Selecione um registro para excluir.');
    return false;
  }

  var nValorParaSubtrair = 0;
  var aLinhasRemover = [];
  aListaCheckbox.each(
    function ( oRow, iSeq ) {
      nValorParaSubtrair += js_strToFloat(oRow.aCells[2].getValue());
      aLinhasRemover.push(oRow.getRowNumber());
    }
  );

  var sMensagemExclusao = 'Excluir selecionados?';
  sMensagemExclusao += '\nPara confirmar opera��o, � necess�rio clicar no bot�o "Salvar".';

  if (!confirm(sMensagemExclusao)) {
    return false;
  }
  oGridConfiguracaoDetalhe.removeRow(aLinhasRemover);
  oGridConfiguracaoDetalhe.renderizar();
  $('TotalForCol2').innerHTML = js_formatar(oGridConfiguracaoDetalhe.sum(2, false), 'f');
}

function js_retornoExcluirDetalhes(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  js_getDetalhes(oRetorno.iMovimento);
  alert(oRetorno.sMessage.urlDecode());
}

document.onkeydown =  function(Event) {

  /**
   * F6
   */
  if (Event.which == 117) {

    if ($('windowDetalhes')) {
      oDBCodigoBarra.liberarCodigoDeBarra();
    }
    Event.preventDefault();
    Event.stopPropagation();
  }
};

//===================== Grid que conter� os movimentos a serem configurados ==========//

function js_criaGridConfiguracao() {

  oGridConfiguracao = new DBGrid('oGridConfiguracao');
  oGridConfiguracao.nameInstance = 'oGridConfiguracao';

  oGridConfiguracao.setCellWidth(new Array( '100px' ,
                                            '100px',
                                            '100px',
                                            '200px',
                                            '200px',
                                            '100px',
                                            '100px',
                                            '80px'
                                           ));
  oGridConfiguracao.setCellAlign(new Array( 'left'  ,
                                            'left'  ,
                                            'left',
                                            'left',
                                            'left',
                                            'right',
                                            'center',
                                            'center'
                                           ));
  oGridConfiguracao.setHeader(new Array( 'C�d.Mov',
                                         'Emp. / Slip',
                                         'Recurso',
                                         'Cta. Pagadora',
                                         'Nome',
                                         'Valor',
                                         'Fatura Anexo',
                                         'A��o'
                                        ));
  
  oGridConfiguracao.setHeight(150);
  oGridConfiguracao.show($('ctnGridConfiguracao'));
  oGridConfiguracao.clearAll(true);
}


js_criaGridConfiguracao();

//===================== chamada de fun�oes da janela de detalhes ==========//
function js_criaJanelaDetalhes(iCodMov, iCodigoRecurso) {

  if ( $('sTituloWindow') &&  $('sTituloWindow').innerHTML != '' ) {
    $('sTituloWindow').innerHTML = '';
  }

  js_viewConfiguracao(iCodMov);
  js_criaGridDetalhes();
  js_getDetalhes(iCodMov);
  js_getTipoTransmissao(iCodMov, iCodigoRecurso);
  js_criaCodigoBarra();

}

//===================== Grid que conter� os detalhes do movimento ==========//

function js_criaGridDetalhes() {

    oGridConfiguracaoDetalhe = new DBGrid('oGridConfiguracaoDetalhe');
    oGridConfiguracaoDetalhe.nameInstance = 'oGridConfiguracaoDetalhe';
    oGridConfiguracaoDetalhe.setCheckbox(0);
    oGridConfiguracaoDetalhe.setCellWidth(['240px',
                                           '100px',
                                           ' 70px',
                                           ' 70px',
                                           ' 60px',
                                           ' 70px',
                                           ' 60px',
                                           ' 60px',
                                           '  0px',
                                           '  0px',
                                           '  0px',
                                           '  0px',
                                           '  0px',
                                           '  0px',
                                           '  0px',
                                           '  0px',
                                           '  0px',
                                           '  0px',
                                           '  0px'
                                             ]);
    oGridConfiguracaoDetalhe.setCellAlign(['left',
                                           'right',
                                           'right',
                                           'right',
                                           'center',
                                           'left',
                                           'center',
                                           'center',
                                           'center',
                                           'center',
                                           'center',
                                           'center',
                                           'center',
                                           'center',
                                           'center',
                                           'center',
                                           'center',
                                           'center',
                                           'center']);
    oGridConfiguracaoDetalhe.setHeader(['C�digo de Barras',    
                                        'Valor',               
                                        'Juros',               
                                        'Desconto',            
                                        'Data',                
                                        'Tipo de Fatura',      
                                        'Linha Digit�vel',     
                                        'C�d. Receita',        
                                        'C�d. Ident.',         
                                        'NumReferencia',       
                                        'MesAnoCompetencia',   
                                        'PeriodoApuracao',     
                                        'ValorINSS',           
                                        'ValorOutras',         
                                        'AtualizacaoMonetaria',
                                        'ValorReceitaBruta',   
                                        'ValorMulta',          
                                        'PercentualReceita',   
                                        'JurosEncargos']);     
    oGridConfiguracaoDetalhe.hasTotalizador = true;
    oGridConfiguracaoDetalhe.setHeight(150);

    oGridConfiguracaoDetalhe.aHeaders[3].lDisplayed  = false;
    oGridConfiguracaoDetalhe.aHeaders[4].lDisplayed  = false;
    oGridConfiguracaoDetalhe.aHeaders[7].lDisplayed  = false;
    oGridConfiguracaoDetalhe.aHeaders[10].lDisplayed = false;
    oGridConfiguracaoDetalhe.aHeaders[11].lDisplayed = false;
    oGridConfiguracaoDetalhe.aHeaders[12].lDisplayed = false;
    oGridConfiguracaoDetalhe.aHeaders[13].lDisplayed = false;
    oGridConfiguracaoDetalhe.aHeaders[14].lDisplayed = false;
    oGridConfiguracaoDetalhe.aHeaders[15].lDisplayed = false;
    oGridConfiguracaoDetalhe.aHeaders[16].lDisplayed = false;
    oGridConfiguracaoDetalhe.aHeaders[17].lDisplayed = false;
    oGridConfiguracaoDetalhe.aHeaders[18].lDisplayed = false;
    oGridConfiguracaoDetalhe.aHeaders[19].lDisplayed = false;

    oGridConfiguracaoDetalhe.show($('ctnGridConfiguracaoDetalhes'));
    oGridConfiguracaoDetalhe.clearAll(true);
}

//================== Janela para Configurar detalhes ============//
function js_viewConfiguracao (iCodMov) {

    var iMovimento     = iCodMov;
    var iLarguraJanela = 870;
    var iAlturaJanela  = 650;

    if (typeof(windowDetalhes) != 'undefined' && windowDetalhes instanceof windowAux) {
      windowDetalhes.destroy();
    }

    windowDetalhes   = new windowAux( 'windowDetalhes',
                                      'Configura��o do Movimento',
                                      iLarguraJanela,
                                      iAlturaJanela
                                      );
    

    var sConteudoDetalhes  = "<div>";
        sConteudoDetalhes += "<div id='sTituloWindow'></div> "; // container do message box

        sConteudoDetalhes += "  <center>  <br>";
        sConteudoDetalhes += "  <fieldset style='width: 95%;'><legend><strong> Configura��o </strong></legend>";
      sConteudoDetalhes += "     <table border = 0 align='left'>  ";

      sConteudoDetalhes += "      <tr nowrap>     ";
      sConteudoDetalhes += "        <td style='width:130px'>   ";
      sConteudoDetalhes += "         <strong>C�digo do Movimento: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "         <span>" + iMovimento + "</span>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr nowrap>     ";
      sConteudoDetalhes += "        <td>   ";
      sConteudoDetalhes += "         <strong>Tipo de Transmiss�o: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "         <label id='ctnTipoTransmissao' style='float:left;'>";
      sConteudoDetalhes += "           <select id='iTipoTransmissao' name='iTipoTransmissao' onChange='js_exibeCamposObn(this.value)' style='width:100px;'>";
      sConteudoDetalhes += "           </select>";
      sConteudoDetalhes += "         </label>";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Tipo de Fatura: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "           <select id='iTipoFatura' name='iTipoFatura' onChange='js_camposTipoFatura(this.value)' style='width:100px;' >";
      sConteudoDetalhes += "             <option value='0'>Selecione</option>           ";
      sConteudoDetalhes += "             <option value='1'>Fatura</option>           ";
      sConteudoDetalhes += "             <option value='2'>Conv�nio</option>         ";
      //sConteudoDetalhes += "             <option value='3'>GPS</option>         ";
      //sConteudoDetalhes += "             <option value='4'>DARF</option>         ";
      //sConteudoDetalhes += "             <option value='5'>DARF Simples</option>         ";
      sConteudoDetalhes += "           </select>";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trCodRecIdent' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> C�d. Receita Tributo: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td>  ";
      sConteudoDetalhes += "          <table>  ";
      sConteudoDetalhes += "            <tr nowrap>  ";
      sConteudoDetalhes += "              <td align='left'>   ";
      sConteudoDetalhes += "                <label id='ctnCodReceita' style='float:left;'></label>  ";
      sConteudoDetalhes += "              </td>   ";
      sConteudoDetalhes += "              <td >   ";
      sConteudoDetalhes += "               <strong> C�d. Ident. Tributo: </strong>  ";
      sConteudoDetalhes += "              </td>  ";
      sConteudoDetalhes += "              <td align='left'>   ";
      sConteudoDetalhes += "                <label id='ctnCodIdent' style='float:left;'></label>  ";
      sConteudoDetalhes += "              </td>   ";
      sConteudoDetalhes += "             </tr>  ";
      sConteudoDetalhes += "          </table>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "     <tr id='trMesAnoCompetencia' nowrap>";
      sConteudoDetalhes += "      <td >   ";
      sConteudoDetalhes += "       <strong> M�s/Ano Compet�ncia: </strong>  ";
      sConteudoDetalhes += "      </td>  ";
      sConteudoDetalhes += "      <td align='left'>   ";
      sConteudoDetalhes += "        <label id='ctnMesCompetencia' style='float:left;'></label>  ";
      sConteudoDetalhes += "        <label id='ctnAnoCompetencia' style='float:left;'></label>  ";
      sConteudoDetalhes += "      </td>  ";
      sConteudoDetalhes += "     </tr>";

      sConteudoDetalhes += "      <tr id='trNumReferencia' nowrap>    ";
      sConteudoDetalhes += "        <td>    ";
      sConteudoDetalhes += "          <strong> Num. Refer�ncia: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnNumReferencia' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr nowrap id='linhadigitavel'>     ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr nowrap id='codigodebarras'>     ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trValor' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "          <strong> Valor Principal: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnValor' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trJuros' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Juros: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnJuros' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trDesconto' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Desconto: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnDesconto' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trValorINSS' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Valor INSS: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnValorINSS' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trValorOutras' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Valor Outras: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnValorOutras' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trAtualizacaoMonetaria' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Atualiza��o Monet�ria: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnAtualizacaoMonetaria' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trReceitaBruta' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Valor Receita Bruta: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnReceitaBruta' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trValorMulta' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Valor Multa: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnValorMulta' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trJurosEncargos' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Valor Juros/Encargos: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnJurosEncargos' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trPercentualReceita' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Percentual Receita: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnPercentualReceita' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trPeriodoApuracao' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Per�odo Apura��o: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnPeriodoApuracao' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr id='trData' nowrap>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Data de Vencimento: </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "          <label id='ctnData' style='float:left;'></label>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";

      sConteudoDetalhes += "      <tr nowrap id='trFaturaAnexo'>     ";
      sConteudoDetalhes += "        <td >   ";
      sConteudoDetalhes += "         <strong> Com Fatura Anexo? </strong>  ";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "        <td align='left'>   ";
      sConteudoDetalhes += "           <select id='iFatura' name='iFatura' style='width:100px;' >";
      sConteudoDetalhes += "             <option value='f'>N�o</option>           ";
      sConteudoDetalhes += "             <option value='t'>Sim</option>         ";
      sConteudoDetalhes += "           </select>";
      sConteudoDetalhes += "        </td>  ";
      sConteudoDetalhes += "      </tr>    ";
      sConteudoDetalhes += " </table> </fieldset>";

       sConteudoDetalhes += "<div style='margin-top:10px;'>";
       sConteudoDetalhes += " <input type='button' value='Adicionar' id = 'incluir' onclick='js_incluirDetalhe(" + iMovimento + ")'/>";
       sConteudoDetalhes += "</div>"  ;

       sConteudoDetalhes += "<fieldset style='width:95%;'>";
       sConteudoDetalhes += "<legend><strong>Dados Configurados para o Movimento</strong></legend>";
       sConteudoDetalhes += "  <div style='margin-top:10px;'>";
       sConteudoDetalhes += "    <div id='ctnGridConfiguracaoDetalhes'> </div>";
       sConteudoDetalhes += "  </div>"  ;
       sConteudoDetalhes += "</fieldset>"  ;

       sConteudoDetalhes += "<div style='margin-top:10px;'>";
       sConteudoDetalhes += " <input type='button' value='Salvar' id = 'salvar' onclick='js_salvarDetalhes(" + iMovimento + ");' />";
       sConteudoDetalhes += " <input type='button' value='Excluir Selecionados' id = 'excluir' onclick='js_removerDetalhes("+iMovimento+");' />";
       sConteudoDetalhes += "</div>"  ;

      sConteudoDetalhes += "  </center> ";

      sConteudoDetalhes += "</div>";

     windowDetalhes.setContent(sConteudoDetalhes);
     windowDetalhes.allowCloseWithEsc(false);


     //============  MESAGE BORD PARA TITULO da JANELA
    var sTextoMessageBoard   = 'Detalhes do movimento a serem enviados no arquivo.';
        messageBoard         = new DBMessageBoard('msgboard1', 'Caracter�sticas do Movimento.', sTextoMessageBoard, $('sTituloWindow'));

      //funcao para corrigir a exibi��o do window aux, apos fechar a primeira vez

      windowDetalhes.setShutDownFunction(function () {

        windowDetalhes.destroy();
        js_pesquisar();
        delete windowDetalhes;
      });

     windowDetalhes.show();
     messageBoard.show();

  oTxtCodIdent             = new DBTextField('txtCodIdent','oTxtCodIdent', null, 10);
  oTxtCodReceita           = new DBTextField('txtCodReceita','oTxtCodReceita', null, 10);
  oTxtMesCompetencia       = new DBTextField('txtMesCompetencia','oTxtMesCompetencia', null, 2);
  oTxtAnoCompetencia       = new DBTextField('txtAnoCompetencia','oTxtAnoCompetencia', null, 4);
  oTxtValorINSS            = new DBTextField('txtValorINSS','oTxtValorINSS', null, 10);
  oTxtValorOutras          = new DBTextField('txtValorOutras','oTxtValorOutras', null, 10);
  oTxtAtualizacaoMonetaria = new DBTextField('txtAtualizacaoMonetaria','oTxtAtualizacaoMonetaria', null, 10);
  oTxtValorReceitaBruta    = new DBTextField('txtValorReceitaBruta','oTxtValorReceitaBruta', null, 10);
  oTxtValorMulta           = new DBTextField('txtValorMulta','oTxtValorMulta', null, 10);
  oTxtPercentualReceita    = new DBTextField('txtPercentualReceita','oTxtPercentualReceita', null, 10);
  oTxtJurosEncargos        = new DBTextField('txtJurosEncargos','oTxtJurosEncargos', null, 10);
  oTxtPeriodoApuracao      = new DBTextFieldData('txtPeriodoApuracao','oTxtPeriodoApuracao', null, 10);
  oTxtNumReferencia        = new DBTextField('txtNumReferencia','oTxtNumReferencia', null, 20);
  oTxtValor        = new DBTextField('txtValor','oTxtValor', null, 10);
  oTxtData         = new DBTextFieldData('txtData','oTxtData', null);
  oTxtJuros        = new DBTextField('txtJuros','oTxtJuros', null, 10);
  oTxtDesconto     = new DBTextField('txtDesconto','oTxtDesconto', null ,10);

  oTxtCodIdent.setMaxLength(2);
  oTxtCodReceita.setMaxLength(6);
  oTxtMesCompetencia.setMaxLength(2);
  oTxtAnoCompetencia.setMaxLength(4);

  oTxtCodIdent            .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtCodReceita          .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtMesCompetencia      .addEvent("onKeyUp", "$('txtAnoCompetencia').focus();");
  oTxtMesCompetencia      .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtAnoCompetencia      .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtValorINSS           .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtValorOutras         .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtAtualizacaoMonetaria.addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtValorReceitaBruta   .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtValorMulta          .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtPercentualReceita   .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtJurosEncargos       .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtNumReferencia       .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtValor      .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtJuros      .addEvent("onKeyPress", "return js_teclas(event,this)");
  oTxtDesconto   .addEvent("onKeyPress", "return js_teclas(event,this)");

  oTxtCodIdent            .show($('ctnCodIdent'));
  oTxtCodReceita          .show($('ctnCodReceita'));
  oTxtMesCompetencia      .show($('ctnMesCompetencia'));
  oTxtAnoCompetencia      .show($('ctnAnoCompetencia'));
  oTxtValorINSS           .show($('ctnValorINSS'));
  oTxtValorOutras         .show($('ctnValorOutras'));
  oTxtAtualizacaoMonetaria.show($('ctnAtualizacaoMonetaria'));
  oTxtValorReceitaBruta   .show($('ctnReceitaBruta'));
  oTxtValorMulta          .show($('ctnValorMulta'));
  oTxtPercentualReceita   .show($('ctnPercentualReceita'));
  oTxtJurosEncargos       .show($('ctnJurosEncargos'));
  oTxtPeriodoApuracao     .show($('ctnPeriodoApuracao')); 
  oTxtNumReferencia       .show($('ctnNumReferencia'));
  oTxtValor      .show($('ctnValor'));
  oTxtData       .show($('ctnData'));
  oTxtJuros      .show($('ctnJuros'));
  oTxtDesconto   .show($('ctnDesconto'));

  js_camposTipoFatura('0');
}

function js_camposTipoFatura(tipoFatura){

  $('trCodRecIdent').style.display          = 'none';
  $('trMesAnoCompetencia').style.display    = 'none';
  $('trValor').style.display                = 'none';
  $('linhadigitavel').style.display         = 'none';
  $('codigodebarras').style.display         = 'none';
  $('trJuros').style.display                = 'none';
  $('trDesconto').style.display             = 'none';
  $('trData').style.display                 = 'none';
  $('trValorINSS').style.display            = 'none';
  $('trValorOutras').style.display          = 'none';
  $('trAtualizacaoMonetaria').style.display = 'none';
  $('trReceitaBruta').style.display         = 'none';
  $('trValorMulta').style.display           = 'none';
  $('trPercentualReceita').style.display    = 'none';
  $('trJurosEncargos').style.display        = 'none';
  $('trPeriodoApuracao').style.display      = 'none';
  $('trNumReferencia').style.display        = 'none';
  $('trFaturaAnexo').style.display          = 'none';
  
  switch(tipoFatura) {

    case '0':
      $('trCodRecIdent').style.display          = 'none';
      $('trMesAnoCompetencia').style.display    = 'none';
      $('trValor').style.display                = 'none';
      $('linhadigitavel').style.display         = 'none';
      $('codigodebarras').style.display         = 'none';
      $('trJuros').style.display                = 'none';
      $('trDesconto').style.display             = 'none';
      $('trData').style.display                 = 'none';
      $('trValorINSS').style.display            = 'none';
      $('trValorOutras').style.display          = 'none';
      $('trAtualizacaoMonetaria').style.display = 'none';
      $('trReceitaBruta').style.display         = 'none';
      $('trValorMulta').style.display           = 'none';
      $('trPercentualReceita').style.display    = 'none';
      $('trJurosEncargos').style.display        = 'none';
      $('trPeriodoApuracao').style.display      = 'none';
      $('trNumReferencia').style.display        = 'none';
      $('trFaturaAnexo').style.display          = 'table-row';
    break;

    case '1':
    case '2':

      $('linhadigitavel').style.display         = 'table-row';
      $('codigodebarras').style.display         = 'table-row';
      $('trData').style.display                 = 'table-row';
      $('trValor').style.display                = 'table-row';
      $('trDesconto').style.display             = 'table-row';
      $('trJuros').style.display                = 'table-row';
    break;

    case '3':

      $('trCodRecIdent').style.display          = 'table-row';
      $('trMesAnoCompetencia').style.display    = 'table-row';
      $('trValorINSS').style.display            = 'table-row';
      $('trValorOutras').style.display          = 'table-row';
      $('trAtualizacaoMonetaria').style.display = 'table-row';
    break;

    case '4':

      $('trCodRecIdent').style.display          = 'table-row';
      $('trPeriodoApuracao').style.display      = 'table-row';
      $('trNumReferencia').style.display        = 'table-row';
      $('trValor').style.display                = 'table-row';
      $('trValorMulta').style.display           = 'table-row';
      $('trJurosEncargos').style.display        = 'table-row';
      $('trData').style.display                 = 'table-row';
    break;

    case '5':

      $('trCodRecIdent').style.display          = 'table-row';
      $('trPeriodoApuracao').style.display      = 'table-row';
      $('trReceitaBruta').style.display         = 'table-row';
      $('trPercentualReceita').style.display    = 'table-row';
      $('trValor').style.display                = 'table-row';
      $('trValorMulta').style.display           = 'table-row';
      $('trJurosEncargos').style.display        = 'table-row';
      $('trData').style.display                 = 'table-row';
    break;
  }


}


//  --------==========  PESQUISAS DOS FILTROS =======================

//--------FUNCAO PESQUISA DE ORDEM---------------------------------------------------
function js_pesquisae82_codord(mostra){
if(mostra==true){
  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_pagordem',
                      'func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord',
                      'Pesquisa Ordens de Pagamento',
                      true,
                      22,
                      0,
                      document.body.getWidth() - 12,
                      document.body.scrollHeight - 30
                      );
}else{
  ord01 = new Number(document.form1.e82_codord.value);
  ord02 = new Number(document.form1.e82_codord02.value);
  if(ord01 > ord02 && ord01 != "" && ord02 != ""){
    alert("Selecione uma ordem menor que a segunda!");
    document.form1.e82_codord.focus();
    document.form1.e82_codord.value = '';
  }
}
}
function js_mostrapagordem1(chave1){
document.form1.e82_codord.value = chave1;
db_iframe_pagordem.hide();
}
//-----------------------------------------------------------
//---ordem 02
function js_pesquisae82_codord02(mostra){
if(mostra==true){
  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_pagordem',
                      'func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e50_codord',
                      'Pesquisa Ordens de Pagamento',
                      true,
                      22,
                      0,
                      document.body.getWidth() - 12,
                      document.body.scrollHeight - 30
                      );
}else{
  ord01 = new Number(document.form1.e82_codord.value);
  ord02 = new Number(document.form1.e82_codord02.value);
  if(ord01 > ord02 && ord02 != ""  && ord01 != ""){
    alert("Selecione uma ordem maior que a primeira");
    document.form1.e82_codord02.focus();
    document.form1.e82_codord02.value = '';
  }
}
}
function js_mostrapagordem102(chave1,chave2){
document.form1.e82_codord02.value = chave1;
db_iframe_pagordem.hide();
}
//======================  PESQISA EMPENHO ============================
function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_empempenho',
                        'func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp',
                        'Pesquisar Empenhos',
                        true,
                        22,
                        0,
                        document.body.getWidth() - 12,
                        document.body.scrollHeight - 30);
  }
}
function js_mostraempempenho2(chave1){
  document.form1.e60_codemp.value = chave1;
  db_iframe_empempenho.hide();
}

//===================  PESQUISA DE RECURSOS ============================

function js_pesquisac62_codrec(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('CurrentWindow.corpo',
                           'db_iframe_orctiporec',
                           'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr',
                           'Pesquisar Recursos',
                           true,
                           22,
                           0,
                          document.body.getWidth() - 12,
                          document.body.scrollHeight - 30);
   }else{
       if(document.form1.o15_codigo.value != ''){
           js_OpenJanelaIframe('CurrentWindow.corpo',
                               'db_iframe_orctiporec',
                               'func_orctiporec.php?pesquisa_chave='+document.form1.o15_codigo.value+
                               '&funcao_js=parent.js_mostraorctiporec',
                               'Pesquisar Recursos',
                               false,
                               22,
                               0,
                               document.body.getWidth() - 12,
                               document.body.scrollHeight - 30);
       }else{
           document.form1.o15_descr.value = '';
       }
   }
}
function js_mostraorctiporec(chave,erro){
   document.form1.o15_descr.value = chave;
   if(erro==true){
      document.form1.o15_codigo.focus();
      document.form1.o15_codigo.value = '';
   }
}

function js_mostraorctiporec1(chave1,chave2){
    document.form1.o15_codigo.value = chave1;
    document.form1.o15_descr.value = chave2;
    db_iframe_orctiporec.hide();
}

//=============  PESQUISA DE CGM ============================================

function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'func_nome',
                        'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
                        'Pesquisar CGM',
                        true,
                        22,
                        0,
                        document.body.getWidth() - 12,
                        document.body.scrollHeight - 30);
  }else{
     if(document.form1.z01_numcgm.value != ''){

        js_OpenJanelaIframe('',
                            'func_nome',
                            'func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+
                            '&funcao_js=parent.js_mostracgm',
                            'Pesquisar CGM',
                            false,
                            22,
                            0,
                            document.width-12,
                            document.body.scrollHeight-30);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = '';
  }
}

function js_mostracgm1(chave1,chave2){

  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value   = chave2;
  func_nome.hide();

}

//==================   PESQUISA SLIPS
//-----------------------------------------------------------
function js_pesquisak17_slip(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_slip','func_slip.php?funcao_js=parent.js_mostraslip1|k17_codigo','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.k17_slip.value);
    ord02 = new Number(document.form1.k17_slip02.value);
    if(ord01 > ord02 && ord01 != "" && ord02 != ""){
      alert("Selecione um slip menor que o segundo!");
      document.form1.k17_slip.focus();
      document.form1.k17_slip.value = '';
    }
  }
}
function js_mostraslip1(chave1){
  document.form1.k17_slip.value = chave1;
  db_iframe_slip.hide();
}
//-----------------------------------------------------------
function js_pesquisak17_slip02(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_slip2','func_slip.php?funcao_js=parent.js_mostraslip102|k17_codigo','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.k17_slip.value);
    ord02 = new Number(document.form1.k17_slip02.value);
    if(ord01 > ord02 && ord02 != ""  && ord01 != ""){
      alert("Selecione um slip maior que o primeiro");
      document.form1.k17_slip02.focus();
      document.form1.k17_slip02.value = '';
    }
  }
}
function js_mostraslip102(chave1,chave2){
  document.form1.k17_slip02.value = chave1;
  db_iframe_slip2.hide();
}

function js_exibeCamposObn(iTipoTransmissao){

    switch (iTipoTransmissao) {

      case "1" : // CNABs

        $('btnCodigoBarra').disabled    = true;
        $('txtValor').readOnly          = true;
        $('txtLinhaDigitavel').readOnly = true;
        $('txtJuros').readOnly          = true;
        $('txtDesconto').readOnly       = true;
        $('txtData').readOnly           = true;
        $('iTipoFatura').disabled       = true;
        $('txtValor').value             = '';
        $('txtLinhaDigitavel').value    = '';
        $('txtJuros').value             = '';
        $('txtDesconto').value          = '';
        $('txtData').value              = '';
        $('iFatura').disabled           = true;
        $('txtValor').style.backgroundColor          = '#DEB887';
        $('txtLinhaDigitavel').style.backgroundColor = '#DEB887';
        $('txtJuros').style.backgroundColor          = '#DEB887';
        $('txtDesconto').style.backgroundColor       = '#DEB887';
        $('txtData').style.backgroundColor           = '#DEB887';
        $('iTipoFatura').style.backgroundColor       = '#DEB887';

        $('dtjs_txtData').style.display = 'none';
        $('incluir').disabled = true;
      break;

      case "2" : // OBN

        $('btnCodigoBarra').disabled    = false;
        $('txtValor'). readOnly         = false;
        $('txtLinhaDigitavel').readOnly = false;
        $('txtJuros'). readOnly         = false;
        $('txtDesconto'). readOnly      = false;
        $('txtData'). readOnly          = false;
        $('iTipoFatura'). disabled      = false;
        $('iFatura').disabled           = false;
        $('txtValor').style.backgroundColor          = '';
        $('txtLinhaDigitavel').style.backgroundColor = '';
        $('txtJuros').style.backgroundColor          = '';
        $('txtDesconto').style.backgroundColor       = '';
        $('txtData').style.backgroundColor           = '';
        $('iTipoFatura').style.backgroundColor       = '';
        $('dtjs_txtData').style.display              = '';
        $('incluir').disabled                        = false;
      break;
    }
  }

  /**
   * Verifica o recurso configurado com FUNDEB
   */
  function js_recursoParametroFundeb() {

    //js_divCarregando("Aguarde, verificando recurso FUNDEB...", "msgBox");
    js_divCarregando(_M(sArquivoMensagens + ".verificando_recurso_fundeb"), 'msgBox');

    var oParam  = new Object();
    oParam.exec = "getRecursoFundeb";

    new Ajax.Request(sUrlRPC,
                     {method: 'post',
                      parameters: "json="+Object.toJSON(oParam),
                      onComplete: function (oAjax){

                        js_removeObj("msgBox");
                        var oRetorno = eval("("+oAjax.responseText+")");
                        iCodigoRecursoFundeb = oRetorno.iCodigoRecurso;

                      }
                     });
  }
  js_recursoParametroFundeb();
</script>
