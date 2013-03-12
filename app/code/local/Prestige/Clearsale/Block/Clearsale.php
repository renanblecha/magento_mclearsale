<?php

class Prestige_Clearsale_Block_Clearsale extends Mage_Adminhtml_Block_Template
{
	
	public function getOrder()
	{
		$order_id = $this->getRequest()->getParam('order_id');
		
		if ($order_id){
			return Mage::getModel('sales/order')->load($order_id);
		}else{
			return false;
		}				
	}	
	

    public function _afterToHtml($html)
    {
    	
    	$html = parent::_afterToHtml();
    	
    	
    	if (Mage::getStoreConfig('payment_services/clearsale/active')){
			$_clearsale = array();
			$_order = $this->getOrder();
			
			if ($_order){
				$_order_data = $_order->getData();	
		
		        $metodoPag = $_order->getPayment()->getMethodInstance()->getCode();
		        
		        if ($metodoPag){	
		        	
		        	//DADOS DO PEDIDO
		        	$_clearsale['Dados_Pedido']['PedidoID'] = $_order->getIncrementId();
		        	$_clearsale['Dados_Pedido']['Data'] = str_ireplace(array(' ','/'), array('+','-'), $_order->getCreatedAtDate());
		        	$_clearsale['Dados_Pedido']['IP'] = $_order->getRemoteIp();
		        	$_clearsale['Dados_Pedido']['Total'] = number_format($_order->getBaseGrandTotal(), 2, '', '');
		        		        
			        switch ($metodoPag) {
			        	case 'Maxima_Cielo_Cc':
			        		$type = $_order->getPayment()->getAdditionalInformation('Cielo_cardType');
			        		$_clearsale['Dados_Pedido']['TipoPagamento'] = 1;
			        		switch ($type) {
			        			case 'diners':
			        				$_clearsale['Dados_Pedido']['TipoCartao'] = 1;
			        			break;
			        			case 'mastercard':
			        				$_clearsale['Dados_Pedido']['TipoCartao'] = 2;
			        			break;
			        			case 'visa':
			        				$_clearsale['Dados_Pedido']['TipoCartao'] = 3;
			        			break;
			        			case 'amex':
			        				$_clearsale['Dados_Pedido']['TipoCartao'] = 5;
			        			break;
			        			default:
			        				$_clearsale['Dados_Pedido']['TipoCartao'] = 4;
			        			break;
			        		}
			        		$_clearsale['Dados_Pedido']['Parcelas'] = $_order->getPayment()->getAdditionalInformation('Cielo_installments');
			        	break;
			        	case 'boleto_bancario':
			        		$_clearsale['Dados_Pedido']['TipoPagamento'] = 2;
			        		$_clearsale['Dados_Pedido']['Parcelas'] = 1;
			        	break;
			        	default:
			        		$_clearsale['Dados_Pedido']['TipoPagamento'] = 14;
			        		$_clearsale['Dados_Pedido']['Parcelas'] = 1;
			        	break;
			        }
			        
			        //DADOS DE COBRANCA
			        $endereco_cobranca = $_order->getBillingAddress();	        
			        
			        $customer_id = $_order_data['customer_id'];
			        $_customer   = Mage::getModel('customer/customer')->load($customer_id);
			        
			        $array_from = array( 'Ã€', 'Ã�', 'Ãƒ', 'Ã‚', 'Ã‰', 'ÃŠ', 'Ã�', 'Ã“', 'Ã•', 'Ã”', 'Ãš', 'Ãœ', 'Ã‡', 'Ã ', 'Ã¡', 'Ã£', 'Ã¢', 'Ã©', 'Ãª', 'Ã­', 'Ã³', 'Ãµ', 'Ã´', 'Ãº', 'Ã¼', 'Ã§','Á', 'À', 'Ã', 'Â', 'É', 'Ê', 'Í', 'Ó', 'Õ', 'Ô', 'Ú', 'Ü', 'Ç', 'á', 'à', 'ã', 'â', 'é', 'ê', 'í', 'ó', 'õ', 'ô', 'ú', 'ü', 'ç');
			        $array_to   = array( 'A', 'A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'C', 'a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'c','A', 'A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'C', 'a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'c' );
			        
			        $_clearsale['Dados_Cobranca']['Cobranca_Nome'] = strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $endereco_cobranca->getName())));
			        $_clearsale['Dados_Cobranca']['Cobranca_Email'] = str_ireplace(' ', '+', $endereco_cobranca->getEmail());
			        
			        if ($_customer->getCnpj()) $_clearsale['Dados_Cobranca']['Cobranca_Documento'] = str_ireplace(' ', '+', $_customer->getCnpj());
			        else  $_clearsale['Dados_Cobranca']['Cobranca_Documento'] = str_ireplace(' ', '+', $_customer->getCpf());
			        
			        $_clearsale['Dados_Cobranca']['Cobranca_Logradouro'] = strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $endereco_cobranca->getStreet(1))));
			        $_clearsale['Dados_Cobranca']['Cobranca_Logradouro_Numero'] = strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $endereco_cobranca->getStreet(2))));
			        $_clearsale['Dados_Cobranca']['Cobranca_Logradouro_Complemento'] = strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $endereco_cobranca->getStreet(4))));
			        $_clearsale['Dados_Cobranca']['Cobranca_Bairro'] = strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $endereco_cobranca->getStreet(3))));
			        	        
			        
			        $_clearsale['Dados_Cobranca']['Cobranca_Cidade'] = strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $endereco_cobranca->getCity())));
			        
			        $_read = Mage::getSingleton('core/resource')->getConnection('core_read');
			        $region = $_read->fetchRow('SELECT * FROM '.Mage::getConfig()->getTablePrefix().'directory_country_region WHERE default_name = "'.$endereco_cobranca->getRegion().'"');
			        
			        $_clearsale['Dados_Cobranca']['Cobranca_Estado'] = str_ireplace(' ', '+', $region['code']);
			        $_clearsale['Dados_Cobranca']['Cobranca_CEP'] = str_ireplace('-', '', $endereco_cobranca->getPostcode());
			        
			        switch( $endereco_cobranca->getCountryId() )
			        {
			        	case "BR":
			        		$_clearsale['Dados_Cobranca']['Cobranca_Pais'] = "BRA";
			        		break;
			        }		        
			        
			        $telefoneTemp = trim($_customer->getTelefone());
			        $telefoneTemp = explode(' ', $telefoneTemp);
			        
			        $_clearsale['Dados_Cobranca']['Cobranca_DDD_Telefone'] = intval(str_replace(array('(',')'), array('',''), $telefoneTemp[0]));
			        $_clearsale['Dados_Cobranca']['Cobranca_Telefone'] =  intval($telefoneTemp[1]);
			        
			        $celularTemp = trim($_customer->getCelular());
			        $celularTemp = explode(' ', $celularTemp);
			        
			        $_clearsale['Dados_Cobranca']['Cobranca_DDD_Celular'] = intval(str_replace(array('(',')'), array('',''), $celularTemp[0]));
			        $_clearsale['Dados_Cobranca']['Cobranca_Celular'] =  intval($celularTemp[1]);      	        
			        
			        //DADOS DE ENTREGA
			        $endereco_entrega  = $_order->getShippingAddress();
			        
			        $_clearsale['Dados_Cobranca']['Cobranca_Nome'] = strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $endereco_cobranca->getName())));
			        $_clearsale['Dados_Cobranca']['Cobranca_Email'] = str_ireplace(' ', '+', $endereco_cobranca->getEmail());
			        
			        if ($_customer->getCnpj()) $_clearsale['Dados_Cobranca']['Cobranca_Documento'] = str_ireplace(' ', '+', $_customer->getCnpj());
			        else  $_clearsale['Dados_Cobranca']['Cobranca_Documento'] = str_ireplace(' ', '+', $_customer->getCpf());
			        
			        $_clearsale['Dados_Cobranca']['Cobranca_Logradouro'] = strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $endereco_cobranca->getStreet(1))));
			        $_clearsale['Dados_Cobranca']['Cobranca_Logradouro_Numero'] = strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $endereco_cobranca->getStreet(2))));
			        $_clearsale['Dados_Cobranca']['Cobranca_Logradouro_Complemento'] = strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $endereco_cobranca->getStreet(4))));
			        $_clearsale['Dados_Cobranca']['Cobranca_Bairro'] = strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $endereco_cobranca->getStreet(3))));
			        	        
			        
			        $_clearsale['Dados_Cobranca']['Cobranca_Cidade'] = strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $endereco_cobranca->getCity())));
			        
			        $_read = Mage::getSingleton('core/resource')->getConnection('core_read');
			        $region = $_read->fetchRow('SELECT * FROM '.Mage::getConfig()->getTablePrefix().'directory_country_region WHERE default_name = "'.$endereco_cobranca->getRegion().'"');
			        
			        $_clearsale['Dados_Cobranca']['Cobranca_Estado'] = str_ireplace(' ', '+', $region['code']);
			        $_clearsale['Dados_Cobranca']['Cobranca_CEP'] = str_ireplace('-', '', $endereco_cobranca->getPostcode());
			        
			        switch( $endereco_cobranca->getCountryId() )
			        {
			        	case "BR":
			        		$_clearsale['Dados_Cobranca']['Cobranca_Pais'] = "BRA";
			        		break;
			        }		        
			        
			        $telefoneTemp = trim($_customer->getTelefone());
			        $telefoneTemp = explode(' ', $telefoneTemp);
			        
			        $_clearsale['Dados_Cobranca']['Cobranca_DDD_Telefone'] = intval(str_replace(array('(',')'), array('',''), $telefoneTemp[0]));
			        $_clearsale['Dados_Cobranca']['Cobranca_Telefone'] =  intval($telefoneTemp[1]);
			        
			        $celularTemp = trim($_customer->getCelular());
			        $celularTemp = explode(' ', $celularTemp);
			        
			        $_clearsale['Dados_Cobranca']['Cobranca_DDD_Celular'] = intval(str_replace(array('(',')'), array('',''), $celularTemp[0]));
			        $_clearsale['Dados_Cobranca']['Cobranca_Celular'] =  intval($celularTemp[1]);   
			        
			        
			        //DADOS DOS ITENS
			        $i = 1;
			        $items = $_order->getAllVisibleItems();
			        
			        if ($items){
			        	foreach ($items as $item){
			        		$item_price = 0;
	        		        $item_qty = $item->getQtyOrdered() * 1;
	        		        if ($children = $item->getChildrenItems()) {
	        		            foreach ($children as $child) {
	        		                $item_price += $child->getBasePrice() * $child->getQtyOrdered() / $item_qty;
	        		            }
	        		            $item_price = $this->formatNumber($item_price);
	        		        }
	        		        if (!$item_price) {
	        		        	$item_price = $this->formatNumber($item->getBasePrice());
	        		        }
	        		        
	        		        $_clearsale['Dados_Item']['Item_ID_'.$i] = substr($item->getSku(), 0, 50);
	        		        $_clearsale['Dados_Item']['Item_Nome_'.$i] = substr(strtoupper(str_ireplace(' ', '+', str_replace($array_from, $array_to, $item->getName()))), 0, 150);
	        		        $_clearsale['Dados_Item']['Item_Qtd_'.$i] = $item_qty;
	        		        $_clearsale['Dados_Item']['Item_Valor_'.$i] = number_format(($item_price/100),2,'','');
	        		        
	        		        $i++;
			        	}
			        }
			        
			        $ambiente				  = Mage::getStoreConfig('payment_services/clearsale/ambiente');
			        $codIntegracao            = Mage::getStoreConfig('payment_services/clearsale/codigo_integracao');
			        
			        if( $ambiente == 1 )
	                {
	        			$urlClearsale = "http://www.clearsale.com.br/integracaov2/FreeClearSale/frame.aspx";
	        		}else{
	        			$urlClearsale = "http://homologacao.clearsale.com.br/integracaov2/FreeClearSale/frame.aspx";
	        		}
	        		
	        		$url = $urlClearsale;
	        		$url.= '?CodigoIntegracao='.$codIntegracao;
	        		
	        		foreach ($_clearsale as $section){
	        			if (is_array($section)){
	        				foreach ($section as $key => $value){
	        					$url.= '&'.$key.'='.$value;
	        				}
	        			}
	        		}
	        		
	        		$html_meio = '<br /><iframe height="85" frameborder="0" width="280" scrolling="no" src="'. $url .'">no"><p>Seu Browser não suporta iframes</p></iframe>';
	        		
	        		
	        		return $html.$html_meio;
		        }
			}
    	}
    	
    	return $html;
    }


	function formatNumber ($number)
	{
		return sprintf('%.2f', (double) $number) * 100;
	}

}