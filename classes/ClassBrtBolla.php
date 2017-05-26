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

class ClassBrtBolla
{
    private $assicurazione;
    private $contrassegno;
    private $dati_consegna;
    private $dati_spedizione;
    private $destinatario;
    private $merce;
    private $mittente;
    private $contatore_eventi;
    private $contatore_note;
    private $esito;
    private $eventi;
    private $risposta_timestamp;
    private $versione;
    
    public function __construct($result)
    {
        $this->assicurazione = new ClassBrtAssicurazione($result->BOLLA->ASSICURAZIONE);
        $this->contrassegno = new ClassBrtContrassegno($result->BOLLA->CONTRASSEGNO);
        $this->dati_consegna = new ClassBrtDatiConsegna($result->BOLLA->DATI_CONSEGNA);
        $this->dati_spedizione = new ClassBrtDatiSpedizione($result->BOLLA->DATI_SPEDIZIONE);
        $this->destinatario = new ClassBrtDestinatario($result->BOLLA->DESTINATARIO);
        $this->merce = new ClassBrtMerce($result->BOLLA->MERCE);
        $this->mittente = new ClassBrtMittente($result->BOLLA->MITTENTE);
        $this->contatore_eventi = $result->CONTATORE_EVENTI;
        $this->contatore_note = $result->CONTATORE_NOTE;
        $this->esito = $result->ESITO;
        $this->eventi = $this->setEventi($result->LISTA_EVENTI);
        $this->risposta_timestamp = $result->RISPOSTA_TIMESTAMP;
        $this->versione = $result->VERSIONE;
    }
    
    private function setEventi($eventi)
    {
        $array = array();
        foreach ($eventi as $evento) {
            $evt = new ClassBrtEvento($evento);
            $array[] = $evt;
        }
        
        return $array;
    }
    
    public function getEventi($esito = 0)
    {
        if ($esito==0) {
            return $this->eventi;
        } else {
            $evt = new stdClass();
            
            $evt->data = date('d.m.Y');
            $evt->descrizione = $this->getEsitoMessage($esito);
            $evt->filiale = '';
            $evt->id = '';
            $evt->ora = date('h:i');
            
            $class = new stdClass();
            $class->EVENTO = $evt;
            return array($class);
        }
    }
}
