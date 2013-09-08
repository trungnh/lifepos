<div id="menu-top">
    <table id="title_bar">
            <tr>
                    <td id="title_icon">
                            <img src='<?php echo base_url()?>images/menubar/sales.png' alt='title icon' />
                    </td>
                    <td id="title">
                            <?php echo lang('sales_register'); ?>
                    </td>
                    <!-- form search-->
                    <td id="title_search">
                        <?php echo form_open("sales/add",array('id'=>'add_item_form')); ?>
                        <?php echo form_input(array('name'=>'item','id'=>'item','size'=>'40', 'accesskey' => 'i','class' =>'search'));?>
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
                array('class'=>'thickbox none','title'=>lang('sales_new_item')));
                ?>
            </div>
            <div class="menu-table-data">
                <?php
                echo anchor("customers/view/-1/width~550",
                "<div class='small_button' style='margin:0 auto;'> <span>".lang('sales_new_customer')."</span> </div>", array('class'=>'thickbox none','title'=>lang('sales_new_customer')));
                ?>
            </div>
            <div class="menu-table-data">
                <?php echo form_open("sales/change_mode",array('id'=>'mode_form')); ?>
                <span><?php echo lang('sales_mode') ?></span>
                <?php echo form_dropdown('mode',$modes,$mode,'onchange="$(\'#mode_form\').submit();"'); ?>
                </form>
            </div>
            <?php if(count($cart) >0){?>
            <?php echo form_open("sales/cancel_sale",array('id'=>'cancel_sale_form')); ?>
            <div class="cancel-receiving">                
                <div class='small_button' id='cancel_sale_button'>
                    <span>
                        <?php echo lang('sales_cancel_sale'); ?>
                    </span>
                </div>
            </div>            
            <div class="cancel-receiving">
                    <div class='small_button' id='suspend_sale_button'>
                        <span>
                            <?php echo lang('sales_suspend_sale');?>
                        </span>
                    </div>
            </div>
            </form>
            <?php }?>
            <div class="menu-table-data">
                <?php echo anchor("sales/suspended/width~550",
                "<div class='small_button'>".lang('sales_suspended_sales')."</div>",
                array('class'=>'thickbox none','title'=>lang('sales_suspended_sales')));
                ?>
            </div>
            
        </div>
        
    </div>







<div id="register_container" class="sales">
<table>
	<tr>
		<td id="register_items_container">
                    
			<div id="register_holder">
			<table id="register">
				<thead>
					<tr>
<th id="reg_item_del"></th>
<th id="reg_item_name"><?php echo lang('sales_item_name'); ?></th>
<th id="reg_item_number"><?php echo lang('sales_item_number'); ?></th>
<th id="reg_item_stock"><?php echo lang('sales_stock'); ?></th>
<th id="reg_item_price"><?php echo lang('sales_price'); ?></th>
<th id="reg_item_qty"><?php echo lang('sales_quantity'); ?></th>
<th id="reg_item_discount"><?php echo lang('sales_discount'); ?></th>
<th id="reg_item_total"><?php echo lang('sales_total'); ?></th>
<th id="reg_item_update"></th>
					</tr>
				</thead>
				<tbody id="cart_contents">
<?php
if(count($cart)==0)
{
?>
<tr><td colspan='9' style="height:60px;border:none;">
<div class='warning_message' style='padding:7px;'><?php echo lang('sales_no_items_in_cart'); ?></div>
</td></tr>
<?php
}
else
{
	foreach(array_reverse($cart, true) as $line=>$item)
	{
		$cur_item_info = isset($item['item_id']) ? $this->Item->get_info($item['item_id']) : $this->Item_kit->get_info($item['item_kit_id']);
		echo form_open("sales/edit_item/$line");
	?>
		<tr id="reg_item_top">
			<td id="reg_item_del"><?php echo anchor("sales/delete_item/$line",lang('common_delete'));?></td>
			<td id="reg_item_name"><?php echo $item['name']; ?></td>
			<td id="reg_item_number"><?php echo isset($item['item_id']) ? $item['item_number'] : $item['item_kit_number']; ?></td>
			<td id="reg_item_stock"><?php echo property_exists($cur_item_info, 'quantity') ? $cur_item_info->quantity : ''; ?></td>
		<?php if ($items_module_allowed){ ?>
			<td id="reg_item_price"><?php echo form_input(array('name'=>'price','value'=>$item['price'],'size'=>'6'));?></td>
		<?php }else{ ?>
			<td id="reg_item_price"><?php echo $item['price']; ?></td>
		<?php echo form_hidden('price',$item['price']); ?>
		<?php }	?>
			<td id="reg_item_qty">
		<?php if(isset($item['is_serialized']) && $item['is_serialized']==1){
			echo $item['quantity'];
			echo form_hidden('quantity',$item['quantity']);
		}else{
			echo form_input(array('name'=>'quantity','value'=>$item['quantity'],'size'=>'2'));
		}?>
			</td>
			<td id="reg_item_discount"><?php echo form_input(array('name'=>'discount','value'=>$item['discount'],'size'=>'3'));?></td>
			<td id="reg_item_total"><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
			<td id="reg_item_update"><?php echo form_submit("edit_item", lang('common_update'));?></td>
		</tr>
		<tr id="reg_item_bottom">
			<td id="reg_item_descrip_label"><?php echo lang('sales_description_abbrv').':';?></td>
			<td id="reg_item_descrip" colspan="4">
		<?php if(isset($item['allow_alt_description']) && $item['allow_alt_description']==1){
        		echo form_input(array('name'=>'description','value'=>$item['description'],'size'=>'20'));
        	}else{
				if ($item['description']!=''){
					echo $item['description'];
        			echo form_hidden('description',$item['description']);
        		}else{
        			echo 'None';
        			echo form_hidden('description','');
        		}
        	}?>
			</td>
			<td id="reg_item_serial_label">
		<?php if(isset($item['is_serialized']) && $item['is_serialized']==1){
				echo lang('sales_serial').':';
			}?>
			</td>
			<td id="reg_item_serial" colspan="3">
		<?php if(isset($item['is_serialized']) && $item['is_serialized']==1)
        	{
        		echo form_input(array('name'=>'serialnumber','value'=>$item['serialnumber'],'size'=>'20'));
			}else{
				echo form_hidden('serialnumber', '');
			}?>
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
			<?php if ($this->config->item('track_cash')) { ?>
			<div>
				<?php echo anchor(site_url('sales/closeregister?continue=home'), lang('sales_close_register')); ?>
			</div>
			<?php } ?>
		</td>
		<td style="width:8px;"></td>
		<td id="over_all_sale_container">
			<div id="overall_sale">
				
				<div id="customer_info_shell">
					<?php
					if(isset($customer))
					{
						echo "<div id='customer_info_filled'>";
							echo '<div id="customer_name">'.character_limiter($customer, 25).'</div>';
							echo '<div id="customer_email"></div>';
							echo '<div id="customer_edit">'.anchor("customers/view/$customer_id/width~550", lang('common_edit'),  array('class'=>'thickbox none','title'=>lang('customers_update'))).'</div>';
							echo '<div id="customer_remove">'.anchor("sales/delete_customer", lang('sales_detach')).'</div>';
						echo "</div>";
					}
					else
					{ ?>
						<div id='customer_info_empty'>
							<?php echo form_open("sales/select_customer",array('id'=>'select_customer_form')); ?>
							<label id="customer_label" for="customer">
								<?php echo lang('sales_select_customer'); ?>
							</label>
							<?php echo form_input(array('name'=>'customer','id'=>'customer','size'=>'30','value'=>lang('sales_start_typing_customer_name'),  'accesskey' => 'c'));?>
							</form>
							
							<div class="clearfix">&nbsp;</div>
						</div>
					<?php } ?>
				</div>
			
				<div id='sale_details'>
					<table id="sales_items">
						<tr>
							<td class="left"><?php echo lang('sales_items_in_cart'); ?>:</td>
							<td class="right"><?php echo $items_in_cart; ?></td>
						</tr>
						<?php foreach($payments as $payment) {?>
							<?php if (strpos($payment['payment_type'], lang('sales_giftcard'))!== FALSE) {?>
						<tr>
							<td class="left"><?php echo $payment['payment_type']. ' '.lang('sales_balance') ?>:</td>
							<td class="right"><?php echo to_currency($this->Giftcard->get_giftcard_value(end(explode(':', $payment['payment_type']))) - $payment['payment_amount']);?></td>
						</tr>
							<?php }?>
						<?php }?>
						<tr>
							<td class="left"><?php echo lang('sales_sub_total'); ?>:</td>
							<td class="right"><?php echo to_currency($subtotal); ?></td>
						</tr>
						<?php foreach($taxes as $name=>$value) { ?>
						<tr>
							<td class="left"><?php echo $name; ?>:</td>
							<td class="right"><?php echo to_currency($value); ?></td>
						</tr>
						<?php }; ?>
					</table>
					<table id="sales_items_total">
						<tr>
							<td class="left"><?php echo lang('sales_total'); ?>:</td>
							<td class="right"><?php echo to_currency($total); ?></td>
						</tr>
					</table>
				</div>
				
				<?php
				// Only show this part if there are Items already in the sale.
				if(count($cart) > 0){ ?>

					<div id="Payment_Types" >
				
						<?php
						// Only show this part if there is at least one payment entered.
						if(count($payments) > 0)
						{
						?>
							<table id="register">
							<thead>
							<tr>
							<th id="pt_delete"></th>
							<th id="pt_type"><?php echo lang('sales_type'); ?></th>
							<th id="pt_amount"><?php echo lang('sales_amount'); ?></th>
				
				
							</tr>
							</thead>
							<tbody id="payment_contents">
							<?php
								foreach($payments as $payment_id=>$payment)
								{
								echo form_open("sales/edit_payment/$payment_id",array('id'=>'edit_payment_form'.$payment_id));
								?>
								<tr>
								<td id="pt_delete"><?php echo anchor("sales/delete_payment/$payment_id",'['.lang('common_delete').']');?></td>
				
				
								<td id="pt_type"><?php echo  $payment['payment_type']    ?> </td>
								<td id="pt_amount"><?php echo  to_currency($payment['payment_amount'])  ?>  </td>
				
				
								</tr>
								</form>
								<?php
								}
								?>
							</tbody>
							</table>
						<?php } ?>

						<table id="amount_due">
						<tr class="<?php if($payments_cover_total){ echo 'covered'; }?>">
							<td>
								<div class="float_left" style="font-size:.8em;"><?php echo lang('sales_amount_due'); ?>:</div>
							</td>
							<td style="text-align:right; ">
								<div class="float_left" style="text-align:right;font-weight:bold;"><?php echo to_currency($amount_due); ?></div>
							</td>
						</tr>
					</table>

						<div id="make_payment">
							<?php echo form_open("sales/add_payment",array('id'=>'add_payment_form')); ?>
							<table id="make_payment_table">
								<tr id="mpt_top">
									<td id="add_payment_text">
										<?php echo lang('sales_add_payment'); ?>:
									</td>
									<td>
										<?php echo form_dropdown('payment_type',$payment_options,array(), 'id="payment_types"');?>
									</td>
								</tr>
								<tr id="mpt_bottom">
									<td id="tender" colspan="2">
										<?php echo form_input(array('name'=>'amount_tendered','id'=>'amount_tendered','value'=>to_currency_no_money($amount_due),'size'=>'10', 'accesskey' => 'p'));	?>
									</td>
								</tr>
							</table>
							<div class='small_button' id='add_payment_button'>
								<span><?php echo lang('sales_add_payment'); ?></span>
							</div>
							</form>
						</div>
					</div>

					<?php
					if(!empty($customer_email))
					{
						echo '<div id="email_customer">';
						echo form_checkbox(array(
							'name'        => 'email_receipt',
							'id'          => 'email_receipt',
							'value'       => '1',
							'checked'     => (boolean)$email_receipt,
							)).' '.lang('sales_email_receipt').': <br /><b style="font-size:1.1em; padding-left: 17px;">'.character_limiter($customer_email, 25).'</b><br />';
						echo '</div>';
					}
					// Only show this part if there is at least one payment entered.
					if(count($payments) > 0){?>
						<div id="finish_sale">
							<?php echo form_open("sales/complete",array('id'=>'finish_sale_form')); ?>
							<?php							 
							if ($payments_cover_total)
							{
							echo '<label id="comment_label" for="comment">';
							echo lang('common_comments');
							echo ':</label>';
							echo form_textarea(array('name'=>'comment', 'id' => 'comment', 'value'=>$comment,'rows'=>'1',  'accesskey' => 'o'));
							
							echo "<div class='small_button' id='finish_sale_button' style='float:left;margin-top:5px;'><span>".lang('sales_complete_sale')."</span></div>";
							}
							?>
						</div>
					</form>
					<?php }	?>
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
	$( "#item" ).autocomplete({
		source: '<?php echo site_url("sales/item_search"); ?>',
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

	$('#item,#customer').click(function()
    {
    	$(this).attr('value','');
    });

	$( "#customer" ).autocomplete({
		source: '<?php echo site_url("sales/customer_search"); ?>',
		delay: 10,
		autoFocus: false,
		minLength: 0,
		select: function(event, ui)
		{
			$("#customer").val(ui.item.value);
			$("#select_customer_form").submit();
		}
	});

    $('#customer').blur(function()
    {
    	$(this).attr('value',"<?php echo lang('sales_start_typing_customer_name'); ?>");
    });
	
	$('#comment').change(function() 
	{
		$.post('<?php echo site_url("sales/set_comment");?>', {comment: $('#comment').val()});
	});
	
	$('#email_receipt').change(function() 
	{
		$.post('<?php echo site_url("sales/set_email_receipt");?>', {email_receipt: $('#email_receipt').is(':checked') ? '1' : '0'});
	});
	
	
    $("#finish_sale_button").click(function()
    {
    	if (confirm('<?php echo lang("sales_confirm_finish_sale"); ?>'))
    	{
    		$('#finish_sale_form').submit();
    	}
    });

	$("#suspend_sale_button").click(function()
	{
		if (confirm('<?php echo lang("sales_confirm_suspend_sale"); ?>'))
    	{
			window.location = '<?php echo site_url("sales/suspend"); ?>';
    	}
	});

    $("#cancel_sale_button").click(function()
    {
    	if (confirm('<?php echo lang("sales_confirm_cancel_sale"); ?>'))
    	{
    		$('#cancel_sale_form').submit();
    	}
    });

	$("#add_payment_button").click(function()
	{
	   $('#add_payment_form').submit();
    });

	$("#payment_types").change(checkPaymentTypeGiftcard).ready(checkPaymentTypeGiftcard)
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
		if ($("#select_customer_form").length == 1)
		{
			$("#customer").attr("value",response.person_id);
			$("#select_customer_form").submit();
		}
		else
		{
			window.location = '<?php echo site_url('sales/index');?>';
		}
	}
}

function checkPaymentTypeGiftcard()
{
	if ($("#payment_types").val() == "<?php echo lang('sales_giftcard'); ?>")
	{
		$("#amount_tendered_label").html("<?php echo lang('sales_giftcard_number'); ?>");
		$("#amount_tendered").val('');
		$("#amount_tendered").focus();
	}
	else
	{
		$("#amount_tendered_label").html("<?php echo lang('sales_amount_tendered'); ?>");		
	}
}

</script>
