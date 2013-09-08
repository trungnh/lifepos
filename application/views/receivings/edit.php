<?php $this->load->view("partial/header"); ?>
<table id="title_bar">
	<tr>
		<td id="title_icon">
			<img src='<?php echo base_url()?>images/menubar/receivings.png' alt='title icon' />
		</td>
		<td id="title"><?php echo lang('receivings_register')." - ".lang('receivings_edit_receiving'); ?> RECV <?php echo $receiving_info['receiving_id']; ?></td>
	</tr>
</table>
<br />
	
<div id="edit_sale_wrapper">
	<fieldset>
	<?php echo form_open("receivings/save/".$receiving_info['receiving_id'],array('id'=>'receivings_edit_form')); ?>
	<ul id="error_message_box"></ul>
	
	<div class="field_row clearfix">
	<?php echo form_label(lang('receivings_receipt').':', 'receipt'); ?>
		<div class='form_field'>
			<?php echo anchor('receivings/receipt/'.$receiving_info['receiving_id'], 'RECV '.$receiving_info['receiving_id'], array('target' => '_blank'));?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label(lang('sales_date').':', 'date'); ?>
		<div class='form_field'>
			<?php echo form_input(array('name'=>'date','value'=>date(get_date_format(), strtotime($receiving_info['receiving_time'])), 'id'=>'date'));?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label(lang('receivings_supplier').':', 'supplier'); ?>
		<div class='form_field'>
			<?php echo form_dropdown('supplier_id', $suppliers, $receiving_info['supplier_id'], 'id="supplier_id"');?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label(lang('sales_employee').':', 'employee'); ?>
		<div class='form_field'>
			<?php echo form_dropdown('employee_id', $employees, $receiving_info['employee_id'], 'id="employee_id"');?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label(lang('sales_comment').':', 'comment'); ?>
		<div class='form_field'>
			<?php echo form_textarea(array('name'=>'comment','value'=>$receiving_info['comment'],'rows'=>'4','cols'=>'23', 'id'=>'comment'));?>
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

	<?php if ($receiving_info['deleted'])
	{
	?>
	<?php echo form_open("receivings/undelete/".$receiving_info['receiving_id'],array('id'=>'receivings_undelete_form')); ?>
		<?php
		echo form_submit(array(
			'name'=>'submit',
			'id'=>'submit',
			'value'=>lang('receivings_undelete_entire_sale'),
			'class'=>'submit_button float_right')
		);
		?>
	</form>
	<?php
	}
	else
	{
	?>
	<?php echo form_open("receivings/delete/".$receiving_info['receiving_id'],array('id'=>'receivings_delete_form')); ?>
		<?php
		echo form_submit(array(
			'name'=>'submit',
			'id'=>'submit',
			'value'=>lang('receivings_delete_entire_receiving'),
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
	$('#date').datePicker({startDate: '<?php echo get_js_start_of_time_date(); ?>'});
	$("#receivings_delete_form").submit(function()
	{
		if (!confirm('<?php echo lang("sales_delete_confirmation"); ?>'))
		{
			return false;
		}
	});
	
	$("#receivings_undelete_form").submit(function()
	{
		if (!confirm('<?php echo lang("receivings_undelete_confirmation"); ?>'))
		{
			return false;
		}
	});
	var submitting = false;
	$('#receivings_edit_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;
			submitting = true;
			$(form).mask("<?php echo lang('common_wait'); ?>");
			
			$(form).ajaxSubmit({
			success:function(response)
			{
				submitting = false;
				$(form).unmask();
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