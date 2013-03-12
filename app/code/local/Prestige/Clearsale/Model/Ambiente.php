<?php
class Prestige_Clearsale_Model_Ambiente
{
    public function toOptionArray()
    {
        return array(
            array('value' => '0', 'label' => 'Homologação'),
            array('value' => '1', 'label' => 'Produção')
        );
    }
}

