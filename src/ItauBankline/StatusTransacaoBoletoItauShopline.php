<?php
namespace ItauBankline;

class StatusTransacaoBoletoItauShopline
{
	
	const PAGAMENTO_EFETUADO 							= '00';
	const SITUACAO_NAO_FINALIZADA 						= '01';
	const ERRO_NO_PROCESSAMENTO 						= '02';
	const PAGAMENTO_NAO_LOCALIZADO 						= '03';
	const BOLETO_EMITIDO_COM_SUCESSO 					= '04';
	const PAGAMENTO_EFETUADO_AGUARDANDO_COMPENSACAO 	= '05';
	const PAGAMENTO_NAO_COMPENSADO 						= '06';
	const PAGAMENTO_PARCIAL     						= '07';
	
	public static function getDescricao($codStatus){
		
		switch($codStatus) {
			case self::PAGAMENTO_EFETUADO: return 'Pagamento efetuado';
			case self::SITUACAO_NAO_FINALIZADA: return 'Situação de pagamento não finalizada (tente novamente)';
			case self::ERRO_NO_PROCESSAMENTO: return 'Erro no processamento da consulta (tente novamente)';
			case self::PAGAMENTO_NAO_LOCALIZADO: return 'Pagamento não localizado (consulta fora de prazo ou pedido não registrado no banco)';
			case self::BOLETO_EMITIDO_COM_SUCESSO: return 'Boleto emitido com sucesso';
			case self::PAGAMENTO_EFETUADO_AGUARDANDO_COMPENSACAO: return 'Pagamento efetuado, aguardando compensação';
			case self::PAGAMENTO_NAO_COMPENSADO: return 'Pagamento não compensado';
			case self::PAGAMENTO_PARCIAL: return 'Pagamento parcial';
		}
		
		throw new \Exception("Código de status $codStatus não encontrado");
		
	}
	
}