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
class classBrtMittente {
    private $cap;
    private $codice;
    private $indirizzo;
    private $localita;
    private $ragione_sociale;
    private $sigla_area;
    
    public function __construct($mitt) {
        $this->cap = $mitt->CAP;
        $this->codice = $mitt->CODICE;
        $this->indirizzo = $mitt->INDIRIZZO;
        $this->localita = $mitt->LOCALITA;
        $this->ragione_sociale = $mitt->RAGINE_SOCIALE;
        $this->sigla_area = $mitt->SIGLA_AREA;
    }
    
    function getCap() {
        return $this->cap;
    }

    function getCodice() {
        return $this->codice;
    }

    function getIndirizzo() {
        return $this->indirizzo;
    }

    function getLocalita() {
        return $this->localita;
    }

    function getRagioneSociale() {
        return $this->ragione_sociale;
    }

    function getSiglaArea() {
        return $this->sigla_area;
    }


}
