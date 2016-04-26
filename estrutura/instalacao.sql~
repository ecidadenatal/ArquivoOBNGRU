create table plugins.tipopagamento (sequencial integer,
									  codtipo integer, 
                                      descricao varchar(20));
create sequence plugins.tipopagamento_sequencial_seq; 

insert into plugins.tipopagamento values (nextval('plugins.tipopagamento_sequencial_seq'), 1, 'GPS');
insert into plugins.tipopagamento values (nextval('plugins.tipopagamento_sequencial_seq'), 2, 'DARF');
insert into plugins.tipopagamento values (nextval('plugins.tipopagamento_sequencial_seq'), 3, 'DARF Simples');

create table plugins.empagemovpagamento (sequencial integer, 
                                                     empagemov integer not null references empagemov(e81_codmov), 
                                                     tipopagamento integer not null,
                                                     codreceita varchar(6),
													 codidentificacao varchar(2),   
													 periodoapuracao date,
													 datavencimento date,
													 mesanocompetencia varchar(7),
													 numreferencia numeric,
													 valorINSS numeric,          
													 valoroutras numeric,        
													 atualizacaomonetaria numeric,
													 valorreceitabruta numeric,
													 percentualreceita numeric,
													 valorprincipal numeric,
													 valormulta numeric,
													 jurosencargos numeric
													);

create sequence plugins.empagemovpagamento_sequencial_seq; 

create table plugins.empagemovdetalhetransmissaoautenticacao (sequencial integer,
									  							empagemovdetalhetransmissao integer, 
                                      							numautenticacao varchar(16));
create sequence plugins.empagemovdetalhetransmissaoautenticacao_sequencial_seq; 

create table plugins.empagemovpagamentoautenticacao (sequencial integer,
									  							empagemovpagamento integer, 
                                      							numautenticacao varchar(16));
create sequence plugins.empagemovpagamentoautenticacao_sequencial_seq; 