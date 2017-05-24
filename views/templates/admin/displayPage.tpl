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

<div class="panel">
    <div class="panel-heading">
        <i class="icon-truck"></i>
        <span>{l s='Bartolini' mod='mpbrt'}</span>
    </div>
    <div class='panel-body'>
        <div class='panel-body'>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th><span class="title_box">{l s='ID'}</span></th>
                        <th><span class="title_box">{l s='DATE'}</span></th>
                        <th><span class="title_box">{l s='TIME'}</span></th>
                        <th><span class="title_box">{l s='EVENT'}</span></th>
                        <th><span class="title_box">{l s='BRANCH'}</span></th>
                        <th><span class="title_box">{l s='REFERENCE'}</span></th>
                        <th><span class="title_box">{l s='TRACKING_ID'}</span></th>
                    </tr>
                </thead>
                <tbody>
                    {$rows}
                </tbody>
            </table>
        </div>
        
        
        <pre>
            <xmp>
                sql:
                {$sql}
            </xmp>
            <xmp>
                content:
                {$cont|print_r}
            </xmp>
            <xmp>
                orders:
                {$orders|print_r}
            </xmp>
            <xmp>
                rows:
                {$rows|print_r}
            </xmp>
        </pre>
    </div>
    <div class='panel-footer'>
        
    </div>
</div>
