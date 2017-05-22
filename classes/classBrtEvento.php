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
class classBrtEvento {
    private $data;
    private $descrizione;
    private $filiale;
    private $id;
    private $ora;
    
    public function __construct($evento) {
        $this->data = $evento->DATA;
        $this->descrizione = $evento->DESCRIZIONE;
        $this->filiale = $evento->FILIALE;
        $this->id = $evento->ID;
        $this->ora = $evento->ORA;
    }
    
    function getData() {
        return $this->data;
    }

    function getDescrizione() {
        return $this->descrizione;
    }

    function getFiliale() {
        return $this->filiale;
    }

    function getId() {
        return $this->id;
    }

    function getOra() {
        return $this->ora;
    }


}
