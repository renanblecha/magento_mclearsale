magento_mclearsale
==================

<p>Módulo que integra o controle anti-fraude M-ClearSale</p>

no arquivo app/design/adminhtml/default/default/template/sales/order/view/info.phtml após fechamento da tag table (</table>) próximo à linha 104 adicionar o seguinte código:
<?php echo $this->getLayout()->createBlock('clearsale/clearsale')->toHtml(); ?>

Esse trecho inclui, nas informações do pedido, o iframe da ClearSale...

Já no arquivo app/code/local/Prestige/Clearsale/Block/Clearsale.php
na função _afterToHtml($html) é onde está concentrado a função principal do Módulo, buscando as informações do pedido...

Tenham atenção para adequá-los conforme suas necessidades...

Espero que ajudem...

OBS: Esse módulo foi desenvolvido meio às pressas sugestões são sempre bem vindas.

Abraços, Renan.

