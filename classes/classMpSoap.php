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

if(class_exists('classMpSoap')) {
    return false;
}

class classMpSoap {
    
    private $result;
    private $bolla;
    private $customer_id;
    private $module;
    private $debug;
    private $tracking_id;
    
    public function __construct($customer_id, $module) {
        $this->module = $module;
        $this->bolla = false;
        $this->customer_id = $customer_id;
        $this->module = $module;
        $this->debug = true;
    }
    
    /**
     * Returns tracking information
     * @return mixed stdClass Bolla or FALSE if empty
     */
    public function getBolla()
    {
        if(!empty($this->bolla)) {
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
        $message = "";
        
        if(!empty($this->result) && !empty($this->result->ESITO))
        {
            switch ((int)$this->result->ESITO) {
                case 0:
                    $message = $this->module->l('OK','classMpSoap');
                    break;
                case -1:
                    $message = $this->module->l('UNKNOWN ERROR','classMpSoap');
                    break;
                case -3:
                    $message = $this->module->l('ERROR CONNECTING DATABASE', 'classMpSoap');
                    break;
                case -20:
                    $message = $this->module->l('NO SENDER RECEIVED', 'classMpSoap');
                    break;
                case -21:
                    $message = $this->module->l('CUSTOMER NOT VALID', 'classMpSoap');
                    break;
                case -11:
                    $message = $this->module->l('SHIPPING NOT FOUND', 'classMpSoap');
                    break;
                case -22:
                    $message = $this->module->l('MORE THAN ONE SHIPPING FOUND', 'classMpSoap');
                    break;
                default:
                    if ((int)$this->result->ESITO >0) {
                        $message = $this->module->l('WARNINGS DURING FUNCTION CALL', 'classMpSoap');
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
        $url = 'http://wsr.brt.it:10041/web/GetIdSpedizioneByRMNService/GetIdSpedizioneByRMN?wsdl';
        $client = new SoapClient($url);
        
        $request = new stdClass();
        $request->CLIENTE_ID = $this->customer_id;
        $request->RIFERIMENTO_MITTENTE_NUMERICO = $id_order;
        
        $result = $client->getidspedizionebyrmn(array('arg0' => $request));
        $this->result = $result->return;
        $this->tracking_id = (int)$this->result->SPEDIZIONE_ID;
        
        if($this->debug) {
            classMpLogger::add("CUSTOMER ID: " . $this->customer_id);
            classMpLogger::add("ID ORDER: " . $id_order);
            classMpLogger::add(print_r($this->result, 1));
        }
    }
    
    /**
     * Call SOAP function to retrieve informations about a shipping
     * @param Integer $tracking_id Bartolini's tracking id
     */
    public function getTrackingInfoByTrackingId($tracking_id)
    {
        $url = 'http://wsr.brt.it:10041/web/BRT_TrackingByBRTshipmentIDService/BRT_TrackingByBRTshipmentID?wsdl';
        $client = new SoapClient($url);
        $request = new stdClass();
        $request->LINGUA_ISO639_ALPHA2 = '';
        $request->SPEDIZIONE_ANNO = '2017';
        $request->SPEDIZIONE_BRT_ID = '169010023175';
        
        
        $result = $client->brt_trackingbybrtshipmentid(array('arg0' => $request));
        $this->result = $result->return;
        
        if ($this->debug) {
            classMpLogger::add('GET TRACKING INFO');
            classMpLogger::add('TRACKING ID: ' . $tracking_id);
            classMpLogger::add("ESITO: " . $this->getResultMessage());
            classMpLogger::add(print_r($result, 1));
        }
        
        if($this->result->ESITO==0) {
            $this->bolla = new classBrtBolla($this->result);
        } else {
            $this->bolla = null;
        }
    }
    
    
    public function getOrderHistory($id_order) {
        $this->getTrackingByRMN($id_order);
        if($this->getResultCode()==0) {
            $this->getTrackingInfoByTrackingId($this->getTrackingId());
        } else {
            return $this->result->ESITO;
        }
    }
    
    public function request()
    {
        $url = 'http://wsr.brt.it:10041/web/GetIdSpedizioneByRMNService/GetIdSpedizioneByRMN?wsdl';
        $client = new SoapClient($url);
        
        
        /**
         * <xs:complexType name="getidspedizionebyrmnInput">
         * <xs:sequence>
         * <xs:element name="CLIENTE_ID" type="xs:decimal"/>
         * <xs:element name="RIFERIMENTO_MITTENTE_NUMERICO" type="xs:decimal"/>
         * </xs:sequence>
         * </xs:complexType>
         */
        
        $request = new stdClass();
        $request->CLIENTE_ID = 1690519;
        $request->RIFERIMENTO_MITTENTE_NUMERICO = 10001093;
        $functions = $client->__getFunctions();
        $result = $client->getidspedizionebyrmn(array('arg0' => $request));
        
        $url2 = 'http://wsr.brt.it:10041/web/BRT_TrackingByBRTshipmentIDService/BRT_TrackingByBRTshipmentID?wsdl';
        $client2 = new SoapClient($url2);
        $request2 = new stdClass();
        $request2->LINGUA_ISO639_ALPHA2 = '';
        $request2->SPEDIZIONE_ANNO = '';
        $request2->SPEDIZIONE_BRT_ID = '';
        
        $functions2 = $client2->__getFunctions();
        $result2 = $client2->brt_trackingbybrtshipmentid(array('arg0' => $request2));
        
        classMpLogger::add(print_r($result, 1));
        classMpLogger::add(print_r($functions2, 1));
        classMpLogger::add(print_r($result2, 1));
        
        
        return array($functions,$result,$functions2,$result2);
    }
}
