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

require_once(_PS_TOOL_DIR_.'tcpdf/config/lang/eng.php');
require_once(_PS_TOOL_DIR_.'tcpdf/tcpdf.php');
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
        
        $curl = new classMpSoap();
        $this->context->smarty->assign('response', $curl->request('', array()));
        $content = $this->smarty->fetch(_MPBRT_TEMPLATES_ . 'admin/displayPage.tpl');
        
        $this->context->smarty->assign('content', $this->content . $content);
    } 
}
