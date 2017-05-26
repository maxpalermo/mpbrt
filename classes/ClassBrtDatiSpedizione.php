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

class ClassBrtDatiSpedizione
{
    private $cod_filiale_arrivo;
    private $descrizione_1;
    private $descrizione_2;
    private $filiale_arrivo;
    private $filiale_arrivo_url;
    private $porto;
    private $servizio;
    private $data;
    private $id;
    private $stato_1;
    private $stato_2;
    private $tipo_porto;
    private $tipo_servizio;
    
    public function __construct($spedizione)
    {
        $this->cod_filiale_arrivo = $spedizione->COD_FILIALE_ARRIVO;
        $this->descrizione_1 = $spedizione->DESCRIZIONE_STATO_SPED_PARTE1;
        $this->descrizione_2 = $spedizione->DESCRIZIONE_STATO_SPED_PARTE2;
        $this->filiale_arrivo = $spedizione->FILIALE_ARRIVO;
        $this->filiale_arrivo_url = $spedizione->FILIALE_ARRIVO_URL;
        $this->porto = $spedizione->PORTO;
        $this->servizio = $spedizione->SERVIZIO;
        $this->data = $spedizione->SPEDIZIONE_DATA;
        $this->id = $spedizione->SPEDIZIONE_ID;
        $this->stato_1 = $spedizione->STATO_SPED_PARTE1;
        $this->stato_2 = $spedizione->STATO_SPED_PARTE2;
        $this->tipo_porto = $spedizione->TIPO_PORTO;
        $this->tipo_servizio = $spedizione->TIPO_SERVIZIO;
    }
    
    public function getCodFilialeArrivo()
    {
        return $this->cod_filiale_arrivo;
    }

    public function getDescrizione1()
    {
        return $this->descrizione_1;
    }

    public function getDescrizione2()
    {
        return $this->descrizione_2;
    }

    public function getFilialeArrivo()
    {
        return $this->filiale_arrivo;
    }

    public function getFilialeArrivoURL()
    {
        return $this->filiale_arrivo_url;
    }

    public function getPorto()
    {
        return $this->porto;
    }

    public function getServizio()
    {
        return $this->servizio;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStato1()
    {
        return $this->stato_1;
    }

    public function getStato2()
    {
        return $this->stato_2;
    }

    public function getTipoPorto()
    {
        return $this->tipo_porto;
    }

    public function getTipoServizio()
    {
        return $this->tipo_servizio;
    }
}
