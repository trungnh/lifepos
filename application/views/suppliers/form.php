<?php
echo form_open('suppliers/save/'.$person_info->person_id,array('id'=>'supplier_form'));
?>
<div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<fieldset id="supplier_basic_info">
<legend><?php echo lang("suppliers_basic_information"); ?></legend>

<div class="field_row clearfix">	
<?php echo form_label(lang('suppliers_company_name').':', 'company_name', array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'company_name',
		'id'=>'company_name_input',
		'value'=>$person_info->company_name)
	);?>
	</div>
</div>

<?php $this->load->view("people/form_basic_info"); ?>
<div class="field_row clearfix">	
<?php echo form_label(lang('suppliers_account_number').':', 'account_number'); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'account_number',
		'id'=>'account_number',
		'value'=>$person_info->account_number)
	);?>
	</div>
</div>
<?php
echo form_submit(array(
	'name'=>'submit',
	'id'=>'submit',
	'value'=>lang('common_submit'),
	'class'=>'submit_button float_right')
);
?>
</fieldset>
<?php 
echo form_close();
?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	var submitting = false;
	
	$('#supplier_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;
			submitting = true;
			$(form).mask("<?php echo lang('common_wait'); ?>");
			$(form).ajaxSubmit({
			success:function(response)
			{
				submitting = false;
				tb_remove();
				post_person_form_submit(response);
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			company_name: "required",
			first_name: "required",
			last_name: "required",
    		email: "email"
   		},
		messages: 
		{
     		company_name: "<?php echo lang('suppliers_company_name_required'); ?>",
     		last_name: "<?php echo lang('common_last_name_required'); ?>",
     		email: "<?php echo lang('common_email_invalid_format'); ?>"
		}
	});
});
</script>