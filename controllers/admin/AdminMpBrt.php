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
    
    public function __construct()
    {
            $this->bootstrap = true;
            $this->context = Context::getContext();
            $this->smarty = Context::getContext()->smarty;
            $this->lang = Context::getContext()->language->id;

            parent::__construct();
    }

    public function initToolbar()
    {
            parent::initToolbar();
            unset($this->toolbar_btn['new']);
    }

    public function postProcess()
    {

    }

    public function setMedia()
    {
            parent::setMedia();
            $this->addJS(_PS_MODULE_DIR_.'printlabelspro/views/js/label.js');
    }

    public function initContent() 
    {    

        parent::initContent();
        
        $customer_id = (int)ConfigurationCore::get('MP_BRT_CUSTOMER_ID');
        $id_carrier_display = (int)ConfigurationCore::get('MP_BRT_ID_CARRIER_DISPLAY');
        $id_tracking_order = (int)ConfigurationCore::get('MP_BRT_ID_TRACKING_ORDER');
        $id_delivered_order = (int)ConfigurationCore::get('MP_BRT_ID_DELIVERED_ORDER');
        $id_customer_reference = ConfigurationCore::get('MP_BRT_CUSTOMER_REFERENCE');
        
        $soap = new classMpSoap($customer_id, Context::getContext()->controller);
        
        $db = Db::getInstance();
        $sql = new DbQueryCore();
        $sql    ->select('id_order')
                ->select('reference')
                ->from('orders')
                ->orderby('date_add DESC');
        if($id_carrier_display!=0) {
            $sql->where('id_carrier = ' . pSQL($id_carrier_display));
        }
        if($id_delivered_order!=0) {
            $sql->where('current_state != ' . pSQL($id_delivered_order));
        }
        
        $orders = $db->executeS($sql);
        $rows = array();
        
        $i=0;
        foreach($orders as $order)
        {
            $i++; if($i==20) {break;}
            $evt = $soap->seekForDeliveredState((int)$order['reference'], (int)$order['id_order']);
            if($evt!==false) {
                $row = $this->buildStatusRow($evt);
                $rows[] = $row;
            } else {
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
                $rows[] = $tr;
            }
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
            $icon = "<td><i class='icon icon-warning'></i></td>";
        } else {
            $icon = "<td><i class='icon icon-truck'></i></td>";
        }
        $tr = "<tr>"  
                . $icon
                . "<td>" . $row->ID . "</td>"
                . "<td>" . $row->DATA . "</td>"
                . "<td>" . $row->ORA . "</td>"
                . "<td>" . $row->DESCRIZIONE . "</td>"
                . "<td>" . $row->FILIALE . "</td>"
                . "<td>" . $row->REFERENCE . "</td>"
                . "<td>" . $row->TRACKING_ID . "</td>"
                . "<td>" . $row->STATE . "</td>"
                ."<tr>";
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
}
