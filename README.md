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

Caso esse módulo foi útil para você e queira fazer uma singela doação clique no botão abaixo:

<!-- INICIO FORMULARIO BOTAO PAGSEGURO -->
<form target="pagseguro" action="https://pagseguro.uol.com.br/checkout/v2/donation.html" method="post">
<!-- NÃO EDITE OS COMANDOS DAS LINHAS ABAIXO -->
<input type="hidden" name="receiverEmail" value="blecha1990@gmail.com" />
<input type="hidden" name="currency" value="BRL" />
<input type="image" src="https://p.simg.uol.com.br/out/pagseguro/i/botoes/doacoes/120x53-doar.gif" name="submit" alt="Pague com PagSeguro - é rápido, grátis e seguro!" />
</form>
<!-- FINAL FORMULARIO BOTAO PAGSEGURO -->

Abraços, Renan.

