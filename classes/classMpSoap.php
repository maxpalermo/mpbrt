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

class classMpSoap {
    public function request($method, $params)
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
        $request->RIFERIMENTO_MITTENTE_NUMERICO = 15536;
        $functions = $client->__getFunctions();
        $result = $client->getidspedizionebyrmn(array('arg0' => $request));
        
        $url2 = 'http://wsr.brt.it:10041/web/BRT_TrackingByBRTshipmentIDService/BRT_TrackingByBRTshipmentID?wsdl';
        $client2 = new SoapClient($url2);
        $request2 = new stdClass();
        $request2->LINGUA_ISO639_ALPHA2 = '';
        $request2->SPEDIZIONE_ANNO = '';
        $request2->SPEDIZIONE_BRT_ID = 169010037097;
        $functions2 = $client2->__getFunctions();
        $result2 = $client2->brt_trackingbybrtshipmentid(array('arg0' => $request2));
        
        
        
        
        return array($functions,$result,$functions2,$result2);
    }
        
}
