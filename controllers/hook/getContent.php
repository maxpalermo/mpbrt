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
    private $saved;
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
        $this->setMedia();
        $customer_id = (int)ConfigurationCore::get('MP_BRT_CUSTOMER_ID');
        
        $this->smarty->assign('brt_customer_id', $customer_id);
        if($this->saved) {
            $this->smarty->assign('saved', 1);
        }
        
        $template  = $this->module->display($this->file, 'getContent.tpl');
        if($this->saved) {
            $template = 
                    $this->module->displayConfirmation($this->module->l('Customer id saved successfully.', 'mpbrt'))
                    . $template;
        }
        return $template;
    }

    public function setMedia()
    {
        $this->module->setMedia();
        $this->context->controller->addCSS(_MPBRT_CSS_URL_ . 'getContent.css');
    }
    
    public function postProcess()
    {
        if(Tools::isSubmit('submit_customer_save')) {
            $customer_id = (int)Tools::getValue('input_customer_id','0');
            ConfigurationCore::updateValue('MP_BRT_CUSTOMER_ID', $customer_id);
            $this->saved = true;
        }
    }
    
    public function run()
    {
        $this->postProcess();
        $html_form = $this->renderForm();
        return $html_form;
    }
}
