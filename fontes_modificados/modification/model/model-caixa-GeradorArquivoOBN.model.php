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

require_once(modification("dbforms/db_layouttxt.php"));
require_once(modification("model/caixa/ConfiguracaoArquivoObn.model.php"));
require_once(modification("std/DBDate.php"));


/**
 * Classe que gera arquivo no Layout OBN
 * @author Matheus Felini matheus.felini@dbseller.com.br
 * @author Bruno Silva bruno.silva@dbseller.com.br
 * @package caixa
 */
class GeradorArquivoOBN {

  /**
   * Destino onde arquivo ser� gerado
   * @var String
   */
  private $sLocalizacaoArquivo;

  /**
   * Data
   * @var "yyyy-mm-dd"
   */
  private $dtGeracaoArquivo;

  /**
   * Hora
   * @var time
   */
  private $dtHoraGeracaoArquivo;

  /**
   * Data da autoriza��o do pagamento
   * @var date
   */
  private $dtAutorizacaoPagamento;

  /**
   * C�digo sequencial do arquivo
   * @var integer
   */
  private $iSequencialArquivo;

  /**
   * C�digo da remessa do arquivo
   * @var integer
   */
  private $iCodigoRemessa;

  /**
   * Descri��o do arquivo
   * @var string
   */
  private $sDescricaoArquivo;

  /**
   * Institui��o
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Contador de registros no arquivo
   * @var Instituicao
   */
  private $iContadorRegistros;

  /**
   * Sequencial do registro dentro do arquivo
   * @var integer
   */
  private $iSequencialRegistro = 1;


  /**
   * Ano referente a gera��o do arquivo
   * @var Ano
   */
  private $iAno;

  /**
   * Armazena os o somat�rio dos valores das ordens
   * @var float
   */
  private $nValorTotalDasMovimentacoes = 0;


  /**
   * Arquivo de transmiss�o
   * @var ArquivoTransmissao
   */
  private $oArquivoTransmissao;


  public function __construct() {
    $oArquivoTransmissao = new ArquivoTransmissao();
  }

  /**
   * Retorna o codigo da remessa
   * @return integer $iCodigoRemessa
   */
  public function getCodigoRemessa() {
  	return $this->iCodigoRemessa;
  }

  /**
   * seta o codigo da remessa
   * @param integer $iCodigoRemessa
   */
  public function setCodigoRemessa($iCodigoRemessa){

  	$this->iCodigoRemessa = $iCodigoRemessa;
  }

  /**
   * Retorna o destino do arquivo OBN
   * @return String $sLocalizacaoArquivo
   */
  public function getLocalizacao() {
    return $this->sLocalizacaoArquivo;
  }

  /**
   * Seta o destino do arquivo OBN
   * @param String $sLocalizacaoArquivo
   */
  public function setLocalizacao($sLocalizacaoArquivo) {
    $this->sLocalizacaoArquivo = $sLocalizacaoArquivo;
  }

  /**
   * Seta a descri��o do arquivo
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricaoArquivo = $sDescricao;
  }

  /**
   * Seta a data de gera��o do arquivo
   * @param date $dtGeracao
   */
  public function setDataGeracao($dtGeracao) {
    $this->dtGeracaoArquivo = $dtGeracao;
  }

  /**
   * Seta a hora da gera��o
   * @param time $dtHoraGeracaoArquivo
   */
  public function setHoraGeracao($dtHoraGeracaoArquivo) {
    $this->dtHoraGeracaoArquivo= $dtHoraGeracaoArquivo;
  }

  /**
   * Seta a data da autoriza��o do pagamento
   * @param date $dtAutorizacaoPagamento
   */
  public function setDataAutorizacaoPagamento($dtAutorizacaoPagamento) {
    $this->dtAutorizacaoPagamento = $dtAutorizacaoPagamento;
  }

  /**
   * Seta a institui��o vinculada ao arquivo
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Retorna a institui��o vinculada ao arquivo
   * @return Instituicao $oInstituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Retorna o Ano do processamento do arquivo
   * @return Ano
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Seta o Ano do processamento do arquivo
   * @param  integer $iAno
   * @return integer
   */
  public function setAno($iAno) {
    return $this->iAno = $iAno;
  }

  /**
   *  Vincula movimento com um arquivo de remessa (empagegera)
   *  e imprime arquivo com movimentos, de acordo com layout
   */
  public function construirRemessa(array $aMovimentosAgenda){

    $this->salvarGeracaoArquivo();
    $this->vincularMovimentosNaGeracao($aMovimentosAgenda);
    $this->geraArquivoEnvio($aMovimentosAgenda);
  	$this->vincularRemessaNumeracao();
  	$this->setCodigoSequencialArquivo();
  }


  /**
   * Altera os dados do arquivo, como a data e hora de gera��o
   * reimprimindo o mesmo
   * @throws BusinessException
   */
  public function regerarArquivo() {

  	if (empty($this->oInstituicao)) {
  		throw new BusinessException("ERRO [ 0 ] - Gerando arquivo - N�o foi encontrada institui��o.");
  	}

  	if (empty($this->iAno)) {
  		throw new BusinessException("ERRO [ 1 ] - Gerando arquivo - N�o foi encontrado o ano da se��o.");
  	}

  	if (empty($this->iCodigoRemessa)) {
  		throw new BusinessException("ERRO [ 2 ] - Gerando arquivo - N�o foi encontrado c�digo da remessa.");
  	}
		$this->iSequencialArquivo = $this->buscaCodigoArquivoRemessa();
  	$this->salvarGeracaoArquivo();
  	$this->buscaCodigoArquivoRemessa();
  	$this->geraArquivoEnvio();
  }

  /**
   * Gera o Arquivo com Layout OBN, a partir dos movimentos
   * @param Array $aMovimentosAgenda
   */
  private function geraArquivoEnvio() {

    $dtNomeArquivo              = str_replace("-", "_", $this->dtGeracaoArquivo);
    $this->sLocalizacaoArquivo  = "tmp/arquivo_{$this->iCodigoRemessa}_{$dtNomeArquivo}.txt";
    $iInstituicao               = $this->oInstituicao->getSequencial();
    $iAno                       = $this->iAno;
    $iRemessa                   = $this->iCodigoRemessa;
    $sSqlGeracaoArquivo         = MovimentoArquivoTransmissao::getSqlDadosMovimentacao($iRemessa, $iInstituicao, $iAno);
    $rsBuscaDadosGeracaoArquivo = db_query($sSqlGeracaoArquivo);
    $iCodigoConvenio            = db_utils::fieldsMemory($rsBuscaDadosGeracaoArquivo, 0)->convenio;
    $iTotalMovimentos           = pg_num_rows($rsBuscaDadosGeracaoArquivo);
    $iContaPagadora             = 0;

    $aCodigoSequenciais = array();

    $oLayoutTXT   = new db_layouttxt(211, $this->sLocalizacaoArquivo);
    $oHeader      = $this->constroiDadosHeader($iCodigoConvenio);

    $oLayoutTXT->setByLineOfDBUtils($oHeader, 1);
    $this->iContadorRegistros++;
    $aCodigoSequenciais[] = $this->iContadorRegistros;

    $oStdDadosBancoAnterior                = new stdClass();
    $oStdDadosBancoAnterior->banco         = 0;
    $oStdDadosBancoAnterior->agencia       = 0;
    $oStdDadosBancoAnterior->digitoAgencia = 0;
    $oStdDadosBancoAnterior->conta         = 0;
    $oStdDadosBancoAnterior->digitoConta   = 0;

    /**
     * @TODO fazer uso do model ArquivoTransmissao, usando getMovimentos para buscar objetos
     * do tipo MovimentoArquivoTransmissao, para n�o precisar refazer o SQL "MovimentoArquivoTransmissao::getSqlDadosMovimentacao"
     */
    for ($iDadoMovimento = 0; $iDadoMovimento < $iTotalMovimentos; $iDadoMovimento++ ) {

      $this->iSequencialRegistro++;
      $oStdMovimento   = db_utils::fieldsMemory($rsBuscaDadosGeracaoArquivo, $iDadoMovimento);
      $oDadosMovimento = MovimentoArquivoTransmissao::montaObjetoLinha($oStdMovimento);
      /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte2] */
      //$oLinha          = $this->constroiLinhaTipoDois($oDadosMovimento);
      //$oLayoutTXT->setByLineOfDBUtils($oLinha, 3, 2);
      /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte2] */

      $this->iContadorRegistros++;
      $aCodigoSequenciais[] = $this->iContadorRegistros;

      $this->nValorTotalDasMovimentacoes += $oDadosMovimento->getValor();

      /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte3] */
       $iTipoLinha      = ConfiguracaoArquivoObn::verificaTipoLinha_2($oDadosMovimento->getCodigoBarra(), 
                                                                      $oDadosMovimento->getCodReceita(),
                                                                      $oDadosMovimento->getCodigoSlip(), 
                                                                      $oDadosMovimento->getCodigoBancoFavorecido(), 
                                                                      $oDadosMovimento->getSlipVinculo());
      /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte3] */
      switch($iTipoLinha) {

      /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte4] */
        case ConfiguracaoArquivoObn::LAYOUT2:
          $oLinha          = $this->constroiLinhaTipoDois($oDadosMovimento);
          $oLayoutTXT->setByLineOfDBUtils($oLinha, 3, 2);
        break;
        case ConfiguracaoArquivoObn::LAYOUT3:
          /*
            O tipo 3 � utilizado em Natal para os Slips. O banco configurou este tipo 3 e tipo de opera��o 17 em Natal para float 0,
            ou seja, uma transfer�ncia para o entre secret�rias � realizada no mesmo dia.
            E o tipo de opera��o nela contida � 17, caso a conta cr�dito seja a conta �nica (7000-9) e 37 para as demais contas.
          */
          
          $oLinha          = $this->constroiLinhaTipoDois($oDadosMovimento);
          $oLayoutTXT->setByLineOfDBUtils($oLinha, 3, 2); 
            
          $this->iSequencialRegistro++;
          $this->iContadorRegistros++;
          $aCodigoSequenciais[] = $this->iContadorRegistros;

          $oLinha          = $this->constroiLinhaTipoTresNovo($oDadosMovimento);
          $oLayoutTXT->setByLineOfDBUtils($oLinha, 3, 3);
        break;
        
      /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte4] */
      case ConfiguracaoArquivoObn::LAYOUT4:

      /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte5] */
          $oLinha          = $this->constroiLinhaTipoDois($oDadosMovimento);
          $oLayoutTXT->setByLineOfDBUtils($oLinha, 3, 2);
        
      /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte5] */
          $this->iSequencialRegistro++;
      /* Plugin ArquivoOBNGRU - Parte1 */
          $oDaoDetalheTransmissao = db_utils::getDao('empagemovdetalhetransmissao');
          $sSqlDetalheTransmissao = $oDaoDetalheTransmissao->sql_query_file (null, "*", null, "e74_empagemov = {$oDadosMovimento->getCodigoMovimento()}");
          $rsDetalheTransmissao   = $oDaoDetalheTransmissao->sql_record($sSqlDetalheTransmissao);
          
          if ($oDaoDetalheTransmissao->numrows > 0) {

            for ($iDetalheTransmissao=0; $iDetalheTransmissao < $oDaoDetalheTransmissao->numrows; $iDetalheTransmissao++) { 
              
              $oDadosDetalheTransmissao = db_utils::fieldsMemory($rsDetalheTransmissao, $iDetalheTransmissao);
              
              $oLinha = $this->constroiLinhaTipoQuatro($oDadosMovimento, $oDadosDetalheTransmissao);
              $oLayoutTXT->setByLineOfDBUtils($oLinha, 3, 4);
              
              $this->iContadorRegistros++;
            }

          }
          $aCodigoSequenciais[] = $this->iContadorRegistros;
          break;


      case ConfiguracaoArquivoObn::LAYOUT5:

          $oLinha          = $this->constroiLinhaTipoDois($oDadosMovimento);
          $oLayoutTXT->setByLineOfDBUtils($oLinha, 3, 2);
        
          $this->iSequencialRegistro++;

          $oDaoEmpAgeMovPagamento = db_utils::getDao('empagemovpagamento');
          $sSqlEmpAgeMovPagamento = $oDaoEmpAgeMovPagamento->sql_query_empagemov("*", "", "empagemov = {$oDadosMovimento->getCodigoMovimento()}");
          $rsEmpAgeMovPagamento   = $oDaoEmpAgeMovPagamento->sql_record($sSqlEmpAgeMovPagamento);

          if ($oDaoEmpAgeMovPagamento->numrows > 0) {

            for ($iPagamento = 0; $iPagamento < $oDaoEmpAgeMovPagamento->numrows; $iPagamento++) { 
              
              $oDadosPagamento = db_utils::fieldsMemory($rsEmpAgeMovPagamento, $iPagamento);

              $oLinha = $this->constroiLinhaTipoCinco($oDadosMovimento, $oDadosPagamento);
              $oLayoutTXT->setByLineOfDBUtils($oLinha, 3, 5);
              
              $this->iContadorRegistros++;
            }

          }
          
          $aCodigoSequenciais[] = $this->iContadorRegistros;
      break;

      /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte6] */
        default:
          throw new BusinessException("Linha $iTipo n�o foi configurada para tipo OBN.");
        
      /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte6] */

      }

    }


    $this->iContadorRegistros = array_sum($aCodigoSequenciais);
    $oLinha    = $this->constroiLinhaTrailer();
    $oLayoutTXT->setByLineOfDBUtils($oLinha, 5);
  }

  /**
   * metodo ir� vincular o arquivo gerado na empagegera com a numera��o OBN
   */

  private function vincularRemessaNumeracao(){

    /*
     * criamos vinculo do codgera com a numera��o obn
     */
    $oDaoEmpAgeGeraObn = db_utils::getDao("empagegeraobn");
    $oDaoEmpAgeGeraObn->e138_numeracaoobn = $this->iSequencialArquivo;
    $oDaoEmpAgeGeraObn->e138_empagegera   = $this->iCodigoRemessa;
    $oDaoEmpAgeGeraObn->incluir(null);
    if ($oDaoEmpAgeGeraObn->erro_status == 0 ) {
      throw new DBException("ERRO - [ 0 ] - criando vinculo obn - " . $oDaoEmpAgeGeraObn->erro_msg);
    }
  }

  /**
   * Constr�i os dados que ser�o impressos na linha do tipo 2.
   * @param MovimentoArquivoTransmissao $oDadosLinha
   * @return stdClass
   */
  private function constroiLinhaTipoDois(MovimentoArquivoTransmissao $oDadosLinha) {

    list($iAno, $iMes, $iDia) = explode("-", $this->dtGeracaoArquivo);
    $iTipoFavorecido          = ConfiguracaoArquivoObn::verificaTipoFavorecido(strlen($oDadosLinha->getCnpj()));
    $iTipoOperacao            = ConfiguracaoArquivoObn::verificarTipoOperacao($oDadosLinha);
    $iTipoPagamento           = ConfiguracaoArquivoObn::verificaTipoPagamento($iTipoOperacao);

    $sCPFCNPJ = $oDadosLinha->getCnpj();
    if ($iTipoFavorecido == ConfiguracaoArquivoObn::TIPO_CPF) {
      /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte7] */
      $sCPFCNPJ = str_pad($sCPFCNPJ, 14, " ", STR_PAD_RIGHT);
      /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte7] */
    }

    $sAgenciaInstituicao = $oDadosLinha->getCodigoAgenciaPagadora().$oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $sContaConvenio      = $oDadosLinha->getContaFavorecida().$oDadosLinha->getDigitoVerificadorContaFavorecida();

    $sAgenciaDigitoPagadora = $oDadosLinha->getCodigoAgenciaPagadora().$oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $sContaDigitoPagadora   = $oDadosLinha->getContaPagadora().$oDadosLinha->getDigitoVerificadorContaPagadora();

    $sCodigoFinalidadePagamento = $oDadosLinha->getFinalidadePagamentoFundeb();
    if (!empty($sCodigoFinalidadePagamento)) {

      $oFinalidadePagamento       = new FinalidadePagamentoFundeb($oDadosLinha->getFinalidadePagamentoFundeb());
      $sCodigoFinalidadePagamento = $oFinalidadePagamento->getCodigo();
    }


    $sDigitoVerificadorAgenciaFavorecido = $oDadosLinha->getDigitoVerificadorAgenciaFavorecida();
    
    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte21] */
    $sCodigoAgenciaFavorecido            = str_pad(substr($oDadosLinha->getCodigoAgenciaFavorecida(), -4), 4, "0", STR_PAD_LEFT);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte21] */
    $sCodigoBancoFavorecido              = $oDadosLinha->getCodigoBancoFavorecido();
    $sContaDigitoFavorecido              = str_pad($oDadosLinha->getContaFavorecida().$oDadosLinha->getDigitoVerificadorContaFavorecida(), 10, "0", STR_PAD_LEFT);

    if ($oDadosLinha->getCodigoBarra() != "") {


      $sDigitoVerificadorAgenciaFavorecido = str_pad("0", 01, "0", STR_PAD_LEFT);
      $sCodigoAgenciaFavorecido            = str_pad("0", 04, "0", STR_PAD_LEFT);
      $sCodigoBancoFavorecido              = str_pad("0", 03, "0", STR_PAD_LEFT);
      $sContaDigitoFavorecido              = str_pad("0", 10, "0", STR_PAD_LEFT);
      
    }

    $oStdLinhaTipoDois                              = new stdClass();
    $oStdLinhaTipoDois->numero_sequencial_movimento = str_pad($this->iSequencialRegistro, 7, "0", STR_PAD_LEFT);

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte8] */
    $oStdLinhaTipoDois->codigo_retorno                = str_repeat("0", 2);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte8] */

    $oStdLinhaTipoDois->campo_branco_3              = str_repeat(" ", 4);

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte9] */
    $oStdLinhaTipoDois->finalidade_pagamento        = str_repeat(" ", 3);
    $oStdLinhaTipoDois->prefixo_conta_convenio        = str_pad($sContaDigitoPagadora, 10, " ", STR_PAD_RIGHT);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte9] */

    $oStdLinhaTipoDois->prefixo_agencia_convenio    = $sAgenciaDigitoPagadora;
    $oStdLinhaTipoDois->cpf_cnpj_favorecido         = $sCPFCNPJ;
    $oStdLinhaTipoDois->tipo_favorecido             = $iTipoFavorecido;

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte10] */
    $oStdLinhaTipoDois->campo_um = "0";
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte10] */

    $oStdLinhaTipoDois->observacao                  = str_repeat(" ", 40);
    $oStdLinhaTipoDois->estado_favorecido           = $oDadosLinha->getUf();
    $oStdLinhaTipoDois->cep_favorecido              = $oDadosLinha->getCep();
    $oStdLinhaTipoDois->campo_branco_2              = str_repeat(" ", 17);
    $oStdLinhaTipoDois->municipio_favorecido        = $oDadosLinha->getMunicipio();
    $oStdLinhaTipoDois->endereco_favorecido         = $oDadosLinha->getEndereco();
    $oStdLinhaTipoDois->nome_favorecido             = $oDadosLinha->getNome();
    $oStdLinhaTipoDois->codigo_conta_favorecido     = $sContaDigitoFavorecido;
    $oStdLinhaTipoDois->digito_agencia_favorecido   = $sDigitoVerificadorAgenciaFavorecido;
    $oStdLinhaTipoDois->codigo_agencia_favorecido   = $sCodigoAgenciaFavorecido;
    $oStdLinhaTipoDois->codigo_banco_favorecido     = $sCodigoBancoFavorecido;
    $oStdLinhaTipoDois->valor_liquido               = str_pad(str_replace(".", "", $oDadosLinha->getValor()), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTipoDois->campo_zero_1                = str_repeat("0", 9);
    $oStdLinhaTipoDois->tipo_pagamento              = "0";
    $oStdLinhaTipoDois->codigo_operacao             = $iTipoOperacao;
    $oStdLinhaTipoDois->campo_branco_1              = str_repeat(" ", 4);
    $oStdLinhaTipoDois->data_geracao                = "{$iDia}{$iMes}{$iAno}";
    $oStdLinhaTipoDois->codigo_ob                   = str_pad($oDadosLinha->getCodigoMovimento(), 11, "0", STR_PAD_LEFT);

        /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte11] */
    $oStdLinhaTipoDois->codigo_movimentacao = str_pad(number_format($oDadosLinha->getCodigoArquivoSistema(),0,',','.')."/".db_getsession("DB_anousu"), 11, "0", STR_PAD_LEFT);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - part11] */

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte12] */
    $sUgGestao      =  str_pad(str_pad($oDadosLinha->getOrgao(),2,"0",STR_PAD_LEFT).str_pad($oDadosLinha->getUnidade(),2,"0",STR_PAD_LEFT),"6","0",STR_PAD_LEFT).str_pad("1",5,"0",STR_PAD_LEFT);
    if ($oDadosLinha->getContaPagadora() == "7000") {
    $sUgGestao          =  "0".str_pad($oDadosLinha->getOrgao(),2,"0",STR_PAD_LEFT)."000".str_pad("1",5,"0",STR_PAD_LEFT);
    }
    $oStdLinhaTipoDois->codigo_instituicao      = $sUgGestao;
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte12] */

    $oStdLinhaTipoDois->codigo_agencia_dv           = $sAgenciaInstituicao;
    $oStdLinhaTipoDois->identificador_campo         = 2;
    return $oStdLinhaTipoDois;
  }

  /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte13] */
  /**
   * Constr�i os dados que ser�o impressos na linha do tipo 3 (novo)
   * @param MovimentoArquivoTransmissao $oDadosLinha
   * @return stdClass
   */
  private function constroiLinhaTipoTresNovo(MovimentoArquivoTransmissao $oDadosLinha) {

    list($iAno, $iMes, $iDia) = explode("-", $this->dtGeracaoArquivo);
    $iTipoFavorecido          = ConfiguracaoArquivoObn::verificaTipoFavorecido(strlen($oDadosLinha->getCnpj()));
    $iTipoOperacao            = ConfiguracaoArquivoObn::verificarTipoOperacao($oDadosLinha);
    $iTipoPagamento           = ConfiguracaoArquivoObn::verificaTipoPagamento($iTipoOperacao);

    $sUgGestao          =  str_pad(str_pad($oDadosLinha->getOrgao(),2,"0",STR_PAD_LEFT).str_pad($oDadosLinha->getUnidade(),2,"0",STR_PAD_LEFT),"6","0",STR_PAD_LEFT).str_pad("1",5,"0",STR_PAD_LEFT);

    if ($oDadosLinha->getContaPagadora() == "7000") {        
    $sUgGestao          =  "0".str_pad($oDadosLinha->getOrgao(),2,"0",STR_PAD_LEFT)."000".str_pad("1",5,"0",STR_PAD_LEFT);
    }

    $sCPFCNPJ = $oDadosLinha->getCnpj();
    if ($iTipoFavorecido == ConfiguracaoArquivoObn::TIPO_CPF) {
      $sCPFCNPJ = str_pad($sCPFCNPJ, 14, " ", STR_PAD_RIGHT);
    }
   
    $sAgenciaInstituicao = $oDadosLinha->getCodigoAgenciaPagadora().$oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $sContaConvenio      = $oDadosLinha->getContaFavorecida().$oDadosLinha->getDigitoVerificadorContaFavorecida();

    $sAgenciaDigitoPagadora = str_pad(substr($oDadosLinha->getCodigoAgenciaPagadora(), -4), 4, "0", STR_PAD_LEFT).$oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $sContaDigitoPagadora   = $oDadosLinha->getContaPagadora().$oDadosLinha->getDigitoVerificadorContaPagadora();

    $sCodigoFinalidadePagamento = $oDadosLinha->getFinalidadePagamentoFundeb();
    if (!empty($sCodigoFinalidadePagamento)) {

      $oFinalidadePagamento       = new FinalidadePagamentoFundeb($oDadosLinha->getFinalidadePagamentoFundeb());
      $sCodigoFinalidadePagamento = $oFinalidadePagamento->getCodigo();
    }


    $sDigitoVerificadorAgenciaFavorecido = $oDadosLinha->getDigitoVerificadorAgenciaFavorecida();
    $sCodigoAgenciaFavorecido            = str_pad(substr($oDadosLinha->getCodigoAgenciaFavorecida(), -4), 4, "0", STR_PAD_LEFT);
    $sCodigoBancoFavorecido              = $oDadosLinha->getCodigoBancoFavorecido();
    $sContaDigitoFavorecido              = str_pad($oDadosLinha->getContaFavorecida().$oDadosLinha->getDigitoVerificadorContaFavorecida(), 10, "0", STR_PAD_LEFT);

    if ($oDadosLinha->getCodigoBarra() != "") {


      $sDigitoVerificadorAgenciaFavorecido = str_pad("0", 01, "0", STR_PAD_LEFT);
      $sCodigoAgenciaFavorecido            = str_pad("0", 04, "0", STR_PAD_LEFT);
      $sCodigoBancoFavorecido              = str_pad("0", 03, "0", STR_PAD_LEFT);
      $sContaDigitoFavorecido              = str_pad("0", 10, "0", STR_PAD_LEFT);
      
    }

    $oStdLinhaTipoTres                                  = new stdClass();
    $oStdLinhaTipoTres->numero_sequencial_movimento     = str_pad($this->iSequencialRegistro, 7, "0", STR_PAD_LEFT);
    $oStdLinhaTipoTres->codigo_operacao                 = $iTipoOperacao;
    $oStdLinhaTipoTres->campo_branco_quatro         = str_repeat(" ", 7);
    $oStdLinhaTipoTres->numero_conta_convenio       = str_pad($sContaDigitoPagadora, 10, " ", STR_PAD_RIGHT);
    $oStdLinhaTipoTres->prefixo_agencia         = $sAgenciaDigitoPagadora;
    $oStdLinhaTipoTres->codigo_favorecido           = $sCPFCNPJ;
    $oStdLinhaTipoTres->tipo_favorecido         = $iTipoFavorecido;
    $oStdLinhaTipoTres->campo_zero_tres         = "0";
    $oStdLinhaTipoTres->observacao                      = str_repeat(" ", 40);
    $oStdLinhaTipoTres->uf_favorecido               = $oDadosLinha->getUf();
    $oStdLinhaTipoTres->cep_favorecido          = $oDadosLinha->getCep();
    $oStdLinhaTipoTres->campo_branco_tres       = str_repeat(" ", 17);
    $oStdLinhaTipoTres->municipio_favorecido        = $oDadosLinha->getMunicipio();
    $oStdLinhaTipoTres->endereco_favorecido     = $oDadosLinha->getEndereco();
    $oStdLinhaTipoTres->nome_favorecido             = $oDadosLinha->getNome();
    $oStdLinhaTipoTres->codigo_contacorrente_bancaria_favorecido    = $sContaDigitoFavorecido;
    $oStdLinhaTipoTres->digito_verificador_agencia_favorecido       = $sDigitoVerificadorAgenciaFavorecido;
    $oStdLinhaTipoTres->codigo_agencia_banco_favorecido = $sCodigoAgenciaFavorecido;
    $oStdLinhaTipoTres->codigo_banco_favorecido     = $sCodigoBancoFavorecido;
    $oStdLinhaTipoTres->valor_liquido_movimentacao  = str_pad(str_replace(".", "", $oDadosLinha->getValor()), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTipoTres->campo_branco_dois       = str_repeat(" ", 3);
    $oStdLinhaTipoTres->campo_zero_um           = "000001";             // Campo sequencial da Lista (Fixo 000001)
    $oStdLinhaTipoTres->tipo_pagamento          = "1";              // 1 - Pagamento Cr�dito em Conta BB
    $oStdLinhaTipoTres->codigo_operacao         = $iTipoOperacao;
    $oStdLinhaTipoTres->campo_branco_um         = str_repeat(" ", 4);
    $oStdLinhaTipoTres->data_movimentacao       = "{$iDia}{$iMes}{$iAno}";
    $oStdLinhaTipoTres->codigo_ob           = str_pad($oDadosLinha->getCodigoMovimento(), 11, "0", STR_PAD_LEFT);
    $oStdLinhaTipoTres->codigo_movimentacao     = str_pad(number_format($oDadosLinha->getCodigoArquivoSistema(),0,',','.')."/".db_getsession("DB_anousu"), 11, "0", STR_PAD_LEFT);
    $oStdLinhaTipoTres->codigo_instituicao      = $sUgGestao;
    $oStdLinhaTipoTres->codigo_agencia_bancaria_instituicao = $sAgenciaInstituicao;
    $oStdLinhaTipoTres->codigo_retorno_operacao     = str_repeat("0", 2);
    $oStdLinhaTipoTres->identificador_linha     = 3;
    return $oStdLinhaTipoTres;
  }  
  /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte13] */

  /**
   * Configura os registros dos registros de movimenta��o de pagamento pessoal, pagamento no caixa,
   * cr�dito em conta BB ou cr�dito em outros bancos
   * @param  MovimentoArquivoTransmissao $oDadosLinha
   * @return stdClass
   */
  private function constroiLinhaTipoTres(MovimentoArquivoTransmissao $oDadosLinha) {

    list($iAno, $iMes, $iDia) = explode("-", $this->dtGeracaoArquivo);
    $iTipoFavorecido          = ConfiguracaoArquivoObn::verificaTipoFavorecido(strlen($oDadosLinha->getCnpj()));
    $iTipoOperacao            = ConfiguracaoArquivoObn::verificarTipoOperacao($oDadosLinha);
    $iTipoPagamento           = ConfiguracaoArquivoObn::verificaTipoPagamento($iTipoOperacao);

    $sCPFCNPJ = $oDadosLinha->getCnpj();
    if ($iTipoFavorecido == ConfiguracaoArquivoObn::TIPO_CPF) {
      $sCPFCNPJ = str_pad($sCPFCNPJ, 14, "0", STR_PAD_RIGHT);
    }

    $oStdLinhaTipoTres        = new stdClass();
    $oStdLinhaTipoTres->identificador_linha = 3;

    $sAgenciaInstituicao = $oDadosLinha->getCodigoAgenciaPagadora().$oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $oStdLinhaTipoTres->codigo_agencia_bancaria_instituicao       = $sAgenciaInstituicao;
    $oStdLinhaTipoTres->codigo_instituicao                        = ConfiguracaoArquivoObn::CODIGO_PADRAO_INSTITUICAO;
    $oStdLinhaTipoTres->codigo_movimentacao                       = $oDadosLinha->getCodigoMovimento();
    $oStdLinhaTipoTres->codigo_ob                                 = $oDadosLinha->getCodigoMovimento();
    $oStdLinhaTipoTres->data_movimentacao                         = "{$iDia}{$iMes}{$iAno}";
    $oStdLinhaTipoTres->campo_branco_um                           = str_repeat(" ", 4);
    $oStdLinhaTipoTres->codigo_operacao                           = $iTipoOperacao;
    $oStdLinhaTipoTres->tipo_pagamento                            = 4;
    $oStdLinhaTipoTres->campo_zero_um                             = str_repeat("0", 6);
    $oStdLinhaTipoTres->campo_branco_dois                         = str_repeat(" ", 3);
    $oStdLinhaTipoTres->valor_liquido_movimentacao                = str_pad(str_replace(".", "", $oDadosLinha->getValor()), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTipoTres->codigo_banco_favorecido                   = $oDadosLinha->getCodigoBancoFavorecido();
    $oStdLinhaTipoTres->codigo_agencia_banco_favorecido           = $oDadosLinha->getCodigoAgenciaFavorecida();
    $oStdLinhaTipoTres->digito_verificador_agencia_favorecido     = $oDadosLinha->getDigitoVerificadorAgenciaFavorecida();
    $oStdLinhaTipoTres->codigo_contacorrente_bancaria_favorecido  = $oDadosLinha->getContaFavorecida();
    $oStdLinhaTipoTres->nome_favorecido                           = $oDadosLinha->getNome();
    $oStdLinhaTipoTres->endereco_favorecido                       = $oDadosLinha->getEndereco();
    $oStdLinhaTipoTres->municipio_favorecido                      = $oDadosLinha->getMunicipio();
    $oStdLinhaTipoTres->campo_branco_tres                         = str_repeat(" ", 17);
    $oStdLinhaTipoTres->cep_favorecido                            = $oDadosLinha->getCep();
    $oStdLinhaTipoTres->uf_favorecido                             = $oDadosLinha->getUf();
    $oStdLinhaTipoTres->observacao                                = str_repeat(" ", 40);
    $oStdLinhaTipoTres->campo_zero_tres                           = 0;
    $oStdLinhaTipoTres->tipo_favorecido                           = $iTipoFavorecido;
    $oStdLinhaTipoTres->codigo_favorecido                         = $sCPFCNPJ;
    $sPrefixoAgencia = $oDadosLinha->getCodigoAgenciaFavorecida().$oDadosLinha->getDigitoVerificadorAgenciaFavorecida();
    $oStdLinhaTipoTres->prefixo_agencia                           = $sPrefixoAgencia;
    $sContaConvenio = $oDadosLinha->getContaFavorecida().$oDadosLinha->getDigitoVerificadorContaFavorecida();
    $oStdLinhaTipoTres->numero_conta_convenio                     =  str_pad($sContaConvenio, 10, "0", STR_PAD_LEFT);

    $oStdLinhaTipoTres->campo_branco_quatro                       = str_repeat(" ", 7);
    $oStdLinhaTipoTres->codigo_retorno_operacao                   = str_repeat(" ", 2);
    $oStdLinhaTipoTres->numero_sequencial_movimento               = str_pad($this->iSequencialArquivo, 7, "0", STR_PAD_LEFT);
    return $oStdLinhaTipoTres;
  }

  /**
   * Configura os registros do tipo pagamento com codigo de barras
   * @param  MovimentoArquivoTransmissao $oDadosLinha
   * @return stdClass
   */
  /* Plugin ArquivoOBNGRU - Parte3 */
  private function constroiLinhaTipoQuatro(MovimentoArquivoTransmissao $oDadosLinha, $oDadosDetalheTransmissao) {

    $oStdLinhaTipoQuatro       = new stdClass();
    $iTipoFavorecido           = ConfiguracaoArquivoObn::verificaTipoFavorecido(strlen($oDadosLinha->getCnpj()));
    $iTipoOperacao             = ConfiguracaoArquivoObn::verificarTipoOperacao($oDadosLinha);
    list($iAno, $iMes, $iDia) = explode("-", $this->dtGeracaoArquivo);
    /* Plugin ArquivoOBNGRU - Parte4 */
    list($iAnoCodigoBarra, $iMesCodigoBarra, $iDiaCodigoBarra) = explode("-", $oDadosDetalheTransmissao->e74_datavencimento);

    $sAgenciaDigitoPagadora = $oDadosLinha->getCodigoAgenciaPagadora().$oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $sContaDigitoPagadora   = $oDadosLinha->getContaPagadora().$oDadosLinha->getDigitoVerificadorContaPagadora();

    $oStdLinhaTipoQuatro->identificador_linha              = 4;
    $sAgenciaInstituicao = $oDadosLinha->getCodigoAgenciaPagadora().$oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $oStdLinhaTipoQuatro->codigo_agencia_banco_instituicao = $sAgenciaInstituicao;

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte14] */
    $sUgGestao          =  str_pad(str_pad($oDadosLinha->getOrgao(),2,"0",STR_PAD_LEFT).str_pad($oDadosLinha->getUnidade(),2,"0",STR_PAD_LEFT),"6","0",STR_PAD_LEFT).str_pad("1",5,"0",STR_PAD_LEFT);
    if ($oDadosLinha->getContaPagadora() == "7000") {
        $sUgGestao          =  "0".str_pad($oDadosLinha->getOrgao(),2,"0",STR_PAD_LEFT)."000".str_pad("1",5,"0",STR_PAD_LEFT);
    }
    $oStdLinhaTipoQuatro->codigo_instituicao               = $sUgGestao;
    $oStdLinhaTipoQuatro->codigo_movimento                 = str_pad(number_format($oDadosLinha->getCodigoArquivoSistema(),0,',','.')."/".db_getsession("DB_anousu"), 11, "0", STR_PAD_LEFT);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte14] */

    $oStdLinhaTipoQuatro->codigo_ob                        = str_pad($oDadosLinha->getCodigoMovimento(), 11, "0", STR_PAD_LEFT);
    $oStdLinhaTipoQuatro->data_geracao_arquivo             = "{$iDia}{$iMes}{$iAno}";
    $oStdLinhaTipoQuatro->campo_branco_um                  = str_repeat(" ", 4);
    $oStdLinhaTipoQuatro->codigo_operacao                  = $iTipoOperacao;
    $oStdLinhaTipoQuatro->campo_branco_dois                = str_repeat(" ", 1);

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte15] */
    $oStdLinhaTipoQuatro->campo_zero_um                    = "000001";
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte15] */

    $oStdLinhaTipoQuatro->campo_branco_tres                = str_repeat(" ", 3);
    $oStdLinhaTipoQuatro->valor_liquido                    = str_pad(str_replace(".", "", $oDadosLinha->getValor()), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTipoQuatro->campo_branco_quatro              = str_repeat(" ", 15);

    /* Plugin ArquivoOBNGRU - Parte5 */
    $oStdLinhaTipoQuatro->tipo_fatura                      = $oDadosDetalheTransmissao->e74_tipofatura;
    $oStdLinhaTipoQuatro->codigo_barra                     = $oDadosDetalheTransmissao->e74_codigodebarra;
    /* Fim Plugin ArquivoOBNGRU - Parte5 */
    $oStdLinhaTipoQuatro->cb_data_vencimento               = "{$iDiaCodigoBarra}{$iMesCodigoBarra}{$iAnoCodigoBarra}";
    /* Plugin ArquivoOBNGRU - Parte6 */
    $oStdLinhaTipoQuatro->cb_valor_nominal                 = str_pad(str_replace(".", "", $oDadosDetalheTransmissao->e74_valornominal), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTipoQuatro->cb_valor_desconto_abatimento     = str_pad(str_replace(".", "",  $oDadosDetalheTransmissao->e74_valordesconto), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTipoQuatro->cb_valor_mora_juros              = str_pad(str_replace(".", "", $oDadosDetalheTransmissao->e74_valorjuros), 17, "0", STR_PAD_LEFT);
    /* Fim Plugin ArquivoOBNGRU - Parte6 */

    /* Plugin ArquivoOBNGRU - Parte7 */
    if ($oDadosDetalheTransmissao->e74_tipofatura == 2) {
    /* Fim Plugin ArquivoOBNGRU - Parte7 */

      $oStdLinhaTipoQuatro->cb_data_vencimento             = str_repeat(" ", 20);
      $oStdLinhaTipoQuatro->cb_valor_nominal               = str_repeat(" ", 20);
      $oStdLinhaTipoQuatro->cb_valor_desconto_abatimento   = str_repeat(" ", 10);
      $oStdLinhaTipoQuatro->cb_valor_mora_juros            = str_repeat(" ", 9);
    }

    $oStdLinhaTipoQuatro->campo_branco_cinco               = str_repeat(" ", 164);
    $oStdLinhaTipoQuatro->observacao_ob                    = str_repeat(" ", 40);
    $oStdLinhaTipoQuatro->numero_autenticacao              = str_repeat(" ", 16);

    $sConvenioAgencia  = $oDadosLinha->getCodigoAgenciaPagadora().$oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $oStdLinhaTipoQuatro->convenio_agencia_dv              = $sConvenioAgencia;

    $sConvenioConta    = $oDadosLinha->getContaPagadora().$oDadosLinha->getDigitoVerificadorContaPagadora();
    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte18] */
    $oStdLinhaTipoQuatro->convenio_conta_dv                = str_pad($sConvenioConta, 10, " ", STR_PAD_RIGHT);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte18] */

    $oStdLinhaTipoQuatro->campo_branco_seis                = str_repeat(" ", 7);
    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte19] */
    $oStdLinhaTipoQuatro->retorno_operacao                 = str_repeat("0", 2);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte19] */
    $oStdLinhaTipoQuatro->numero_sequencial_movimento      = str_pad($this->iSequencialRegistro, 7, "0", STR_PAD_LEFT);
    return $oStdLinhaTipoQuatro;
  }

  private function constroiLinhaTipoCinco(MovimentoArquivoTransmissao $oDadosLinha, $oDadosPagamento) {


    $oStdLinhaTipoCinco       = new stdClass();
    $iTipoFavorecido          = ConfiguracaoArquivoObn::verificaTipoFavorecido(strlen($oDadosLinha->getCnpj()));
    $iTipoOperacao            = ConfiguracaoArquivoObn::verificarTipoOperacao($oDadosLinha);
    list($iAno, $iMes, $iDia) = explode("-", $this->dtGeracaoArquivo);
    list($iAnoPagamento, $iMesPagamento, $iDiaPagamento) = explode("-", $oDadosLinha->getDataVencimento());


    $sCPFCNPJ = $oDadosLinha->getCnpj();
    if ($iTipoFavorecido == ConfiguracaoArquivoObn::TIPO_CPF) {
      $sCPFCNPJ = str_pad($sCPFCNPJ, 14, "0", STR_PAD_RIGHT);
    }
    $oStdLinhaTipoCinco->identificador_linha              = 5;
    $sAgenciaInstituicao = $oDadosLinha->getCodigoAgenciaPagadora().$oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $oStdLinhaTipoCinco->codigo_agencia_banco_instituicao = $sAgenciaInstituicao;

    $sUgGestao          =  str_pad(str_pad($oDadosLinha->getOrgao(),2,"0",STR_PAD_LEFT).str_pad($oDadosLinha->getUnidade(),2,"0",STR_PAD_LEFT),"6","0",STR_PAD_LEFT).str_pad("1",5,"0",STR_PAD_LEFT);
    if ($oDadosLinha->getContaPagadora() == "7000") {
        $sUgGestao          =  "0".str_pad($oDadosLinha->getOrgao(),2,"0",STR_PAD_LEFT)."000".str_pad("1",5,"0",STR_PAD_LEFT);
    }
    $oStdLinhaTipoCinco->codigo_instituicao               = $sUgGestao;
    $oStdLinhaTipoCinco->codigo_movimento                 = str_pad(number_format($oDadosLinha->getCodigoArquivoSistema(),0,',','.')."/".db_getsession("DB_anousu"), 11, "0", STR_PAD_LEFT);
    
    $oStdLinhaTipoCinco->codigo_ob                        = str_pad($oDadosLinha->getCodigoMovimento(), 11, "0", STR_PAD_LEFT);
    $oStdLinhaTipoCinco->data_geracao_arquivo             = "{$iDia}{$iMes}{$iAno}";
    $oStdLinhaTipoCinco->campo_branco_um                  = str_repeat(" ", 4);
    $oStdLinhaTipoCinco->codigo_operacao                  = $iTipoOperacao;
    $oStdLinhaTipoCinco->campo_branco_dois                = str_repeat(" ", 1);

    $oStdLinhaTipoCinco->campo_zero_um                    = "000001";
    
    $oStdLinhaTipoCinco->campo_branco_tres                = str_repeat(" ", 3);
    $oStdLinhaTipoCinco->valor_liquido                    = str_pad(str_replace(".", "", $oDadosLinha->getValor()), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTipoCinco->data_pagamento                   = "{$iDiaPagamento}{$iMesPagamento}{$iAnoPagamento}";
    $oStdLinhaTipoCinco->campo_branco_quatro              = str_repeat(" ", 7);
    
    $oStdLinhaTipoCinco->tipo_pagamento                   = $oDadosPagamento->tipopagamento;
    
    $oStdLinhaTipoCinco->cod_rec_tributo       = str_pad($oDadosPagamento->codreceita, 6, " ", STR_PAD_LEFT);
    $oStdLinhaTipoCinco->cod_ident_tributo     = str_pad($oDadosPagamento->codidentificacao, 2, " ", STR_PAD_LEFT);
    ////DETALHAMENTO////

    switch ($oDadosPagamento->tipopagamento) {
      
      //GPS
      case '1':

        //competencia
        $oStdLinhaTipoCinco->detalhe_linha5  = str_pad(str_replace("/", "", $oDadosPagamento->mesanocompetencia), 6, "0", STR_PAD_LEFT);
        //valor_INSS
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace(".", "", $oDadosPagamento->valorinss), 17, "0", STR_PAD_LEFT);
        //valor_outras
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace(".", "", $oDadosPagamento->valoroutras), 17, "0", STR_PAD_LEFT);
        //atualizacao_monetaria
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace(".", "", $oDadosPagamento->atualizacaomonetaria), 17, "0", STR_PAD_LEFT);
        //campo_branco_GPS
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_repeat(" ", 27);
      break;
      
      //DARF
      case '2':

        //periodo_apuracao
        $oStdLinhaTipoCinco->detalhe_linha5  = str_pad(str_replace("-", "", $oDadosPagamento->periodoapuracao), 8, "0", STR_PAD_LEFT);
        //num_referencia
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace(".", "", $oDadosPagamento->numreferencia), 17, "0", STR_PAD_LEFT);
        //valor_principal
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace(".", "", $oDadosPagamento->valorprincipal), 17, "0", STR_PAD_LEFT);
        //valor_multa
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace(".", "", $oDadosPagamento->valormulta), 17, "0", STR_PAD_LEFT);
        //juros_encargos
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace(".", "", $oDadosPagamento->jurosencargos), 17, "0", STR_PAD_LEFT);
        //data_vencimento
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace("-", "", $oDadosPagamento->datavencimento), 8, "0", STR_PAD_LEFT);
      break;

      //DARF simples
      case '3':

        //periodo_apuracao
        $oStdLinhaTipoCinco->detalhe_linha5  = str_pad(str_replace("-", "", $oDadosPagamento->periodoapuracao), 8, "0", STR_PAD_LEFT);
        //valor_receita_bruta
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace(".", "", $oDadosPagamento->valorreceitabruta), 17, "0", STR_PAD_LEFT);
        //percentual_receita
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace(".", "", $oDadosPagamento->percentualreceita), 7, "0", STR_PAD_LEFT);
        //valor_principal
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace(".", "", $oDadosPagamento->valorprincipal), 17, "0", STR_PAD_LEFT);
        //valor_multa
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace(".", "", $oDadosPagamento->valormulta), 17, "0", STR_PAD_LEFT);
        //juros_encargos
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_pad(str_replace(".", "", $oDadosPagamento->jurosencargos), 17, "0", STR_PAD_LEFT);
        //campo_branco_DARF_simples
        $oStdLinhaTipoCinco->detalhe_linha5 .= str_repeat(" ", 1);
      break;

      default:
        $oStdLinhaTipoCinco->detalhe_linha5  = "Erro ao gerar detalhamento da linha tipo 5. Verifique o v�nculo com o empagemov.";
      break;
    }

    ////FIM DETALHAMENTO////

    $oStdLinhaTipoTres->tipo_favorecido      = $iTipoFavorecido;
    $oStdLinhaTipoTres->codigo_favorecido    = $sCPFCNPJ;
    $oStdLinhaTipoTres->nome_favorecido      = $oDadosLinha->getNome();
    $oStdLinhaTipoCinco->campo_branco_cinco  = str_repeat(" ", 29);
    $oStdLinhaTipoCinco->observacao_ob       = str_repeat(" ", 40);
    $oStdLinhaTipoCinco->numero_autenticacao = str_repeat(" ", 16);

    $sConvenioAgencia  = $oDadosLinha->getCodigoAgenciaPagadora().$oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $oStdLinhaTipoCinco->convenio_agencia_dv              = $sConvenioAgencia;

    $sConvenioConta    = $oDadosLinha->getContaPagadora().$oDadosLinha->getDigitoVerificadorContaPagadora();
    $oStdLinhaTipoCinco->convenio_conta_dv                = str_pad($sConvenioConta, 10, " ", STR_PAD_RIGHT);
    
    $oStdLinhaTipoCinco->campo_branco_seis                = str_repeat(" ", 7);
    $oStdLinhaTipoCinco->retorno_operacao                 = str_repeat("0", 2);
    $oStdLinhaTipoCinco->numero_sequencial_movimento      = str_pad($this->iSequencialRegistro, 7, "0", STR_PAD_LEFT);
    return $oStdLinhaTipoCinco;
  }


  /**
   * Constr�i o trailer do arquivo
   * @return stdClass
   */
  private function constroiLinhaTrailer() {

    $oStdLinhaTrailer                                = new stdClass();
    $oStdLinhaTrailer->campo_nove                    = str_repeat("9", 35);
    $oStdLinhaTrailer->campo_branco                  = str_repeat(" ", 320);
    /* Plugin ArquivoOBNGRU - Parte9 */
    $oStdLinhaTrailer->somatorio_valores             = str_pad(str_replace(".", "", number_format((float)$this->nValorTotalDasMovimentacoes, 2)), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTrailer->somatorio_sequencia_registros = str_pad($this->iContadorRegistros, 13, "0", STR_PAD_LEFT);
    return $oStdLinhaTrailer;
  }

  /**
   * M�todo que compara se houve altera��o na conta banc�ria
   * @param stdClass $oStdContaAnterior
   * @param stdClass $oStdContaAtual
   * @return boolean
   */
  private function compararContaBancaria($oStdContaAnterior, $oStdContaAtual) {

    if ($oStdContaAnterior->banco         == $oStdContaAtual->c63_banco     &&
        $oStdContaAnterior->agencia       == $oStdContaAtual->c63_agencia   &&
        $oStdContaAnterior->digitoAgencia == $oStdContaAtual->c63_dvagencia &&
        $oStdContaAnterior->conta         == $oStdContaAtual->c63_conta     &&
        $oStdContaAnterior->digitoConta   == $oStdContaAtual->c63_dvconta) {
      return false;
    }
    return true;
  }

  /**
   * Salva os dados do cabe�alho da gera��o do arquivo
   * @throws BusinessException
   * @return boolean
   */
  private function salvarGeracaoArquivo() {

    $iCodigoRemessa      = $this->iCodigoRemessa;
    $oInstituicao        = $this->oInstituicao;
    $dtDataGeracao       = date('d/m/Y', strtotime($this->dtGeracaoArquivo));
    $dtDataProcessamento = date('d/m/Y', strtotime($this->dtAutorizacaoPagamento));
    $sHoraGeracaoArquivo = $this->dtHoraGeracaoArquivo;

    $this->oArquivoTransmissao = new ArquivoTransmissao();
    $this->oArquivoTransmissao->setCodigoRemessa($iCodigoRemessa);
    $this->oArquivoTransmissao->setDataAutorizacaoPagamento(new DBDate($dtDataGeracao));
    $this->oArquivoTransmissao->setDataGeracaoArquivo(new DBDate($dtDataProcessamento));
    $this->oArquivoTransmissao->setHoraGeracaoArquivo($sHoraGeracaoArquivo);
    $this->oArquivoTransmissao->setInstituicao($oInstituicao);
    $this->oArquivoTransmissao->setDescricaoGeracao("Gera��o de Arquivo de Transmiss�o OBN");
    $this->oArquivoTransmissao->salvar();
    $this->iCodigoRemessa = $this->oArquivoTransmissao->getCodigoRemessa();
    return true;
  }

  /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte20] */
  public function salvarGeracaoTxtArquivo() {
  
    if (empty($this->iCodigoRemessa)) {
        throw new BusinessException("ERRO [ 3 ] - Salvando geracao txt do arquivo - N�o foi encontrado c�digo da remessa.");
    }
    
    $oArquivoGerado           = db_utils::getDao("arquivoobngerado");
    
    $iCodGera = $this->iCodigoRemessa;
    
    $rsValidaGeracao = $oArquivoGerado->sql_record($oArquivoGerado->sql_query_file("", "*", null,"codgera = {$iCodGera}"));
    if ($oArquivoGerado->numrows == 0) {
      $oArquivoGerado->codgera  = $this->iCodigoRemessa;
      $oArquivoGerado->datagera = date('d/m/Y');
      $oArquivoGerado->incluir(null);
      if ($oArquivoGerado->erro_status == 0) {
        throw new BusinessException("N�o foi poss�vel gravar gera��o do arquivo.");
      }
    }
  
    return true;
  }
  /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte20] */

  /**
   * Vincula os movimentos ao cabe�alho da gera��o do arquivo
   * @param unknown $aMovimentosAgenda
   * @throws BusinessException
   * @return boolean
   */
  private function vincularMovimentosNaGeracao($aMovimentosAgenda) {

    $iAno         = $this->iAno;
    $iInstituicao = $this->oInstituicao->getSequencial();

    foreach ($aMovimentosAgenda as $iCodigoMovimento) {

//       $oMovimento = MovimentoArquivoTransmissao::getInstance($iCodigoMovimento, $iAno, $iInstituicao);
      $this->oArquivoTransmissao->vinculaMovimento($iCodigoMovimento);
    }
    return true;
  }

  /**
   * Busca os dados do header do arquivo retornando um objeto stdClass
   * @return stdClass
   */
  private function constroiDadosHeader($iCodigoConvenio) {

    if (empty($this->iSequencialArquivo)) {
      $this->iSequencialArquivo = $this->getCodigoSequencialArquivo()->o150_proximonumero;
    }

    list($iAno, $iMes, $iDia) = explode("-", $this->dtGeracaoArquivo);
    $oStdDadosHeader                                = new stdClass();
    $oStdDadosHeader->campo_zero                    = str_repeat("0", 35);
    $oStdDadosHeader->data_geracao_arquivo          = "{$iDia}{$iMes}{$iAno}";
    $oStdDadosHeader->hora_geracao_arquivo          = str_replace(":", "", $this->dtHoraGeracaoArquivo);
    $oStdDadosHeader->numero_remessa                = str_pad($this->iSequencialArquivo, 5, "0", STR_PAD_LEFT);
    $oStdDadosHeader->campo_exclusivo_header        = "10E001";
    $oStdDadosHeader->numero_contrato_banco_cliente = str_pad($iCodigoConvenio, 9, "0", STR_PAD_LEFT);
    $oStdDadosHeader->campo_branco                  = str_repeat(" ", 276);
    $oStdDadosHeader->numero_sequencial_arquivo     = str_pad($this->iSequencialRegistro, 7, "0", STR_PAD_LEFT);
    return $oStdDadosHeader;
  }

  /**
   * Fun��o que controi um objeto do tipo MovimentoArquivoTransmissao, para que seja usado no gerador de arquivo obn
   * @param MovimentoArquivoTransmissao $oDadosMovimento
   * @return stdClass
   */
  private function constroiLinhaTipoUm(MovimentoArquivoTransmissao $oDadosMovimento) {

    $oInstituicao                  = $this->getInstituicao();
    $sAgenciaDigitoContaBancaria   = $oDadosMovimento->getCodigoAgenciaPagadora();
    $sAgenciaDigitoContaBancaria  .= $oDadosMovimento->getDigitoVerificadorAgenciaPagadora();
    $sContaBancaria                = $oDadosMovimento->getContaPagadora();
    $sContaBancaria               .= $oDadosMovimento->getDigitoVerificadorContaPagadora();
    $sContaBancaria                = str_pad($sContaBancaria, 10, "0", STR_PAD_LEFT);

    $oStdRegistroTipoUm                                              = new stdClass();
    $oStdRegistroTipoUm->campo_branco_ultimo                         = str_repeat(" ", 251);
    $oStdRegistroTipoUm->descricao_instituicao                       = substr($oInstituicao->getDescricao(), 0, 45);
    $oStdRegistroTipoUm->campo_branco                                = str_repeat(" ", 26);
    $oStdRegistroTipoUm->conta_instituicao                           = $sContaBancaria;
    $oStdRegistroTipoUm->codigo_instituicao_emitente_obs             = "";
    $oStdRegistroTipoUm->codigo_agenciabancaria_instituicao_emitente = $sAgenciaDigitoContaBancaria;
    $oStdRegistroTipoUm->identificador_linha                         = 1;
    return $oStdRegistroTipoUm;
  }

  private function buscaCodigoArquivoRemessa () {

    if (isset($this->iCodigoRemessa)) {

      $oDaoEmpAgeGeraObn = db_utils::getDao("empagegeraobn");
      $sSqlNumeracao     = $oDaoEmpAgeGeraObn->sql_query_file (null, "e138_numeracaoobn", null, "e138_empagegera = {$this->iCodigoRemessa}");
      $rsNumeracao       = $oDaoEmpAgeGeraObn->sql_record($sSqlNumeracao);
      if ($oDaoEmpAgeGeraObn->numrows == 0 ) {
        throw new BusinessException("ERRO [ 0 ] - Regerando arquivo - Vinculo de remessa com numera��o n�o encontrado.");
      }
      return db_utils::fieldsMemory($rsNumeracao, 0)->e138_numeracaoobn;
    }
  }

  /**
   * Retorna o sequencial do arquivo
   * @throws BusinessException
   * @return object
   */
  private function getCodigoSequencialArquivo() {

    $oDaoConfiguracaoOBN = db_utils::getDao("obnnumeracao");
    $iInstituicao        = $this->getInstituicao()->getSequencial();
    $sWhere              = "o150_instit = {$iInstituicao}";
    $sSqlBuscaSequencial = $oDaoConfiguracaoOBN->sql_query_file(null, "*", null, $sWhere);
    $rsBuscaSequencial   = $oDaoConfiguracaoOBN->sql_record($sSqlBuscaSequencial);

    if ($oDaoConfiguracaoOBN->erro_status == "0") {
      throw new BusinessException("Erro [ 0 ]: erro ao buscar sequencial do arquivo. {$oDaoConfiguracaoOBN->erro_msg}");
    }
    $oCodigoProximoNumero = db_utils::fieldsMemory($rsBuscaSequencial, 0);

    return $oCodigoProximoNumero;
  }

  /**
   * atualiza o sequencial do arquivo, apos a gera��o;
   */
  private function setCodigoSequencialArquivo() {

    $oDaoConfiguracaoOBN = db_utils::getDao("obnnumeracao");
    $iInstituicao        = $this->getInstituicao()->getSequencial();
    $iNumeroAtual        = $this->getCodigoSequencialArquivo()->o150_proximonumero;
    $iSequencial         = $this->getCodigoSequencialArquivo()->o150_sequencial;
    $iProximoNumero      = $iNumeroAtual + 1;

    $oDaoConfiguracaoOBN->o150_sequencial    = $iSequencial;
    $oDaoConfiguracaoOBN->o150_proximonumero = $iProximoNumero;
    $oDaoConfiguracaoOBN->alterar($oDaoConfiguracaoOBN->o150_sequencial);
    if ($oDaoConfiguracaoOBN->erro_status == '0') {
      throw new DBException("ERRO [ 0 ] - atualizando c�digo proximo arquivo - " . $oDaoConfiguracaoOBN->erro_msg );
    }
  }
}