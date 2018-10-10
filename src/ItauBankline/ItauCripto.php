<?php
namespace ItauBankline;

/**
 * 
 * @author Dilnei
 *
 */
class ItauCripto
{
	
	private $sbox;
	private $key;
	private $codEmp;
	private $numPed;
	private $tipPag;
	private $CHAVE_ITAU = "SEGUNDA12345ITAU";
	private $TAM_COD_EMP = 26;
	private $TAM_CHAVE = 16;
	private $dados;
	
	public function __construct(){
		$this->sbox = array();
		$this->key = array();

		$this->numPed = "";
		$this->tipPag = "";
		$this->codEmp = "";
	}
	
	//$dados, $chave
	private function Algoritmo($paramString1, $paramString2) {
		$paramString2 = strtoupper($paramString2);
	
		$k = 0;
		$m = 0;
	
		$str = "";
		$this->Inicializa($paramString2);
	
		for ($j = 1; $j <= strlen($paramString1); $j++) {
			$k = ($k + 1) % 256;
			$m = ($m + $this->sbox[$k]) % 256;
			$i = $this->sbox[$k];
			$this->sbox[$k] = $this->sbox[$m];
			$this->sbox[$m] = $i;
	
			$n = $this->sbox[(($this->sbox[$k] + $this->sbox[$m]) % 256)];
	
			$i1 = (ord(substr($paramString1, ($j - 1), 1)) ^ $n);
	
			$str = $str . chr($i1);
		}
	
		return $str;
	}
	
	private function PreencheBranco($paramString, $paramInt) {
		$str = $paramString . "";
	
		while (strlen($str) < $paramInt) {
			$str = $str . " ";
		}
	
		return substr($str, 0, $paramInt);
	}
	
	private function PreencheZero($paramString, $paramInt) {
		$str = $paramString . "";
	
		while (strlen($str) < $paramInt) {
			$str = "0" . $str;
		}
	
		return substr($str, 0, $paramInt);
	}
	
	private function Inicializa($paramString) {
		$m = strlen($paramString);
	
		for ($j = 0; $j <= 255; $j++) {
			$this->key[$j] = substr($paramString, ($j % $m), 1);
			$this->sbox[$j] = $j;
		}
	
		$k = 0;
	
		for ($j = 0; $j <= 255; $j++) {
			$k = ($k + $this->sbox[$j] + ord($this->key[$j])) % 256;
			$i = $this->sbox[$j];
			$this->sbox[$j] = $this->sbox[$k];
			$this->sbox[$k] = $i;
		}
	}
	
	//Função criada para substituir o Math.random() do Java
	//Retorna um número entre 0.0 e 0.9999999999 (equivalente ao Double do Java, mas com menor precisão)
	private function JavaRandom() {
		return rand(0, 999999999) / 1000000000;
	}
	
	//Retira as letras acentuadas e substitui pelas não acentuadas
	private function TiraAcento($string) {
		
		if ( !preg_match('/[\x80-\xff]/', $string) )
			return $string;
		
			$chars = array(
					// Decompositions for Latin-1 Supplement
					chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
					chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
					chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
					chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
					chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
					chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
					chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
					chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
					chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
					chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
					chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
					chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
					chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
					chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
					chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
					chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
					chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
					chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
					chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
					chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
					chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
					chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
					chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
					chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
					chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
					chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
					chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
					chr(195).chr(191) => 'y',
					// Decompositions for Latin Extended-A
					chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
					chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
					chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
					chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
					chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
					chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
					chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
					chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
					chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
					chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
					chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
					chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
					chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
					chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
					chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
					chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
					chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
					chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
					chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
					chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
					chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
					chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
					chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
					chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
					chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
					chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
					chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
					chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
					chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
					chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
					chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
					chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
					chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
					chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
					chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
					chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
					chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
					chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
					chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
					chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
					chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
					chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
					chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
					chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
					chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
					chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
					chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
					chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
					chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
					chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
					chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
					chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
					chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
					chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
					chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
					chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
					chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
					chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
					chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
					chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
					chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
					chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
					chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
					chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
			);
		
			return strtr($string, $chars);
		
	}
	
	private function Converte($paramString) {
		$c = chr(floor(26.0 * $this->JavaRandom() + 65.0));
		$str = "" . $c;
	
		for ($i = 0; $i < strlen($paramString); $i++) {
			$k = substr($paramString, $i, 1);
			$j = ord($k);
			$str = $str . $j;
			$c = chr(floor(26.0 * $this->JavaRandom() + 65.0));
			$str = $str . $c;
		}
	
		return $str;
	}
	
	private function Desconverte($paramString) {
		$str1 = "";
	
		for ($i = 0; $i < strlen($paramString); $i++) {
			$str2 = "";
	
			$c = substr($paramString, $i, 1);
	
			while (is_numeric($c)) {
				$str2 = $str2 . substr($paramString, $i, 1);
				$i += 1;
				$c = substr($paramString, $i, 1);
			}
	
			if ($str2 != "") {
				$j = $str2 + 0;
				$str1 = $str1 . chr($j);
			}
		}
	
		return $str1;
	}
	
	/**
	  $pedido // Identificação do pedido - máximo de 8 dígitos (12345678) - Obrigatório  
	  $valor // Valor do pedido - máximo de 8 dígitos + vírgula + 2 dígitos - 99999999,99 - Obrigatório  
	  $observacao // Observações - máximo de 40 caracteres  
	  $nomeSacado // Nome do sacado - máximo de 30 caracteres  
	  $codigoInscricao // Código de Inscrição: 01->CPF, 02->CNPJ  
	  $numeroInscricao // Número de Inscrição: CPF ou CNPJ - até 14 caracteres  
	  $enderecoSacado // Endereco do Sacado - máximo de 40 caracteres  
	  $bairroSacado // Bairro do Sacado - máximo de 15 caracteres  
	  $cepSacado // Cep do Sacado - máximo de 8 dígitos  
	  $cidadeSacado // Cidade do sacado - máximo 15 caracteres  
	  $estadoSacado // Estado do Sacado - 2 caracteres  
	  $dataVencimento // Vencimento do título - 8 dígitos - ddmmaaaa  
	  $urlRetorna // URL do retorno - máximo de 60 caracteres  
	  $obsAdicional1 // ObsAdicional1 - máximo de 60 caracteres  
	  $obsAdicional2 // ObsAdicional2 - máximo de 60 caracteres  
	  $obsAdicional3 // ObsAdicional3 - máximo de 60 caracteres
	  
	  $dados = $cripto->geraDados($codEmp,$pedido,$valor,$observacao,$chave,$nomeSacado,$codigoInscricao,$numeroInscricao,$enderecoSacado,$bairroSacado,$cepSacado,$cidadeSacado,$estadoSacado,$dataVencimento,$urlRetorna,$obsAd1,$obsAd2,$obsAd3);
	 */
	public function geraDados($paramString1, $paramString2, $paramString3, $paramString4, $paramString5, $paramString6, $paramString7, $paramString8, $paramString9, $paramString10, $paramString11, $paramString12, $paramString13, $paramString14, $paramString15, $paramString16, $paramString17, $paramString18) {
		
		$paramString1 = strtoupper($paramString1);
		$paramString5 = strtoupper($paramString5);

		if (strlen($paramString1) != $this->TAM_COD_EMP) {
			throw new \Exception("Erro: tamanho do codigo da empresa diferente de 26 posições.");
		}

		if (strlen($paramString5) != $this->TAM_CHAVE) {
			throw new \Exception("Erro: tamanho da chave da chave diferente de 16 posições.");
		}

		if ((strlen($paramString2) < 1) || (strlen($paramString2) > 8)) {
			throw new \Exception("Erro: número do pedido inválido.");
		}

		if (is_numeric($paramString2)) {
			$paramString2 = $this->PreencheZero($paramString2, 8);
		} else {
			throw new \Exception("Erro: numero do pedido não é numérico.");
		}

		if ((strlen($paramString3) < 1) || (strlen($paramString3) > 11)) {
			throw new \Exception("Erro: valor da compra inválido.");
		}

		$i = strpos($paramString3, ',');

		if ($i !== FALSE) {
			$str3 = substr($paramString3, ($i + 1));

			if (!is_numeric($str3)) {
				throw new \Exception("Erro: valor decimal não é numérico.");
			}

			if (strlen($str3) != 2) {
				throw new \Exception("Erro: valor decimal da compra deve possuir 2 posições após a virgula.");
			}

			$paramString3 = substr($paramString3, 0, strlen($paramString3) - 3) . $str3;
		} else {
			if (!is_numeric($paramString3)) {
				throw new \Exception("Erro: valor da compra não é numérico.");
			}

			if (strlen($paramString3) > 8) {
				throw new \Exception("Erro: valor da compra deve possuir no máximo 8 posições antes da virgula.");
			}

			$paramString3 = $paramString3 . "00";
		}

		$paramString3 = $this->PreencheZero($paramString3, 10);

		$paramString7 = trim($paramString7);

		if (($paramString7 != "02") && ($paramString7 != "01") && ($paramString7 != "")) {
			throw new \Exception("Erro: código de inscrição inválido.");
		}

		if (($paramString8 != "") && (!is_numeric($paramString8)) && (strlen($paramString8) > 14)) {
			throw new \Exception("Erro: número de inscrição inválido.");
		}

		if (($paramString11 != "") && ((!is_numeric($paramString11)) || (strlen($paramString11) != 8))) {
			throw new \Exception("Erro: cep inválido.");
		}

		if (($paramString14 != "") && ((!is_numeric($paramString14)) || (strlen($paramString14) != 8))) {
			throw new \Exception("Erro: data de vencimento inválida.");
		}

		if (strlen($paramString16) > 60) {
			throw new \Exception("Erro: observação adicional 1 inválida.");
		}

		if (strlen($paramString17) > 60) {
			throw new \Exception("Erro: observação adicional 2 inválida.");
		}

		if (strlen($paramString18) > 60) {
			throw new \Exception("Erro: observação adicional 3 inválida.");
		}

		//Retira os acentos
		$paramString4 = $this->TiraAcento($paramString4);
		$paramString6 = $this->TiraAcento($paramString6);
		$paramString9 = $this->TiraAcento($paramString9);
		$paramString10 = $this->TiraAcento($paramString10);
		$paramString12 = $this->TiraAcento($paramString12);
		$paramString16 = $this->TiraAcento($paramString16);
		$paramString17 = $this->TiraAcento($paramString17);
		$paramString18 = $this->TiraAcento($paramString18);
		
		ddd($paramString4,$paramString6, $paramString9, $paramString10, $paramString12, $paramString16, $paramString17, $paramString18);
		
		$paramString4 = $this->PreencheBranco($paramString4, 40);
		$paramString6 = $this->PreencheBranco($paramString6, 30);
		$paramString7 = $this->PreencheBranco($paramString7, 2);
		$paramString8 = $this->PreencheBranco($paramString8, 14);
		$paramString9 = $this->PreencheBranco($paramString9, 40);
		$paramString10 = $this->PreencheBranco($paramString10, 15);
		$paramString11 = $this->PreencheBranco($paramString11, 8);
		$paramString12 = $this->PreencheBranco($paramString12, 15);
		$paramString13 = $this->PreencheBranco($paramString13, 2);
		$paramString14 = $this->PreencheBranco($paramString14, 8);
		$paramString15 = $this->PreencheBranco($paramString15, 60);
		$paramString16 = $this->PreencheBranco($paramString16, 60);
		$paramString17 = $this->PreencheBranco($paramString17, 60);
		$paramString18 = $this->PreencheBranco($paramString18, 60);
		
		$str1 = $this->Algoritmo($paramString2 . $paramString3 . $paramString4 . $paramString6 . $paramString7 . $paramString8 . $paramString9 . $paramString10 . $paramString11 . $paramString12 . $paramString13 . $paramString14 . $paramString15 . $paramString16 . $paramString17 . $paramString18, $paramString5);

		$str2 = $this->Algoritmo($paramString1 . $str1, $this->CHAVE_ITAU);

		return $this->Converte($str2);
	}

	public function geraCripto($paramString1, $paramString2, $paramString3) {
		
		if (strlen($paramString1) != $this->TAM_COD_EMP) {
			throw new \Exception("Erro: tamanho do codigo da empresa diferente de 26 posições.");
		}

		if (strlen($paramString3) != $this->TAM_CHAVE) {
			throw new \Exception("Erro: tamanho da chave da chave diferente de 16 posições.");
		}

		$paramString2 = trim($paramString2);

		if ($paramString2 == "") {
			throw new \Exception("Erro: código do sacado inválido.");
		}

		$str1 = $this->Algoritmo($paramString2, $paramString3);

		$str2 = $this->Algoritmo($paramString1 . $str1, $this->CHAVE_ITAU);

		return $this->Converte($str2);
	}

	/**
	 * 
	 * @param string $paramString1 Código da Empresa
	 * @param string $paramString2 Número do pedido
	 * @param string $paramString3 Método de consulta, 0 para exibir html e 1 para exibir xml
	 * @param string $paramString4 Chave de criptografia
	 * @throws \Exception
	 */
	public function geraConsulta($paramString1, $paramString2, $paramString3, $paramString4) {
		
		if (strlen($paramString1) != $this->TAM_COD_EMP) {
			throw new \Exception("Erro: tamanho do codigo da empresa diferente de 26 posições.");
		}

		if (strlen($paramString4) != $this->TAM_CHAVE) {
			throw new \Exception("Erro: tamanho da chave da chave diferente de 16 posições.");
		}

		if ((strlen($paramString2) < 1) || (strlen($paramString2) > 8)) {
			throw new \Exception("Erro: número do pedido inválido.");
		}

		if (is_numeric($paramString2)) {
			$paramString2 = $this->PreencheZero($paramString2, 8);
		} else {
			throw new \Exception("Erro: numero do pedido não é numérico.");
		}

		if (($paramString3 != "0") && ($paramString3 != "1")) {
			throw new \Exception("Erro: formato de consulta inválido.");
		}

		$str1 = $this->Algoritmo($paramString2 . $paramString3, $paramString4);

		$str2 = $this->Algoritmo($paramString1 . $str1, $this->CHAVE_ITAU);

		return $this->Converte($str2);
	}

	//$dados, $chave
	public function decripto($paramString1, $paramString2) {
		//A chave precisa sempre estar em maiusculo
		$paramString2 = strtoupper($paramString2);

		$paramString1 = $this->Desconverte($paramString1);

		$str = $this->Algoritmo($paramString1, $paramString2);

		$this->codEmp = substr($str, 0, 26);

		$this->numPed = substr($str, 26, 8);

		$this->tipPag = substr($str, 34, 2);

		return $str;
	}

	public function retornaCodEmp() {
		return $this->codEmp;
	}

	public function retornaPedido() {
		return $this->numPed;
	}

	public function retornaTipPag() {
		return $this->tipPag;
	}

	public function geraDadosGenerico($paramString1, $paramString2, $paramString3) {
		
		$paramString1 = strtoupper($paramString1);
		$paramString3 = strtoupper($paramString3);

		if (strlen($paramString1) != $this->TAM_COD_EMP) {
			throw new \Exception("Erro: tamanho do codigo da empresa diferente de 26 posições.");
		}

		if (strlen($paramString3) != $this->TAM_CHAVE) {
			throw new \Exception("Erro: tamanho da chave da chave diferente de 16 posições.");
		}

		if (strlen($paramString2) < 1) {
			throw new \Exception("Erro: sem dados.");
		}

		$str1 = $this->Algoritmo($paramString2, $paramString3);

		$str2 = $this->Algoritmo($paramString1 . $str1, $this->CHAVE_ITAU);

		return $this->Converte($str2);
		
	}
	
}