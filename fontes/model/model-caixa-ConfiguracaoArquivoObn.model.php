<?php
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

/**
 * Classe que possui as configura��es referentes ao processamento do arquivo de envio OBN
 * @author Bruno Silva bruno.silva@dbseller.com.br
 * @package caixa
 */
class ConfiguracaoArquivoObn {

  //C�digo padr�o usado no layout de linha
  const CODIGO_PADRAO_INSTITUICAO = "00000100001";

  //Tipo de opera��o
  const OPERACAO_DOC           = 31;
  const OPERACAO_TED           = 31;
  const OPERACAO_DEP           = 32;
  const OPERACAO_CODIGO_BARRAS = 38;

  /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte1] */
  const OPERACAO_SLIP_CONTAUNICA    = 17;
  const OPERACAO_COM_FATURA    = 33;
  const OPERACAO_SLIP_OUTRASCONTAS  = 37;
        
  /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte1] */

  //Tipo de pagamento
  const PAGAMENTO_CONTA_BB      = 1;
  const PAGAMENTO_CAIXA         = 3;
  const PAGAMENTO_OUTROS_BANCOS = 4;

  //Tipo de favorecido
  const TIPO_CNPJ    = 1;
  const TIPO_CPF     = 2;
  const CPF_TAMANHO  = 11;

  //Tipo de Layout Linha
  const LAYOUT5      = 5;
  const LAYOUT4      = 4;
  const LAYOUT3      = 3;
  const LAYOUT2      = 2;

  //Valor que determinate para tipar opera��es em TED ou DOC
  const VALOR_DETERMINANTE_DOC_TED = 1000;


  /**
   * Verifica o Tipo de opera��o da movimenta��o, retornando o c�digo correspondente de acordo com layout do arquivo
   * @param  stdClass $oDadosMovimentacao
   * @throws BusinessException
   * @return integer
   */
  public static function verificarTipoOperacao(MovimentoArquivoTransmissao $oDadosMovimentacao) {

    // Movimenta��o com c�digo de barras
    $sCodigoBarras  = $oDadosMovimentacao->getCodigoBarra();
    $iCodigoReceita = $oDadosMovimentacao->getCodReceita();
    
    if (!empty ($sCodigoBarras) || !empty($iCodigoReceita)) {
      return ConfiguracaoArquivoObn::OPERACAO_CODIGO_BARRAS;
    }

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte2] */
    // Se o Movimento estiver marcado como fatura (Sim = t) ent�o tipo = 33
    if ($oDadosMovimentacao->getComFatura() == "t") {
       return ConfiguracaoArquivoObn::OPERACAO_COM_FATURA;
    }

    if ($oDadosMovimentacao->getSlipVinculo() == 13) {
      if ($oDadosMovimentacao->getCodigoBancoFavorecido() == $oDadosMovimentacao->getCodigoBancoPagador()) { 
        return ConfiguracaoArquivoObn::OPERACAO_DEP;
      } else { // Banco Diferente
          return ConfiguracaoArquivoObn::OPERACAO_DOC;
      }
    }
        
    /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte2] */

    // Contas do mesmo banco, opera��o tipo DEP
    if ($oDadosMovimentacao->getCodigoBancoFavorecido() == $oDadosMovimentacao->getCodigoBancoPagador()) {

      /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte3] */
      if ($oDadosMovimentacao->getCodigoSlip() != null) {
        if ($oDadosMovimentacao->getContaPagadora().$oDadosMovimentacao->getDigitoVerificadorContaPagadora() == "70009"){
          return ConfiguracaoArquivoObn::OPERACAO_SLIP_CONTAUNICA;
        } else {
         return ConfiguracaoArquivoObn::OPERACAO_SLIP_OUTRASCONTAS;
        }
      }


      // se for Slip e a conta pagadora 7000-9 tipo 17, caso contrario tipo 37
      if ($oDadosMovimentacao->getCodigoSlip() != null) {
        if ($oDadosMovimentacao->getContaPagadora().$oDadosMovimentacao->getDigitoVerificadorContaPagadora() == "70009"){
          return ConfiguracaoArquivoObn::OPERACAO_SLIP_CONTAUNICA;
        } else {
          return ConfiguracaoArquivoObn::OPERACAO_SLIP_OUTRASCONTAS;
        }
      }
      /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte3] */

      return ConfiguracaoArquivoObn::OPERACAO_DEP;
    }

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte4] */
    else { // Banco Diferente
      return ConfiguracaoArquivoObn::OPERACAO_DOC;
    }
        
    /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte4] */

    //Opera��o tipo DOC
    if ($oDadosMovimentacao->getValor() <= ConfiguracaoArquivoObn::VALOR_DETERMINANTE_DOC_TED) {
      return ConfiguracaoArquivoObn::OPERACAO_DOC;
    }

    //Opera��o tipo TED
    if ($oDadosMovimentacao->getValor() > ConfiguracaoArquivoObn::VALOR_DETERMINANTE_DOC_TED) {
      return ConfiguracaoArquivoObn::OPERACAO_TED;
    }

    throw new BusinessException("Erro t�cnico: Erro ao verificar o tipo de opera��o.");
  }


  /**
   * Verifica o tipo do favorecido, retornando o valor correspondente de acordo com layout arquivo
   * @param  stdClass $iTamanhoCpf
   * @return integer
   */
  public static function verificaTipoFavorecido($iTamanhoCpf) {

    $iTipoFavorecido = ConfiguracaoArquivoObn::TIPO_CNPJ;

    if ($iTamanhoCpf <= ConfiguracaoArquivoObn::CPF_TAMANHO) {
      $iTipoFavorecido = ConfiguracaoArquivoObn::TIPO_CPF;
    }
    return $iTipoFavorecido;
  }


  /**
   * Verifica o tipo de opera��o, retornando o valor correspondente de acordo com layout arquivo
   * @param  stdClass $iTamanhoCpf
   * @return integer
   */
  public static function verificaTipoPagamento($iTipoOperacao) {

    switch ($iTipoOperacao) {

      case ConfiguracaoArquivoObn::OPERACAO_CODIGO_BARRAS:
        return ConfiguracaoArquivoObn::PAGAMENTO_CAIXA;
      break;

      case ConfiguracaoArquivoObn::OPERACAO_DEP:
        return ConfiguracaoArquivoObn::PAGAMENTO_CONTA_BB;
      break;

      case ConfiguracaoArquivoObn::OPERACAO_DOC|| ConfiguracaoArquivoObn::OPERACAO_TED:
        return ConfiguracaoArquivoObn::PAGAMENTO_OUTROS_BANCOS;
      break;

      default:
        throw new BusinessException("Erro t�cnico: Imposs�vel verificar tipo de pagamento");
      break;
    }
  }


  /**
   * Fun��o que verifica o layout em que a linha ser� gerada
   * Caso n�o exista cadastro de detalhe (c�digo de barras) ser� o LAYOUT3
   * Sen�o tipo LAYOUT4
   * @return Integer
   */
  public static function verificaTipoLinha($sCodigoDeBarras) {

    if (empty($sCodigoDeBarras)){
      return ConfiguracaoArquivoObn::LAYOUT2;
    }
    return ConfiguracaoArquivoObn::LAYOUT4;
  }


  /* [Inicio plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte5] */
  /**
   * Fun��o que verifica o layout em que a linha ser� gerada
   * Caso n�o exista cadastro de detalhe (c�digo de barras) ser� o LAYOUT3
   * Sen�o tipo LAYOUT4
   * @return Integer
   */
  public static function verificaTipoLinha_2($sCodigoDeBarras, $iCodigoReceita, $iSlip, $iCodigoBancoFavorecido, $iSlipVinculo) {
    if (!empty($sCodigoDeBarras)) {
      return ConfiguracaoArquivoObn::LAYOUT4;
    }

    if (!empty($iCodigoReceita)) {
      return ConfiguracaoArquivoObn::LAYOUT5;
    }

    // caso seja slip
    if ($iSlip != null){

    if ($iCodigoBancoFavorecido == '001') {
        if ($iSlipVinculo == 13) { // Slip de Reten��o
            return ConfiguracaoArquivoObn::LAYOUT2;
        } else {
            return ConfiguracaoArquivoObn::LAYOUT3;
        }
    } else {
       return ConfiguracaoArquivoObn::LAYOUT2;
    }

    } else {
    return ConfiguracaoArquivoObn::LAYOUT2;
    }

    if (empty($sCodigoDeBarras)){
      return ConfiguracaoArquivoObn::LAYOUT2;
    }
    return 0;
  }
        
  /* [Fim plugin GeracaoArquivoOBN  - Geracao arquivo OBN - parte5] */
  
}
