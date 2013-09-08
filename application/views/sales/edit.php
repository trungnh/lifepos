<?php $this->load->view("partial/header"); ?>
<table id="title_bar">
	<tr>
		<td id="title_icon">
			<img src='<?php echo base_url()?>images/menubar/sales.png' alt='title icon' />
		</td>
		<td id="title"><?php echo lang('sales_register')." - ".lang('sales_edit_sale'); ?> POS <?php echo $sale_info['sale_id']; ?></td>
	</tr>
</table>
<br />
	
<div id="edit_sale_wrapper">
	<fieldset>
	<?php echo form_open("sales/save/".$sale_info['sale_id'],array('id'=>'sales_edit_form')); ?>
	<ul id="error_message_box"></ul>
	
	<div class="field_row clearfix">
	<?php echo form_label(lang('sales_receipt').':', 'sales_receipt'); ?>
		<div class='form_field'>
			<?php echo anchor('sales/receipt/'.$sale_info['sale_id'], 'POS '.$sale_info['sale_id'], array('target' => '_blank'));?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label(lang('sales_date').':', 'date'); ?>
		<div class='form_field'>
			<?php echo form_input(array('name'=>'date','value'=>date(get_date_format(), strtotime($sale_info['sale_time'])), 'id'=>'date'));?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label(lang('sales_customer').':', 'customer'); ?>
		<div class='form_field'>
			<?php echo form_dropdown('customer_id', $customers, $sale_info['customer_id'], 'id="customer_id"');?>
			<?php if ($sale_info['customer_id']) { ?>
				<?php echo anchor('sales/email_receipt/'.$sale_info['sale_id'], lang('sales_email_receipt'), array('id' => 'email_receipt'));?>
			<?php }?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label(lang('sales_employee').':', 'employee'); ?>
		<div class='form_field'>
			<?php echo form_dropdown('employee_id', $employees, $sale_info['employee_id'], 'id="employee_id"');?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label(lang('sales_comment').':', 'comment'); ?>
		<div class='form_field'>
			<?php echo form_textarea(array('name'=>'comment','value'=>$sale_info['comment'],'rows'=>'4','cols'=>'23', 'id'=>'comment'));?>
		</div>
	</div>
	
	<?php
	echo form_submit(array(
		'name'=>'submit',
		'id'=>'submit',
		'value'=>lang('common_submit'),
		'class'=>'submit_button float_left')
	);
	?>
	</form>

	<?php if ($sale_info['deleted'])
	{
	?>
	<?php echo form_open("sales/undelete/".$sale_info['sale_id'],array('id'=>'sales_undelete_form')); ?>
		<?php
		echo form_submit(array(
			'name'=>'submit',
			'id'=>'submit',
			'value'=>lang('sales_undelete_entire_sale'),
			'class'=>'submit_button float_right')
		);
		?>
	</form>
	<?php
	}
	else
	{
	?>
	<?php echo form_open("sales/delete/".$sale_info['sale_id'],array('id'=>'sales_delete_form')); ?>
		<?php
		echo form_submit(array(
			'name'=>'submit',
			'id'=>'submit',
			'value'=>lang('sales_delete_entire_sale'),
			'class'=>'delete_button float_right')
		);
		?>
	</form>
	<?php
	}
	?>
</fieldset>
</div>
<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{	
	$("#email_receipt").click(function()
	{
		$.get($(this).attr('href'), function()
		{
			alert("<?php echo lang('sales_receipt_sent'); ?>")
		});
		
		return false;
	});
	$('#date').datePicker({startDate: '<?php echo get_js_start_of_time_date(); ?>'});
	$("#sales_delete_form").submit(function()
	{
		if (!confirm('<?php echo lang("sales_delete_confirmation"); ?>'))
		{
			return false;
		}
	});
	
	$("#sales_undelete_form").submit(function()
	{
		if (!confirm('<?php echo lang("sales_undelete_confirmation"); ?>'))
		{
			return false;
		}
	});
	
	$('#sales_edit_form').validate({
		submitHandler:function(form)
		{
			$(form).ajaxSubmit({
			success:function(response)
			{
				if(response.success)
				{
					set_feedback(response.message,'success_message',false);
				}
				else
				{
					set_feedback(response.message,'error_message',true);	
					
				}
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
   		},
		messages: 
		{
		}
	});
});
</script>