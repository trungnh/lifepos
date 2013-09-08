<?php
echo form_open('customers/save/'.$person_info->person_id,array('id'=>'customer_form'));
?>
<div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<fieldset id="customer_basic_info">
<legend><?php echo lang("customers_basic_information"); ?></legend>
<?php $this->load->view("people/form_basic_info"); ?>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_company').':', 'company_name'); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'company_name',
		'id'=>'customer_company_name',
		'value'=>$person_info->company_name)
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('customers_account_number').':', 'account_number'); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'account_number',
		'id'=>'account_number',
		'value'=>$person_info->account_number)
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('customers_taxable').':', 'taxable'); ?>
	<div class='form_field'>
	<?php echo form_checkbox('taxable', '1', $person_info->taxable == '' ? TRUE : (boolean)$person_info->taxable);?>
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
	$('#customer_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;
			submitting = true;
			$(form).mask("<?php echo lang('common_wait'); ?>");
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_person_form_submit(response);
				submitting = false;
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			first_name: "required",
			last_name: "required",
    		email: "email"
   		},
		messages: 
		{
     		first_name: "<?php echo lang('common_first_name_required'); ?>",
     		last_name: "<?php echo lang('common_last_name_required'); ?>",
     		email: "<?php echo lang('common_email_invalid_format'); ?>"
		}
	});
});
</script>