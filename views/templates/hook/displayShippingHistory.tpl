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
        </div>
        <div class='panel-body'>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th><span class="title_box">{l s='ID'}</span></th>
                        <th><span class="title_box">{l s='EVENT'}</span></th>
                        <th><span class="title_box">{l s='DATE'}</span></th>
                        <th><span class="title_box">{l s='TIME'}</span></th>
                        <th><span class="title_box">{l s='BRANCH'}</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class='icon icon-truck'></i></td>
                        <td><span>{l s='701'}</span></td>
                        <td><span>{l s='RITIRATA'}</span></td>
                        <td><span>{l s='23.03.2017'}</span></td>
                        <td><span>{l s='12.00'}</span></td>
                        <td><span>{l s='CASTROVILLARI'}</span></td>
                    </tr>
                    <tr>
                        <td><i class='icon icon-truck'></i></td>
                        <td><span>{l s='702'}</span></td>
                        <td><span>{l s='PARTITA'}</span></td>
                        <td><span>{l s='23.03.2017'}</span></td>
                        <td><span>{l s='21.00'}</span></td>
                        <td><span>{l s='CASTROVILLARI'}</span></td>
                    </tr>
                    <tr>
                        <td><i class='icon icon-truck'></i></td>
                        <td><span>{l s='703'}</span></td>
                        <td><span>{l s='ARRIVATA IN FILIALE'}</span></td>
                        <td><span>{l s='24.03.2017'}</span></td>
                        <td><span>{l s='08.14'}</span></td>
                        <td><span>{l s='CREMONA'}</span></td>
                    </tr>
                    <tr>
                        <td><i class='icon icon-hand-up'></i></td>
                        <td><span>{l s='704'}</span></td>
                        <td><span>{l s='CONSEGNATA'}</span></td>
                        <td><span>{l s='23.03.2017'}</span></td>
                        <td><span>{l s='12.04'}</span></td>
                        <td><span>{l s='CREMONA'}</span></td>
                    </tr>
                </tbody>
            </table>

        </div>
        
        <div class="panel-footer">
            <button type="submit" value="1" id="submit_cash_save" name="submit_cash_save" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> 
                {l s='Save' mod='mpadvpayment'}
            </button>
        </div>
    </div>
</form>
            