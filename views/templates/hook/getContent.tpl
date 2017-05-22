{*
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
*}

<div id='cover-wait-operations'></div>

<form class='defaultForm form-horizontal' method='post' id="form_brt_settings">                
    <div class='panel' id='panel-config'>
        <div class='panel-heading'>
            <i class="icon-cogs"></i>
            {l s='Configuration section' mod='mpadvpayment'}
        </div>
        
        <div class="panel-body">
            <div class="form-wrapper">
                <label class="control-label col-lg-3 ">{l s='Customer id' mod='mpbrt'}</label>
                <input 
                    type="text" 
                    id="input_customer_id" 
                    name="input_customer_id"
                    class="input fixed-width-lg" 
                    onfocus='javascript:$(this).select();'
                    value="{$brt_customer_id}"
                    >
            </div>
        </div>
                
        <div class="panel-footer">
            <button type="submit" value="1" id="submit_customer_save" name="submit_customer_save" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> 
                {l s='Save' mod='mpadvpayment'}
            </button>
        </div>
    </div>
</form>
                            
<script type="text/javascript">
    $(window).bind("load",function()
    {
       $('#cover-wait-operations').fadeOut();
       $('#input_paypal_switch_off').click();
    }); // end onload function
    
    function switch_btn(value)
    {
        if (value==1) {
            switch_on();
        } else {
            switch_off();
        }
    }
    
    function switch_off()
    {
        log('switch off');
        $("#input_paypal_switch_val").attr('switch','0');
    }
    
    function switch_on()
    {
        log('switch on');
        $("#input_paypal_switch_val").attr('switch', '1');
    }
    
    function log(logger)
    {
        console.log(logger);
    }
</script>