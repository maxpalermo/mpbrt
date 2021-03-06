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

<form class='defaultForm form-horizontal' method='post' id="form_brt_settings">
    <div class='panel'>
        <div class="panel-heading">
            <i class="icon icon-truck"></i>
            {l s='Carrier history' mod='mpbrt'}
            {if !empty($title_box)}
                <span style='color: #ad2b34; padding-left: 10px; font-weight: bold;'>
                    <i class='icon icon-warning'></i>
                    {$title_box|escape:'htmlall':'UTF-8'} ({$title_code|escape:'htmlall':'UTF-8'})
                </span>
            {/if}
        </div>
        <div class='panel-info'>
            <span>{l s='Customer reference:' mod='mpbrt'} <strong>{$brt_customer_reference|escape:'htmlall':'UTF-8'}</strong></span>
            <br>
            <span>{l s='Tracking id:' mod='mpbrt'} <strong>{$brt_tracking_id|escape:'htmlall':'UTF-8'}</strong></span>
        </div>
        <div class='panel-body'>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th><span class="title_box">{l s='ID' mod='mpbrt'}</span></th>
                        <th><span class="title_box">{l s='EVENT' mod='mpbrt'}</span></th>
                        <th><span class="title_box">{l s='DATE' mod='mpbrt'}</span></th>
                        <th><span class="title_box">{l s='TIME' mod='mpbrt'}</span></th>
                        <th><span class="title_box">{l s='BRANCH' mod='mpbrt'}</span></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $events as $evento}
                        <tr>
                            <td><i class='icon icon-truck'></i></td>
                            <td><span>{$evento->getId()|escape:'htmlall':'UTF-8'}</span></td>
                            <td><span>{$evento->getDescrizione()|escape:'htmlall':'UTF-8'}</span></td>
                            <td><span>{$evento->getData()|escape:'htmlall':'UTF-8'}</span></td>
                            <td><span>{$evento->getOra()|escape:'htmlall':'UTF-8'}</span></td>
                            <td><span>{$evento->getFiliale()|escape:'htmlall':'UTF-8'}</span></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        
        <div class="panel-footer">
            <button type="button" value="1" id="submit_cash_save" name="button_tracking_info" class="btn btn-default pull-right">
                <i class="process-icon-plus"></i> 
                {l s='More info' mod='mpbrt'}
            </button>
        </div>
    </div>
</form>

{assign var=debug value=false}
{if $debug}
<div class="panel">
    <div class='panel-heading'>
        <i class='icon-info'></i>
        INFO:
    </div>
    <span>CODICE: {$soap->getResultCode()|escape:'htmlall':'UTF-8'}</span>
    <br>
    <span>MESSAGGIO: {$soap->getResultMessage()|escape:'htmlall':'UTF-8'}</span>
    <pre>
        <strong>RESULT</strong>
        {{$soap->getResult()|print_r}|escape:'htmlall':'UTF-8'}
    </pre>
    <pre>
        <strong>EVENTS</strong>
        {{$events|print_r}|escape:'htmlall':'UTF-8'}
    </pre>
</div>
{/if}