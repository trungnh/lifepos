<div id="menu-top">
    <table id="title_bar">
            <tr>
                    <td id="title_icon">
                            <img src='<?php echo base_url()?>images/menubar/receivings.png' alt='title icon' />
                    </td>
                    <td id="title">
                            <?php echo lang('receivings_register'); ?>
                    </td>
                    <td id="title_search">
                        <?php echo form_open("receivings/add",array('id'=>'add_item_form')); ?>
                            <?php echo form_input(array('name'=>'item','id'=>'item','size'=>'30','class'=>'search'));?>
                        </form>
                        
                    </td>
            </tr>
    </table>
</div>
<div class="main-pos">
<?php $this->load->view("partial/header"); ?>

    <div class="table-content-menu">
        <div id="menu-table-data">
            <div class="menu-table-data" id="sales-new-item">
                <?php echo anchor("items/view/-1/width~550",
                "<div class='small_button'><span>".lang('sales_new_item')."</span></div>",
                array('class'=>'thickbox none','title'=>lang('sales_new_item')));?>
            </div>
            <div class="menu-table-data">
                <?php 
                echo anchor("suppliers/view/-1/width~550",
                "<div class='small_button' style='margin:0 auto;'><span>".lang('receivings_new_supplier')."</span></div>",
                array('class'=>'thickbox none','title'=>lang('receivings_new_supplier')));
                ?>
            </div>
            <div class="menu-table-data">
                <?php echo form_open("receivings/change_mode",array('id'=>'mode_form')); ?>
                <span><?php echo lang('receivings_mode') ?></span>
                <?php echo form_dropdown('mode',$modes,$mode,'onchange="$(\'#mode_form\').submit();"'); ?>
                </form>
            </div>
            <?php if(count($cart) >0){?>
            <div class="cancel-receiving">
                <?php echo form_open("receivings/cancel_receiving",array('id'=>'cancel_sale_form')); ?>
                    <div class='small_button' id='cancel_sale_button'>
                        <span>
                                <?php
                                echo lang('receivings_cancel_receiving');
                                ?>
                        </span>
                    </div>
                </form>
            </div>
            <?php }?>
        </div>
    </div>
    
<div id="register_container" class="receiving">
<table>
	<tr>
		<td id="register_items_container">						
			<div id="register_holder">
			<table id="register">
				<thead>
					<tr>
<th id="reg_item_del"></th>
<th id="reg_item_name"><?php echo lang('receivings_item_name'); ?></th>
<th id="reg_item_price"><?php echo lang('receivings_cost'); ?></th>
<th id="reg_item_qty"><?php echo lang('receivings_quantity'); ?></th>
<th id="reg_item_discount"><?php echo lang('receivings_discount'); ?></th>
<th id="reg_item_total"><?php echo lang('receivings_total'); ?></th>
<th id="reg_item_update"></th>
					</tr>
				</thead>
				<tbody id="cart_contents">
<?php
if(count($cart)==0)
{
?>
<tr><td colspan='7' style="height:60px;border:none;">
<div class='warning_message' style='padding:7px;'><?php echo lang('sales_no_items_in_cart'); ?></div>
</td></tr>
<?php
}
else
{
	foreach(array_reverse($cart, true) as $line=>$item)
	{
		$cur_item_info = $this->Item->get_info($item['item_id']);
		echo form_open("receivings/edit_item/$line");
	?>
		<tr id="reg_item_top">
			<td id="reg_item_del"><?php echo anchor("receivings/delete_item/$line",lang('common_delete'));?></td>
			<td id="reg_item_name"><?php echo $item['name']; ?></td>
		<?php if ($items_module_allowed){ ?>
			<td id="reg_item_price"><?php echo form_input(array('name'=>'price','value'=>$item['price'],'size'=>'6'));?></td>
		<?php }else{ ?>
			<td id="reg_item_price"><?php echo $item['price']; ?></td>
		<?php echo form_hidden('price',$item['price']); ?>
		<?php }	?>
			<td id="reg_item_qty">
		<?php echo form_input(array('name'=>'quantity','value'=>$item['quantity'],'size'=>'2'));?>
			</td>
			<td id="reg_item_discount"><?php echo form_input(array('name'=>'discount','value'=>$item['discount'],'size'=>'3'));?></td>
			<td id="reg_item_total"><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
			<td id="reg_item_update"><?php echo form_submit("edit_item", lang('sales_edit_item'));?></td>
		</tr>
		<tr id="reg_item_bottom">
			<td id="reg_item_descrip_label"><?php echo lang('sales_description_abbrv').':';?></td>
			<td id="reg_item_descrip" colspan="6">
		<?php 
			echo $item['description'];
        	echo form_hidden('description',$item['description']);
        ?>
			</td>
		</tr>
	</form>
	<?php
	}
}
?>
				</tbody>
			</table>
			</div>
			<div id="reg_item_base"></div>
		</td>
		<td style="width:8px;"></td>
		<td id="over_all_sale_container">
			<div id="overall_sale">							

				<div id="customer_info_shell">
					<?php
					if(isset($supplier))
					{
						echo "<div id='customer_info_filled'>";
							echo '<div id="customer_name">'.character_limiter($supplier, 25).'</div>';
							echo '<div id="customer_email"></div>';
							echo '<div id="customer_edit">'.anchor("suppliers/view/$supplier_id/width~550", lang('common_edit'),  array('class'=>'thickbox none','title'=>lang('suppliers_update'))).'</div>';
							echo '<div id="customer_remove">'.anchor("receivings/delete_supplier", lang('sales_detach')).'</div>';
						echo "</div>";
					}
					else
					{ ?>
						<div id='customer_info_empty'>
							<?php echo form_open("receivings/select_supplier",array('id'=>'select_supplier_form')); ?>
							<label id="customer_label" for="supplier">
								<?php echo lang('receivings_select_supplier'); ?>
							</label>
							<?php echo form_input(array('name'=>'supplier','id'=>'supplier','size'=>'30','value'=>lang('receivings_start_typing_supplier_name')));?>
							</form>
							
							<div class="clearfix">&nbsp;</div>
						</div>
					<?php } ?>
				</div>
			
				<div id='sale_details'>
					<table id="sales_items_total">
						<tr>
							<td class="left"><?php echo lang('sales_total'); ?>:</td>
							<td class="right"><?php echo to_currency($total); ?></td>
						</tr>
					</table>
				</div>
				
				<?php
				// Only show this part if there are Items already in the Table.
				if(count($cart) > 0){ ?>

					<div id="finish_sale">
						<?php echo form_open("receivings/complete",array('id'=>'finish_sale_form')); ?>

						<div id="make_payment" >
							<table id="make_payment_table">
								<tr id="mpt_top">
									<td>
										<?php echo lang('sales_payment').':   ';?>
	
										<?php echo form_dropdown('payment_type',$payment_options);?>
									</td>
								</tr>
								<tr id="mpt_bottom">
									<td id="tender" colspan="2">
										<?php echo form_input(array('name'=>'amount_tendered','value'=>'','size'=>'10')); ?>
									</td>
								</tr>
							</table>
						</div>
						
						<label id="comment_label" for="comment"><?php echo lang('common_comments'); ?>:</label>
						<?php echo form_textarea(array('name'=>'comment', 'id' => 'comment', 'value'=>'','rows'=>'4'));?>
						
						<?php echo "<div class='small_button' id='finish_sale_button' style='float:right;margin-top:5px;'><span>".lang('receivings_complete_receiving')."</span></div>"; ?>
					</div>
				<?php } ?>

			

			
			</div><!-- END OVERALL-->		
		</td>
	</tr>
</table>
<div id="feedback_bar"></div>

<script type="text/javascript">
<?php
if(isset($error))
{
	echo "set_feedback('$error','error_message',false);";
}

if (isset($warning))
{
	echo "set_feedback('$warning','warning_message',false);";
}

if (isset($success))
{
	echo "set_feedback('$success','success_message',false);";
}
?>
</script>

</div>

<?php $this->load->view("partial/footer"); ?>


<script type="text/javascript" language="javascript">
$(document).ready(function()
{
//        $("#customer_info_shell").load($("#select_supplier_form").arrt("action"), $("#supplier").val(), function(){
//            alert("dfs");
//        });

        $( "#item" ).autocomplete({
            source: '<?php echo site_url("receivings/item_search"); ?>',
            delay: 10,
            autoFocus: false,
            minLength: 0,
            select: function(event, ui)
            {
                $( "#item" ).val(ui.item.value);
                $("#add_item_form").submit();
            }
	});

	$('#item').focus();

	$('#item').blur(function()
        {
            $(this).attr('value',"<?php echo lang('sales_start_typing_item_name'); ?>");
        });

	$('#item,#supplier').click(function()
        {
            $(this).attr('value','');
        });

	$( "#supplier" ).autocomplete({
            source: '<?php echo site_url("receivings/supplier_search"); ?>',
            delay: 10,
            autoFocus: false,
            minLength: 0,
            select: function(event, ui)
            {
                $( "#supplier" ).val(ui.item.value);
                $("#select_supplier_form").submit();
            }
	});

        $('#supplier').blur(function()
        {
            $(this).attr('value',"<?php echo lang('receivings_start_typing_supplier_name'); ?>");
        });

        $("#finish_sale_button").click(function()
        {
            if (confirm('<?php echo lang("receivings_confirm_finish_receiving"); ?>'))
            {
    		$('#finish_sale_form').submit();
            }
        });

        $("#cancel_sale_button").click(function()
        {
            if (confirm('<?php echo lang("receivings_confirm_cancel_receiving"); ?>'))
            {
    		$('#cancel_sale_form').submit();
            }
        });

    });

function post_item_form_submit(response)
{
	if(response.success)
	{
		$("#item").attr("value",response.item_id);
		$("#add_item_form").submit();
	}
}

function post_person_form_submit(response)
{
	if(response.success)
	{
		$("#supplier").attr("value",response.person_id);
		$("#select_supplier_form").submit();
	}
}

</script>