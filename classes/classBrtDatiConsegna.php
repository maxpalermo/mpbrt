<?php

/**
 * 2017 mpSOFT
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    mpSOFT <info@mpsoft.it>
 *  @copyright 2017 mpSOFT Massimiliano Palermo
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of mpSOFT
 */
class classBrtDatiConsegna {
    private $data_consegna_merce;
    private $data_consegna_richiesta;
    private $data_teorica_consegna;
    private $descrizione_consegna_richiesta;
    private $firmatario_consegna;
    private $ora_consegna_merce;
    private $ora_consegna_richiesta;
    private $ora_teorica_consegna_a;
    private $ora_teorica_consegna_da;
    private $tipo_consegna_richiesta;
    
    public function __construct($consegna) {
        $this->data_consegna_merce = $consegna->DATA_CONSEGNA_MERCE;
        $this->data_consegna_richiesta = $consegna->DATA_CONS_RICHIESTA;
        $this->data_teorica_consegna = $consegna->DATA_TEORICA_CONSEGNA;
        $this->descrizione_consegna_richiesta = $consegna->DESCRIZIONE_CONS_RICHIESTA;
        $this->firmatario_consegna = $consegna->FIRMATARIO_CONSEGNA;
        $this->ora_consegna_merce = $consegna->ORA_CONSEGNA_MERCE;
        $this->ora_consegna_richiesta = $consegna->ORA_CONS_RICHIESTA;
        $this->ora_teorica_consegna_a = $consegna->ORA_TEORICA_CONSEGNA_A;
        $this->ora_teorica_consegna_da = $consegna->ORA_TEORICA_CONSEGNA_DA;
        $this->tipo_consegna_richiesta = $consegna->TIPO_CONS_RICHIESTA;
    }
    
    function getDataConsegnaMerce() {
        return $this->data_consegna_merce;
    }

    function getDataConsegnaRichiesta() {
        return $this->data_consegna_richiesta;
    }

    function getDataTeoricaConsegna() {
        return $this->data_teorica_consegna;
    }

    function getDescrizioneConsegnaRichiesta() {
        return $this->descrizione_consegna_richiesta;
    }

    function getFirmatarioConsegna() {
        return $this->firmatario_consegna;
    }

    function getOraConsegnaMerce() {
        return $this->ora_consegna_merce;
    }

    function getOraConsegnaRichiesta() {
        return $this->ora_consegna_richiesta;
    }

    function getOraTeoricaConsegnaA() {
        return $this->ora_teorica_consegna_a;
    }

    function getOraTeoricaConsegnaDa() {
        return $this->ora_teorica_consegna_da;
    }

    function getTipoConsegnaRichiesta() {
        return $this->tipo_consegna_richiesta;
    }


}
