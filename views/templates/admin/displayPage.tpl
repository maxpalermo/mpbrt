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

{if isset($confirmation)}
    <div class="bootstrap">
        <div class="module_confirmation conf confirm alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {$confirmation|escape:'htmlall':'UTF-8'}
        </div>
    </div>
{/if}

{if !empty($errors)}
    <div class="bootstrap">
        <div class="module_error alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {foreach $errors as $error}
                <p>{$error|escape:'htmlall':'UTF-8'}</p>
            {/foreach}
        </div>
    </div>
{/if}

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
                            <th><span class="title_box">{l s='ID' mod='mpbrt'}</span></th>
                            <th><span class="title_box">{l s='DATE' mod='mpbrt'}</span></th>
                            <th><span class="title_box">{l s='TIME' mod='mpbrt'}</span></th>
                            <th><span class="title_box">{l s='EVENT' mod='mpbrt'}</span></th>
                            <th><span class="title_box">{l s='BRANCH' mod='mpbrt'}</span></th>
                            <th><span class="title_box">{l s='REFERENCE' mod='mpbrt'}</span></th>
                            <th><span class="title_box">{l s='TRACKING ID' mod='mpbrt'}</span></th>
                            <th><span class="title_box">{l s='CURRENT STATE' mod='mpbrt'}</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $rows as $row}
                        <tr>
                            <td style='color: {$row->COLOR|escape:'htmlall':'UTF-8'};'><i class='{$row->ICON|escape:'htmlall':'UTF-8'}'</td>
                            <td style='color: {$row->COLOR|escape:'htmlall':'UTF-8'};'>{$row->ID|escape:'htmlall':'UTF-8'}</td>
                            <td style='color: {$row->COLOR|escape:'htmlall':'UTF-8'};'>{$row->DATA|escape:'htmlall':'UTF-8'}</td>
                            <td style='color: {$row->COLOR|escape:'htmlall':'UTF-8'};'>{$row->ORA|escape:'htmlall':'UTF-8'}</td>
                            <td style='color: {$row->COLOR|escape:'htmlall':'UTF-8'};'>{$row->DESCRIZIONE|escape:'htmlall':'UTF-8'}</td>
                            <td style='color: {$row->COLOR|escape:'htmlall':'UTF-8'};'>{$row->FILIALE|escape:'htmlall':'UTF-8'}</td>
                            <td style='color: {$row->COLOR|escape:'htmlall':'UTF-8'};'>{$row->REFERENCE|escape:'htmlall':'UTF-8'}</td>
                            <td style='color: {$row->COLOR|escape:'htmlall':'UTF-8'};'>{$row->TRACKING_ID|escape:'htmlall':'UTF-8'}</td>
                            <td style='color: {$row->COLOR|escape:'htmlall':'UTF-8'};'>{$row->STATE|escape:'htmlall':'UTF-8'}</td>
                        </tr>
                        {/foreach}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" style="text-align: right;">
                                {l s='Total rows:' mod='mpbrt'}
                                <span id='brt_total_rows' style='font-weight: bold;'></span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <input type='hidden' name='input_hidden_orders' id='input_hidden_orders' value=''>

            <div class="panel-footer">
                <button type="submit" value="1" id="submit_process_orders" class="btn btn-default pull-right">
                    <i class="process-icon-save"></i> 
                    {l s='Save' mod='mpbrt'}
                </button>
                <input type='hidden' id='submit_hidden_process_orders' name="submit_process_orders" value='0'>
            </div>

            {assign var=debug value=false}
            {if $debug}    
            <pre>
                <xmp>
                    sql:
                    {$sql|escape:'htmlall':'UTF-8'}
                </xmp>
                <xmp>
                    content:
                    {{$cont|print_r}|escape:'htmlall':'UTF-8'}
                </xmp>
                <xmp>
                    orders:
                    {{$orders|print_r}|escape:'htmlall':'UTF-8'}
                </xmp>
                <xmp>
                    rows:
                    {{$rows|print_r}|escape:'htmlall':'UTF-8'}
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
        var totalRows = $('#brt-tracking-orders >tbody >tr').length;
        $('#brt_total_rows').html(totalRows);
        
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
           $("#brt_form").submit();
       });
    });
</script>