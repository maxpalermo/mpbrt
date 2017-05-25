<?php
/**
* 2007-2016 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class AdminMpBrtController extends ModuleAdminController {
    public $smarty;
    public $lang;
    
    private $customer_id;
    private $id_carrier_display;
    private $id_tracking_order;
    private $id_delivered_order;
    private $id_customer_reference;
    private $skip_states;
    private $switch_display_error;
    private $debug;
        
    public function __construct()
    {
            $this->bootstrap = true;
            parent::__construct();
            
            $this->context = Context::getContext();
            $this->smarty = Context::getContext()->smarty;
            $this->lang = Context::getContext()->language->id;
            $this->customer_id = (int)ConfigurationCore::get('MP_BRT_CUSTOMER_ID');
            $this->id_carrier_display = (int)ConfigurationCore::get('MP_BRT_ID_CARRIER_DISPLAY');
            $this->id_tracking_order = (int)ConfigurationCore::get('MP_BRT_ID_TRACKING_ORDER');
            $this->id_delivered_order = (int)ConfigurationCore::get('MP_BRT_ID_DELIVERED_ORDER');
            $this->id_customer_reference = ConfigurationCore::get('MP_BRT_CUSTOMER_REFERENCE');
            $this->skip_states = explode(",", ConfigurationCore::get('MP_BRT_SKIP_STATES'));
            $this->switch_display_error = (int)ConfigurationCore::get('MP_BRT_SWITCH_DISPLAY_ERROR');
            $this->debug = true;
    }

    public function initToolbar()
    {
            parent::initToolbar();
            unset($this->toolbar_btn['new']);
    }

    public function postProcess()
    {
        if(Tools::isSubmit('submit_process_orders')) {
            $arrayOrders = Tools::getValue('input_hidden_orders', '');
            $trackingOrders = Tools::jsonDecode($arrayOrders);
            
            if($this->debug) {
                print "<pre>";
                print print_r($trackingOrders, 1);
                print "</pre>";
                return;
            }
            
            foreach($arrayOrders as $objOrder)
            {
                $order = new OrderCore($objOrder->id);
                if($objOrder->delivered) {
                    $order->setCurrentState($this->id_delivered_order);
                } else {
                    $order->setCurrentState($this->id_tracking_order);
                }
                
                //Check if tracking number exists in archive
                $db = Db::getInstance();
                $sql = new DbQueryCore();
                $sql->select('id_order_carrier')
                        ->from('order_carrier')
                        ->where('tracking_number = \'' . pSQL($objOrder->tracking_id) . '\'');
                $value = (int)$db->getValue($sql);
                
                if($value==0) {
                    //No tracking number found, update orderCarrier
                    $sql = new DbQueryCore();
                    $sql->select('id_order_carrier')
                            ->from('order_carrier')
                            ->where('id_order = ' . (int)$order->id);
                    $value = (int)$db->getValue($sql);
                    
                    if($value!=0) {
                        //Update orderCarrier tracking number
                        $orderCarrier = new OrderCarrierCore($value);
                        $orderCarrier->tracking_number = $objOrder->tracking_id;    
                        $orderCarrier->save();
                    }
                }
            }
        }
    }

    public function setMedia()
    {
            parent::setMedia();
            $this->addJS(_PS_MODULE_DIR_.'printlabelspro/views/js/label.js');
    }

    public function initContent() 
    {    

        parent::initContent();
        
        $soap = new classMpSoap($this->customer_id);
        
        $db = Db::getInstance();
        $sql = new DbQueryCore();
        $sql    ->select('id_order')
                ->select('reference')
                ->from('orders')
                ->orderby('date_add DESC');
        if($this->id_carrier_display!=0) {
            $sql->where('id_carrier = ' . pSQL($this->id_carrier_display));
        }
        if($this->id_delivered_order!=0) {
            $this->skip_states[] = $this->id_delivered_order;
            $skip_sql = implode(',', $this->skip_states);
            $sql->where('current_state NOT in (' . pSQL($skip_sql) . ')');
        }
        
        $orders = $db->executeS($sql);
        
        $rows = array();
        
        //$i=0;
        foreach($orders as $order)
        {
            if($this->debug) {
                $objOrder = new OrderCore($order['id_order']);
                $orderState = new OrderStateCore($objOrder->current_state);
                
                $tr = $this->buildTestRow(
                        (int)$objOrder->reference,
                        $orderState->name[Context::getContext()->language->id]);
                
            } else {
                //$i++; if($i==20) {break;}
                $evt = $soap->seekForDeliveredState((int)$order['reference'], (int)$order['id_order']);
                if($evt!==false) {
                    $row = $this->buildStatusRow($evt);
                    $rows[] = $row;
                } elseif ($evt===false &&  $this->switch_display_error==0) {
                    //build an error row
                    $row = new stdClass();
                    $row->DATA=date('d.m.Y');
                    $row->DESCRIZIONE=$this->getResultMessage($soap->getResultCode());
                    $row->FILIALE='';
                    $row->ID=$soap->getResultCode();
                    $row->ORA=date('h:i');
                    $row->REFERENCE=$order['reference'];
                    $row->TRACKING_ID='ERROR';
                    $row->STATE='';
                    $tr = $this->buildStatusRow($row);
                }
            }
            $rows[] = $tr;
        }
                
        $this->context->smarty->assign('rows', implode(PHP_EOL, $rows));
        $this->context->smarty->assign('cont', $this->content);
        $this->context->smarty->assign('orders', $orders);
        $this->context->smarty->assign('sql', $sql->__toString());
        $content = $this->smarty->fetch(_MPBRT_TEMPLATES_ . 'admin/displayPage.tpl');
        
        $this->context->smarty->assign('content', $this->content . $content);
    } 
    
    private function buildStatusRow($row)
    {
        if($row->ID<0) {
            $color = '#a0a050';
            $icon = "<i class='icon icon-warning'";
        } elseif ($row->DESCRIZIONE=='CONSEGNATA') {
            $color = '#50a050';
            $icon = "<i class='icon icon-ok-sign'";
        } elseif ($row->DESCRIZIONE=='SHIPPING NOT FOUND') {
            $color = '#a05050';
            $icon = "<i class='icon icon-ban'";
        } else {
            $color = "#666";
            $icon = "<i class='icon icon-truck'";
        }
        
        $icon = $icon . " style='color: $color'></i>";
        
        $tr = "<tr>"  
                . "<td>$icon</td>"
                . "<td style='color: $color';>" . $row->ID . "</td>"
                . "<td style='color: $color';>" . $row->DATA . "</td>"
                . "<td style='color: $color';>" . $row->ORA . "</td>"
                . "<td style='color: $color';>" . $row->DESCRIZIONE . "</td>"
                . "<td style='color: $color';>" . $row->FILIALE . "</td>"
                . "<td style='color: $color';>" . $row->REFERENCE . "</td>"
                . "<td style='color: $color';>" . $row->TRACKING_ID . "</td>"
                . "<td style='color: $color';>" . $row->STATE . "</td>"
                ."</tr>";
            
        return $tr;
    }
    
    /**
     * Returns Operation message
     * @return mixed String message of result operation or FALSE if empty
     */
    private function getResultMessage($esito)
    {
        $message = "";
        
        if(!empty($esito) && !empty($esito))
        {
            switch ((int)$esito) {
                case 0:
                    $message = $this->l('OK','classMpSoap');
                    break;
                case -1:
                    $message = $this->l('UNKNOWN ERROR','classMpSoap');
                    break;
                case -3:
                    $message = $this->l('ERROR CONNECTING DATABASE', 'classMpSoap');
                    break;
                case -20:
                    $message = $this->l('NO SENDER RECEIVED', 'classMpSoap');
                    break;
                case -21:
                    $message = $this->l('CUSTOMER NOT VALID', 'classMpSoap');
                    break;
                case -11:
                    $message = $this->l('SHIPPING NOT FOUND', 'classMpSoap');
                    break;
                case -22:
                    $message = $this->l('MORE THAN ONE SHIPPING FOUND', 'classMpSoap');
                    break;
                default:
                    if ((int)$this->result->ESITO >0) {
                        $message = $this->l('WARNINGS DURING FUNCTION CALL', 'classMpSoap');
                    } else {
                        $message = '';
                    }
                    break;
            }
        }
        return $message;
    }
    
    private function buildTestRow($reference, $current_state)
    {
        $id = rand(100,999);
        $date = date('d.m.Y');
        $time = date('h:i');
        $event = array(
            'SHIPPING NOT FOUND',
            'CONSEGNATA',
            'IN CONSEGNA',
            'PARTITA',
            'LASCIATO AVVISO',
            'ASSENTE',
            'RIFIUTATA'
        );
        $branch = array(
            'MILANO',
            'PALERMO',
            'ROMA',
            'COSENZA',
            'SIRACUSA',
            'LECCE',
            'ASTI',
            'TORINO',
            'PESCARA',
            'ANCONA',
            'CAGLIARI'
        );
        $tracking_id = '1690100' . rand(10000,99999);
        
        $current_event = $event[rand(0, count($event)-1)];
        $current_branch = $branch[rand(0, count($branch)-1)];
        
        if($current_event=='SHIPPING NOT FOUND') {
            $tracking_id = 'ERROR';
        }
        
        $row = new stdClass();
        $row->DATA=$date;
        $row->DESCRIZIONE=$current_event;
        $row->FILIALE=$current_branch;
        $row->ID=$id;
        $row->ORA=$time;
        $row->REFERENCE=$reference;
        $row->TRACKING_ID=$tracking_id;
        $row->STATE=$current_state;
        
        return $this->buildStatusRow($row);
    }
}
