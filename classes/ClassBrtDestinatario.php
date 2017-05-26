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

class ClassBrtDestinatario
{
    private $cap;
    private $indirizzo;
    private $localita;
    private $ragione_sociale;
    private $referente_consegna;
    private $sigla_nazione;
    private $sigla_provincia;
    private $telefono_referente;
    
    public function __construct($dest)
    {
        $this->cap = $dest->CAP;
        $this->indirizzo = $dest->INDIRIZZO;
        $this->localita = $dest->LOCALITA;
        $this->ragione_sociale = $dest->RAGIONE_SOCIALE;
        $this->referente_consegna = $dest->REFERENTE_CONSEGNA;
        $this->sigla_nazione = $dest->SIGLA_NAZIONE;
        $this->sigla_provincia = $dest->SIGLA_PROVINCIA;
        $this->telefono_referente = $dest->TELEFONO_REFERENTE;
    }
    
    public function getCap()
    {
        return $this->cap;
    }

    public function getIndirizzo()
    {
        return $this->indirizzo;
    }

    public function getLocalita()
    {
        return $this->localita;
    }

    public function getRagioneSociale()
    {
        return $this->ragione_sociale;
    }

    public function getReferenteConsegna()
    {
        return $this->referente_consegna;
    }

    public function getSiglaNazione()
    {
        return $this->sigla_nazione;
    }

    public function getSiglaProvincia()
    {
        return $this->sigla_provincia;
    }

    public function getTelefonoReferente()
    {
        return $this->telefono_referente;
    }
}
