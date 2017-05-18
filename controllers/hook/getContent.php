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

class MpBrtGetContentController
{
    private $path;
    private $lang;
    private $smarty;
    private $context;
    private $class;
    private $file;
    
    public function __construct($module, $file, $path)
    {
        $this->file = $file;
        $this->module = $module;
        $this->path = $path;
        $this->context = Context::getContext();
        $this->smarty = Context::getContext()->smarty;
        $this->lang = Context::getContext()->language->id;
    }
    
    public function renderForm()
    {
        //$this->class->setMedia();
        $this->setMedia();
        
        $this->smarty->assign('brt_customer_id', '1690519');
        
        $template  = $this->module->display($this->file, 'getContent.tpl');
        return $template;
    }

    public function setMedia()
    {
        $this->module->setMedia();
        $this->context->controller->addCSS(_MPBRT_CSS_URL_ . 'getContent.css');
    }
    
    public function getTaxList()
    {
        $taxes = TaxCore::getTaxes($this->_lang);
        $options = array();
        $options[] = "<option value='0'>" . $this->module->l('Please select', 'getContent') . "</option>";
        foreach ($taxes as $tax) {
            $options[] = "<option value='" . $tax['rate'] . "'>" . $tax['name'] . "</option>";
        }
        return implode("\n", $options);
    }
    
    public function getCarrierList()
    {
        $carriers = CarrierCore::getCarriers($this->_lang);
        $options = array();
        foreach ($carriers as $carrier) {
            $options[] = "<option value='" . $carrier['id_carrier'] . "'>" . Tools::strtoupper($carrier['name']) . "</option>";
        }
        return implode("\n", $options);
    }
    
    public function getCategoriesList()
    {
        $categories = CategoryCore::getCategories($this->_lang);
        $options = array();
        foreach ($categories as $category) {
            foreach ($category as $cat) {
                $options[] = "<option value='" . $cat['infos']['id_category'] . "'>" . Tools::strtoupper($cat['infos']['name']) . "</option>";
            }
        }
        return implode("\n", $options);
    }
    
    public function getManufacturersList()
    {
        $items = ManufacturerCore::getManufacturers();
        $options = array();
        foreach ($items as $item) {
            $options[] = "<option value='" . $item['id_manufacturer'] . "'>" . Tools::strtoupper($item['name']) . "</option>";
        }
        return implode("\n", $options);
    }
    
    public function getSuppliersList()
    {
        $items = SupplierCore::getSuppliers();
        $options = array();
        foreach ($items as $item) {
            $options[] = "<option value='" . $item['id_supplier'] . "'>" . Tools::strtoupper($item['name']) . "</option>";
        }
        return implode("\n", $options);
    }
    
    public function getProductsList()
    {
        $items = ProductCore::getSimpleProducts($this->_lang);
        $options = array();
        foreach ($items as $item) {
            $options[] = "<option value='" . $item['id_product'] . "'>" . Tools::strtoupper($item['name']) . "</option>";
        }
        return implode("\n", $options);
    }
    
    public function getOrderStateList()
    {
        $items = OrderStateCore::getOrderStates($this->_lang);
        $options = array();
        foreach ($items as $item) {
            $options[] = "<option value='" . $item['id_order_state'] . "'>" . Tools::strtoupper($item['name']) . "</option>";
        }
        return implode("\n", $options);
    }
    
    public function getCashValues()
    {
        $cash = new stdClass();
        $values = new ClassMpPaymentConfiguration();
        $values->read(classCart::CASH);
        
        $cash->input_switch_on      = $values->is_active;
        $cash->fee_type             = $values->fee_type;
        $cash->fee_amount           = $values->fee_amount;
        $cash->fee_percent          = $values->fee_percent;
        $cash->fee_min              = $values->fee_min;
        $cash->fee_max              = $values->fee_max;
        $cash->order_min            = $values->order_min;
        $cash->order_max            = $values->order_max;
        $cash->order_free           = $values->order_free;
        $cash->tax_included         = $values->tax_included;
        $cash->tax_rate             = number_format($values->tax_rate, 3);
        $cash->carriers             = $this->toArray($values->carriers);
        $cash->categories           = $this->toArray($values->categories);
        $cash->manufacturers        = $this->toArray($values->manufacturers);
        $cash->suppliers            = $this->toArray($values->suppliers);
        $cash->products             = $this->toArray($values->products);
        $cash->id_order_state       = $values->id_order_state;
        
        return $cash;
    }
    
    public function getBankwireValues()
    {
        $bankwire = new stdClass();
        $values = new ClassMpPaymentConfiguration();
        $values->read(classCart::BANKWIRE);
        
        $bankwire->input_switch_on      = $values->is_active;
        $bankwire->discount             = $values->discount;
        $bankwire->fee_type             = $values->fee_type;
        $bankwire->fee_amount           = $values->fee_amount;
        $bankwire->fee_percent          = $values->fee_percent;
        $bankwire->fee_min              = $values->fee_min;
        $bankwire->fee_max              = $values->fee_max;
        $bankwire->order_min            = $values->order_min;
        $bankwire->order_max            = $values->order_max;
        $bankwire->order_free           = $values->order_free;
        $bankwire->tax_included         = $values->tax_included;
        $bankwire->tax_rate             = number_format($values->tax_rate, 3);
        $bankwire->carriers             = $this->toArray($values->carriers);
        $bankwire->categories           = $this->toArray($values->categories);
        $bankwire->manufacturers        = $this->toArray($values->manufacturers);
        $bankwire->suppliers            = $this->toArray($values->suppliers);
        $bankwire->products             = $this->toArray($values->products);
        $bankwire->id_order_state       = $values->id_order_state;
        
        return $bankwire;
    }
    
    public function getPaypalValues()
    {
        $paypal = new stdClass();
        $values = new ClassMpPaymentConfiguration();
        $values->read(classCart::PAYPAL);
        
        $paypal->input_switch_on      = $values->is_active;
        $paypal->discount             = $values->discount;
        $paypal->fee_type             = $values->fee_type;
        $paypal->fee_amount           = $values->fee_amount;
        $paypal->fee_percent          = $values->fee_percent;
        $paypal->fee_min              = $values->fee_min;
        $paypal->fee_max              = $values->fee_max;
        $paypal->order_min            = $values->order_min;
        $paypal->order_max            = $values->order_max;
        $paypal->order_free           = $values->order_free;
        $paypal->tax_included         = $values->tax_included;
        $paypal->tax_rate             = number_format($values->tax_rate, 3);
        $paypal->carriers             = $this->toArray($values->carriers);
        $paypal->categories           = $this->toArray($values->categories);
        $paypal->manufacturers        = $this->toArray($values->manufacturers);
        $paypal->suppliers            = $this->toArray($values->suppliers);
        $paypal->products             = $this->toArray($values->products);
        $paypal->id_order_state       = $values->id_order_state;
        
        return $paypal;
    }
    
    public function toArray($input_string, $separator = ",")
    {
        if (empty($input_string)) {
            return array();
        }
        
        if (is_array($input_string)) {
            return $input_string;
        }
        
        return explode($separator, $input_string);
    }
    
    public function run()
    {
        $html_form = $this->renderForm();
        return $html_form;
    }
}
