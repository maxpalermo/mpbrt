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

<div class="form-group">
	<label class="control-label col-lg-3 ">
			<span>{$switch->label|escape:'htmlall':'UTF-8'}</span>
	</label>
	<div class="col-lg-9">
			<span 
				class="switch prestashop-switch fixed-width-lg" 
				id='{$switch->name|escape:'htmlall':'UTF-8'}_val'
				name='{$switch->name|escape:'htmlall':'UTF-8'}_val'
				switch='1'
				>
			<input 
				type="radio" 
				value="1" 
				name="{$switch->name|escape:'htmlall':'UTF-8'}"
				id="{$switch->name|escape:'htmlall':'UTF-8'}_on" 
				checked="checked"
				onclick='javascript:$("#{$switch->name|escape:'htmlall':'UTF-8'}_val").attr("switch",1);'>
			<label for="{$switch->name|escape:'htmlall':'UTF-8'}_on">{l s='YES' mod='mpbrt'}</label>
			<input 
				type="radio" 
				value="0" 
				name="{$switch->name|escape:'htmlall':'UTF-8'}"
				id="{$switch->name|escape:'htmlall':'UTF-8'}_off"
				onclick='javascript:$("#{$switch->name|escape:'htmlall':'UTF-8'}_val").attr("switch",0);'>
			<label for="{$switch->name|escape:'htmlall':'UTF-8'}_off">{l s='NO' mod='mpbrt'}</label>
			<a class="slide-button btn"></a>
			</span>
	</div>
	<div class="col-lg-9 col-lg-offset-3"></div> 
</div>
<script type='text/javascript'>
    $(document).ready(function(){
        {if $switch->value==1}
            $("#{$switch->name|escape:'htmlall':'UTF-8'}_on").click();
        {else}
            $("#{$switch->name|escape:'htmlall':'UTF-8'}_off").click();
        {/if}
    });
</script>
    