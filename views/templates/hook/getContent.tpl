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
<style>
    .form-wrapper
    {
        margin-bottom: 15px;
    }
</style>

<div id='cover-wait-operations'></div>

<form class='defaultForm form-horizontal' method='post' id="form_brt_settings">                
    <div class='panel' id='panel-config'>
        <div class='panel-heading'>
            <i class="icon-cogs"></i>
            {l s='Configuration section' mod='mpbrt'}
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
                    value='{$brt_customer_id|escape:'htmlall':'UTF-8'}'
                    >
            </div>
            <div class="form-wrapper">
                <label class="control-label col-lg-3 ">{l s='Select Bartolini carrier' mod='mpbrt'}</label>
                <select 
                    id="input_select_carrier_display" 
                    name="input_select_carrier_display"
                    class="input fixed-width-lg" 
                    >
                    <option value="0">{l s='All' mod='mpbrt'}</option>
                    {foreach $brt_carrier_display_list as $option}
                        <option 
                            value='{$option->value|escape:'htmlall':'UTF-8'}' 
                            {if $option->selected|escape:'htmlall':'UTF-8'}selected = 'selected'{/if}>
                            {$option->name|escape:'htmlall':'UTF-8'}
                        </option>
                    {/foreach}
                </select>
            </div>
            <div class="form-wrapper">
                <label class="control-label col-lg-3 ">{l s='Select Order States to skip' mod='mpbrt'}</label>
                <div class='panel-body' style='height: 12em; overflow-y: auto;'>
                    <table class='table table-data-sheet' style='display: block;'>
                        <tbody>
                            {foreach $brt_order_skipped as $order_state}
                            <tr>
                                <td>
                                    <input 
                                        type="checkbox" 
                                        name="input_checkbox_skip_state[]" 
                                        value="{$order_state['id_order_state']|escape:'htmlall':'UTF-8'}" 
                                        {if $order_state['checked']}
                                            checked="checked"
                                        {/if}
                                         />
                                </td>
                                <td>
                                    {$order_state['name']|escape:'htmlall':'UTF-8'}
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-wrapper">
                <label class="control-label col-lg-3">{l s='Select State to change when set tracking id' mod='mpbrt'}</label>
                <select 
                    id="input_select_state_tracking" 
                    name="input_select_state_tracking"
                    class="input fixed-width-lg" 
                    >
                    <option value="0">{l s='None' mod='mpbrt'}</option>
                    {foreach $brt_order_tracking_list as $option}
                        <option 
                            value='{$option->value|escape:'htmlall':'UTF-8'}' 
                            {if $option->selected|escape:'htmlall':'UTF-8'}selected = 'selected'{/if}>
                            {$option->name|escape:'htmlall':'UTF-8'}
                        </option>
                    {/foreach}
                </select>
            </div>
            <div class="form-wrapper">
                <label class="control-label col-lg-3 ">{l s='Order state to change when delivered' mod='mpbrt'}</label>
                <select 
                    id="input_select_state_delivered" 
                    name="input_select_state_delivered"
                    class="input fixed-width-lg" 
                    >
                    <option value="0">{l s='None' mod='mpbrt'}</option>
                    {foreach $brt_order_delivered_list as $option}
                        <option 
                            value='{$option->value|escape:'htmlall':'UTF-8'}' 
                            {if $option->selected|escape:'htmlall':'UTF-8'}selected = 'selected'{/if} >
                            {$option->name|escape:'htmlall':'UTF-8'}
                        </option>
                    {/foreach}
                </select>
            </div>
            {$input_switch_display_error}
            <div class="form-wrapper">
                <label class="control-label col-lg-3 ">{l s='Select Customer reference' mod='mpbrt'}</label>
                <select 
                    id="input_select_customer_reference" 
                    name="input_select_customer_reference"
                    class="input fixed-width-lg" 
                    >
                    <option value="id_order" {if $brt_customer_reference=='id_order'} selected='selected' {/if}>
                        {l s='By Order id' mod='mpbrt'}
                    </option>
                    <option value="reference" {if $brt_customer_reference=='reference'} selected='selected' {/if}>
                        {l s='By Order reference' mod='mpbrt'}
                    </option>
                </select>
            </div>
        </div>
                    
        <div class="panel-footer">
            <button type="submit" value="1" id="submit_customer_save" name="submit_customer_save" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> 
                {l s='Save' mod='mpbrt'}
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
    
    function log(logger)
    {
        console.log(logger);
    }
</script>