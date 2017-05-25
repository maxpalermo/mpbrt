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
<form method='POST' name='brt_form' id='brt_form'>
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-truck"></i>
            <span>{l s='Bartolini' mod='mpbrt'}</span>
        </div>
        <div class='panel-body'>
            <div class='panel-body'>
                <table class="table" id='brt-tracking-orders'>
                    <thead>
                        <tr>
                            <th></th>
                            <th><span class="title_box">{l s='ID'}</span></th>
                            <th><span class="title_box">{l s='DATE'}</span></th>
                            <th><span class="title_box">{l s='TIME'}</span></th>
                            <th><span class="title_box">{l s='EVENT'}</span></th>
                            <th><span class="title_box">{l s='BRANCH'}</span></th>
                            <th><span class="title_box">{l s='REFERENCE'}</span></th>
                            <th><span class="title_box">{l s='TRACKING ID'}</span></th>
                            <th><span class="title_box">{l s='CURRENT STATE'}</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        {$rows}
                    </tbody>
                </table>
            </div>

            <input type='hidden' name='input_hidden_orders' id='input_hidden_orders' value=''>

            <div class="panel-footer">
                <button type="submit" value="1" id="submit_process_orders" class="btn btn-default pull-right">
                    <i class="process-icon-save"></i> 
                    {l s='Save' mod='mpadvpayment'}
                </button>
                <input type='hidden' id='submit_hidden_process_orders' name="submit_process_orders" value='0'>
            </div>

            {assign var=debug value=false}
            {if $debug}    
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
            {/if}
        </div>
        <div class='panel-footer'>

        </div>
    </div>
</form>

<script type='text/javascript'>
    $(document).ready(function(){
        var objOrders = new Array();
        $('#submit_process_orders').on('click', function(e){
            console.log("SUBMIT");
            console.log("objOrders: " + objOrders.length);
            e.preventDefault(); 
           
           var rows = $('#brt-tracking-orders > tbody >tr');
           console.log(rows);
           
           $(rows).each(function(){
                var reference = $(this).find("td:nth-child(7)").text();
                var tracking_id = $(this).find("td:nth-child(8)").text();
                var event = $(this).find("td:nth-child(5)").text();
                
                console.log('ref:' + reference + ", trkid: " + tracking_id + ", evt: " + event);
                
                if(event!=='ERROR') {
                    var order = new Object();

                    order.reference = reference;
                    order.tracking_id = tracking_id;
                    if(event==='CONSEGNATA') {
                        order.delivered=1;
                    } else {
                        order.delivered=0;
                    }
                    objOrders.push(order);
                }
           });
           
           $('#input_hidden_orders').val(JSON.stringify(objOrders));
           console.log("OBJECT: " + $('#input_hidden_orders').val());
           $("#submit_hidden_process_orders").val("1");
           //$("#brt_form").submit();
       });
    });
</script>