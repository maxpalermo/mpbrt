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

if (class_exists('classMpSoap')) {
    return false;
}

class ClassMpSoap extends ModuleCore
{
    private $result;
    private $bolla;
    private $customer_id;
    private $customer_reference;
    private $debug;
    private $tracking_id;
    private $id_customer_reference;
    private $switch_display_error;
    
    public function __construct($customer_id)
    {
        parent::__construct();
        
        $this->bolla = false;
        $this->customer_id = $customer_id;
        $this->debug = true;
        $this->id_customer_reference = ConfigurationCore::get('MP_BRT_CUSTOMER_REFERENCE');
        $this->switch_display_error = ConfigurationCore::get('MP_BRT_SWITCH_DISPLAY_ERROR');
    }
    
    /**
     * Returns tracking information
     * @return mixed stdClass Bolla or FALSE if empty
     */
    public function getBolla()
    {
        if (!empty($this->bolla)) {
            return $this->bolla;
        } else {
            return false;
        }
    }
    
    /**
     * Returns whole $result variable
     * @return mixed Raw data of $result variable or FALSE if is empty
     */
    public function getResult()
    {
        if (!empty($this->result)) {
            return $this->result;
        } else {
            return false;
        }
    }
    
    /**
     * Returns Operation message
     * @return mixed String message of result operation or FALSE if empty
     */
    public function getResultMessage()
    {
        $translate = new MpBrt();
        $message = "";
        
        if (!empty($this->result) && !empty($this->result->ESITO)) {
            switch ((int)$this->result->ESITO) {
                case 0:
                    $message = $translate->l('OK', 'classMpSoap');
                    break;
                case -1:
                    $message = $translate->l('UNKNOWN ERROR', 'classMpSoap');
                    break;
                case -3:
                    $message = $translate->l('ERROR CONNECTING DATABASE', 'classMpSoap');
                    break;
                case -20:
                    $message = $translate->l('NO SENDER RECEIVED', 'classMpSoap');
                    break;
                case -21:
                    $message = $translate->l('CUSTOMER NOT VALID', 'classMpSoap');
                    break;
                case -11:
                    $message = $translate->l('SHIPPING NOT FOUND', 'classMpSoap');
                    break;
                case -22:
                    $message = $translate->l('MORE THAN ONE SHIPPING FOUND', 'classMpSoap');
                    break;
                default:
                    if ((int)$this->result->ESITO >0) {
                        $message = $translate->l('WARNINGS DURING FUNCTION CALL', 'classMpSoap');
                    } else {
                        $message = '';
                    }
                    break;
            }
        }
        return $message;
    }
    
    /**
     * Returns operation code message
     * @return mixed Integer value of operation code message or FALSE if empty
     */
    public function getResultCode()
    {
        if (!empty($this->result) && !empty($this->result->ESITO)) {
            return $this->result->ESITO;
        } else {
            return false;
        }
    }
    
    /**
     * Returns Tracking id or FALSE if empty
     * @return mixed Tracking id or FALSE if empty
     */
    public function getTrackingId()
    {
        return $this->tracking_id;
    }
    
    /**
     * Call SOAP function to get Tracking id from shipping reference
     * @param Integer $customer_id Bartolini customer's id
     * @param Integer $id_order Bartolini's shipping reference
     */
    public function getTrackingByRMN($id_order)
    {
        $this->customer_reference = $id_order;
        $url = 'http://wsr.brt.it:10041/web/GetIdSpedizioneByRMNService/GetIdSpedizioneByRMN?wsdl';
        try {
            $client = new SoapClient($url);
        } catch (Exception $exc) {
            ClassMpLogger::add($exc->getMessage());
            return false;
        }
        
        $request = new stdClass();
        $request->CLIENTE_ID = $this->customer_id;
        $request->RIFERIMENTO_MITTENTE_NUMERICO = $id_order;
        
        try {
            $result = $client->getidspedizionebyrmn(array('arg0' => $request));
        } catch (Exception $exc) {
            ClassMpLogger::add($exc->getMessage());
            return false;
        }

        
        $this->result = $result->return;
        $this->tracking_id = (int)$this->result->SPEDIZIONE_ID;
        
        if ($this->debug) {
            ClassMpLogger::add("CUSTOMER ID: " . $this->customer_id);
            ClassMpLogger::add("ID ORDER: " . $id_order);
            ClassMpLogger::add(print_r($this->result, 1));
        }
    }
    
    /**
     * Call SOAP function to retrieve informations about a shipping
     * @param Integer $tracking_id Bartolini's tracking id
     */
    public function getTrackingInfoByTrackingId($tracking_id)
    {
        $url = 'http://wsr.brt.it:10041/web/BRT_TrackingByBRTshipmentIDService/BRT_TrackingByBRTshipmentID?wsdl';
        try {
            $client = new SoapClient($url);
        } catch (Exception $ex) {
            ClassMpLogger::add($ex->getMessage());
            return false;
        }
        $request = new stdClass();
        $request->LINGUA_ISO639_ALPHA2 = '';
        $request->SPEDIZIONE_ANNO = '';
        $request->SPEDIZIONE_BRT_ID = $tracking_id;
        
        try {
            $result = $client->brt_trackingbybrtshipmentid(array('arg0' => $request));
        } catch (Exception $exc) {
            ClassMpLogger::add('ERROR DURING FUNCTION CALL');
            ClassMpLogger::add($exc->getMessage());
            return false;
        }

        $this->result = $result->return;
        
        if ($this->debug) {
            ClassMpLogger::add('GET TRACKING INFO');
            ClassMpLogger::add('TRACKING ID: ' . $tracking_id);
            ClassMpLogger::add("ESITO: " . $this->getResultMessage());
            //classMpLogger::add(print_r($result, 1));
        }
        
        if ($this->result->ESITO==0) {
            $this->bolla = new ClassBrtBolla($this->result);
        } else {
            $this->bolla = null;
        }
    }
    
    public function getOrderHistory($id_order)
    {
        $this->getTrackingByRMN($id_order);
        if ($this->getResultCode()==0) {
            $this->getTrackingInfoByTrackingId($this->getTrackingId());
        } else {
            return $this->result->ESITO;
        }
    }
    
    public function getCustomerReference()
    {
        return $this->customer_reference;
    }
    
    /**
     * Seek for delivered event and returns event
     * EVENT:
     *      ->DATA
     *      ->DESCRIZIONE
     *      ->FILIALE
     *      ->ID
     *      ->ORA
     *      ->REFERENCE
     *      ->TRACKING_ID
     * @author Massimiliano Palermo <maxx.palermo@gmail.com>
     * @return mixed event or false
     */
    public function seekForDeliveredState($reference, $id_order)
    {
        ClassMpLogger::add('*****************************');
        ClassMpLogger::add('* START FUNCTION SEEK STATE *');
        ClassMpLogger::add('*****************************');
        
        
        $order = new OrderCore($id_order);
        $state = new OrderStateCore($order->current_state);
        if ($this->id_customer_reference=='reference') {
            $reference = (int)$order->reference;
            ClassMpLogger::add('Search by reference: ' . $reference);
        } else {
            $reference = (int)$order->id;
            ClassMpLogger::add('Search by order id: ' . $reference);
        }
        
        //Get Tracking ID
        if ($this->getTrackingByRMN($reference)===false) {
            ClassMpLogger::add('getTrackingByRMN(' . $reference . ') failed');
            return false;
        }
        
        //Get Tracking Info
        if ($this->getTrackingInfoByTrackingId($this->tracking_id)===false) {
            ClassMpLogger::add('getTrackingInfoByTrackingId(' . $this->tracking_id . ') failed');
            return false;
        }
        
        if (!isset($this->result->ESITO) || $this->result->ESITO<0) {
            ClassMpLogger::add('ESITO returns ' . $this->result->ESITO);
            return false;
        }
        
        //Get first event to show
        //evento ->DESCRIZIONE
        $events = $this->result->LISTA_EVENTI;
        $event = $events[0];
        $evt = $event->EVENTO;
        $evt->REFERENCE = $reference;
        $evt->TRACKING_ID = $this->tracking_id;
        $evt->STATE = $state->name[ContextCore::getContext()->language->id];
        return $evt;
    }
}
