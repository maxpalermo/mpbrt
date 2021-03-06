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

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(dirname(__FILE__) . '/classes/ClassMpBrtAutoload.php');
    
class MpBrt extends Module
{
    public function __construct()
    {
        $this->name = 'mpbrt';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'mpsoft';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('MP BRT');
        $this->description = $this->l('This module manages Bartolini shipments');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
      
        //SET DEFINITIONS
        if (!defined('_MPBRT_URL_')) {
            define('_MPBRT_URL_', $this->_path);
        }
        
        if (!defined('_MPBRT_')) {
            define('_MPBRT_', $this->local_path);
        }

        if (!defined('_MPBRT_CLASSES_')) {
            define('_MPBRT_CLASSES_', _MPBRT_ . "classes/");
        }

        if (!defined('_MPBRT_CONTROLLERS_')) {
            define('_MPBRT_CONTROLLERS_', _MPBRT_ . "controllers/");
        }

        if (!defined('_MPBRT_CSS_URL_')) {
            define('_MPBRT_CSS_URL_', _MPBRT_URL_ . "views/css/");
        }

        if (!defined('_MPBRT_JS_URL_')) {
            define('_MPBRT_JS_URL_', _MPBRT_URL_ . "views/js/");
        }

        if (!defined('_MPBRT_IMG_URL_')) {
            define('_MPBRT_IMG_URL_', _MPBRT_URL_ . "views/img/");
        }

        if (!defined('_MPBRT_TEMPLATES_')) {
            define('_MPBRT_TEMPLATES_', _MPBRT_ . "views/templates/");
        }
        
        if (!defined('_MPBRT_TEMPLATES_HOOK_')) {
            define('_MPBRT_TEMPLATES_HOOK_', _MPBRT_TEMPLATES_ . "hook/");
        }
        
        if (!defined('_MPBRT_TEMPLATES_FRONT_')) {
            define('_MPBRT_TEMPLATES_FRONT_', _MPBRT_TEMPLATES_ . "front/");
        }
    }
  
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install() ||
                !$this->registerHook('displayAdminOrder') ||
                !$this->registerHook('displayBackOfficeHeader') ||
                !$this->registerHook('displayAdminOrder') ||
                !$this->installTab()) {
            return false;
        }
        return true;
    }
    
    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }
    
    public function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminMpBrt';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'MP BRT';
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminParentOrders');
        $tab->module = $this->name;
        return $tab->add();
    }

    public function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminMpBrt');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        } else {
            return false;
        }
    }
    
    public function hookDisplayAdminOrder($params)
    {
        ClassMpLogger::clear();
        
        $smarty = Context::getContext()->smarty;
        
        $customer_id = (int)ConfigurationCore::get('MP_BRT_CUSTOMER_ID');
        $id_carrier_display = (int)ConfigurationCore::get('MP_BRT_ID_CARRIER_DISPLAY');
        
        $id_order = (int)Tools::getValue('id_order');
        $order = new OrderCore($id_order);
        if ($order->id_carrier!=$id_carrier_display) {
            return false;
        }
        $reference = $order->reference;
        $soap = new ClassMpSoap($customer_id, $this);
        $soap->getOrderHistory((int)$reference);
        $bolla = $soap->getBolla();
        if (!$bolla) {
            $smarty->assign('title_box', $soap->getResultMessage());
            $smarty->assign('title_code', $soap->getResultCode());
            $smarty->assign('events', array());
            $smarty->assign('brt_customer_reference', $soap->getCustomerReference());
            $smarty->assign('brt_tracking_id', '');
        } else {
            $smarty->assign('title_box', '');
            $smarty->assign('title_code', 0);
            $smarty->assign('events', $bolla->getEventi());
            $smarty->assign('brt_customer_reference', $soap->getCustomerReference());
            $smarty->assign('brt_tracking_id', $soap->getTrackingId());
        }
        
        $smarty->assign('soap', $soap);
        $smarty->assign('id_order', $id_order);
        
        return $this->display(__FILE__, 'displayShippingHistory.tpl');
    }
    
    public function hookDisplayBackOfficeHeader($params)
    {
        //$this->context->controller->addCSS(_MPBRT_CSS_URL_ . 'admin.css');
        //$this->context->controller->addCSS(_MPBRT_CSS_URL_ . 'getContent.css');
        //$this->context->controller->addJS(_MPBRT_JS_URL_ . 'label.js');
    }
    
    public function getContent()
    {
        ClassMpLogger::clear();
        
        $this->smarty = Context::getContext()->smarty;
        $message = $this->postProcess();
        return $message . $this->renderForm();
    }
    
    public function renderForm()
    {
        
        
        $customer_id = (int)ConfigurationCore::get('MP_BRT_CUSTOMER_ID');
        $id_carrier_display = (int)ConfigurationCore::get('MP_BRT_ID_CARRIER_DISPLAY');
        $id_tracking_order = (int)ConfigurationCore::get('MP_BRT_ID_TRACKING_ORDER');
        $id_delivered_order = (int)ConfigurationCore::get('MP_BRT_ID_DELIVERED_ORDER');
        $id_customer_reference = ConfigurationCore::get('MP_BRT_CUSTOMER_REFERENCE');
        $switch_display_error = (int)ConfigurationCore::get('MP_BRT_SWITCH_DISPLAY_ERROR');
        
        $this->addSwitch(
            'input_switch_display_error',
            $this->l('Show Tracking error?', 'mpbrt'),
            $this->smarty,
            $switch_display_error
        );
        
        $this->smarty->assign('brt_customer_id', $customer_id);
        $this->smarty->assign('brt_carrier_display_list', $this->getCarriers($id_carrier_display));
        $this->smarty->assign('brt_order_tracking_list', $this->getOrderStates($id_tracking_order));
        $this->smarty->assign('brt_order_delivered_list', $this->getOrderStates($id_delivered_order));
        $this->smarty->assign('brt_customer_reference', $id_customer_reference);
        $this->smarty->assign('brt_order_skipped', $this->getArrayOrderStates());
        $template  = $this->display(__FILE__, 'getContent.tpl');
        return $template;
    }
    
    private function getObjectList($objects, $id_selected)
    {
        $list = array();
        foreach ($objects as $object) {
            $output = new stdClass();
            $output->value = $object['id'];
            $output->name = $object['name'];
            if ($object['id']==$id_selected) {
                $output->selected = true;
            } else {
                $output->selected=false;
            }
            $list[] = $output;
        }
        return $list;
    }
    
    private function getCarriers($id_carrier)
    {
        $carriers = CarrierCore::getCarriers(Context::getContext()->language->id);
        foreach ($carriers as &$carrier) {
            $carrier['id'] = $carrier['id_carrier'];
        }
        return $this->getObjectList($carriers, $id_carrier);
    }
    
    private function getOrderStates($id_order_state)
    {
        $states = OrderStateCore::getOrderStates(Context::getContext()->language->id);
        foreach ($states as &$state) {
            $state['id'] = $state['id_order_state'];
        }
        return $this->getObjectList($states, $id_order_state);
    }
    
    private function getArrayOrderStates()
    {
        $order_states = OrderStateCore::getOrderStates(Context::getContext()->language->id);
        $order_checked = explode(",", ConfigurationCore::get('MP_BRT_SKIP_STATES'));
        
        foreach ($order_states as &$order_state) {
            if (in_array($order_state['id_order_state'], $order_checked)) {
                $order_state['checked']=true;
            } else {
                $order_state['checked']=false;
            }
        }
        
        return $order_states;
    }
    
    public function postProcess()
    {
        if (Tools::isSubmit('submit_customer_save')) {
            $customer_id = (int)Tools::getValue('input_customer_id', 0);
            $id_carrier_display = (int)Tools::getValue('input_select_carrier_display', 0);
            $id_tracking_order = (int)Tools::getValue('input_select_state_tracking', 0);
            $id_delivered_order = (int)Tools::getValue('input_select_state_delivered', 0);
            $id_customer_reference = Tools::getValue('input_select_customer_reference', 'reference');
            $switch_display_error = (int)Tools::getValue('input_switch_display_error', 0);
            $skip_states = implode(",", Tools::getValue('input_checkbox_skip_state'));
            
            ConfigurationCore::updateValue('MP_BRT_CUSTOMER_ID', $customer_id);
            ConfigurationCore::updateValue('MP_BRT_ID_CARRIER_DISPLAY', $id_carrier_display);
            ConfigurationCore::updateValue('MP_BRT_ID_TRACKING_ORDER', $id_tracking_order);
            ConfigurationCore::updateValue('MP_BRT_ID_DELIVERED_ORDER', $id_delivered_order);
            ConfigurationCore::updateValue('MP_BRT_CUSTOMER_REFERENCE', $id_customer_reference);
            ConfigurationCore::updateValue('MP_BRT_SKIP_STATES', $skip_states);
            ConfigurationCore::updateValue('MP_BRT_SWITCH_DISPLAY_ERROR', $switch_display_error);
            return $this->displayConfirmation($this->l('Configuration saved successfully.', 'mpbrt'));
        } else {
            return '';
        }
    }
    
    public function getHookController($hook_name)
    {
        // Include the controller file
        require_once(dirname(__FILE__).'/controllers/hook/'. $hook_name.'.php');

        // Build dynamically the controller name
        $controller_name = $this->name.$hook_name.'Controller';

        // Instantiate controller
        $controller = new $controller_name($this, __FILE__, $this->_path);

        // Return the controller
        return $controller;
    }
    
    public function addSwitch($name, $label, $smarty, $value)
    {
        $switch = new stdClass();
        $switch->label = $label;
        $switch->name = $name;
        $switch->value = $value;
        $smarty->assign('switch', $switch);
        $smarty->assign($name, $smarty->fetch(_MPBRT_TEMPLATES_HOOK_ . 'switch.tpl'));
    }
}
