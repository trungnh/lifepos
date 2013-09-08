<?php
echo form_open_multipart('items/do_excel_import/',array('id'=>'item_form'));
?>
<div id="required_fields_message"><?php echo lang('items_mass_import_from_excel'); ?></div>
<ul id="error_message_box"></ul>
<b><a href="<?php echo site_url('items/excel'); ?>"><?php echo lang('items_download_import_template'); ?></a></b>
<fieldset id="item_basic_info">
<legend><?php echo lang('items_import'); ?></legend>

<div class="field_row clearfix">	
<?php echo form_label(lang('common_file_path').':', 'name',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_upload(array(
		'name'=>'file_path',
		'id'=>'file_path',
		'value'=>'')
	);?>
	</div>
</div>

<?php
echo form_submit(array(
	'name'=>'submitf',
	'id'=>'submitf',
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
	
	$('#item_form').validate({
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
				post_item_form_submit(response);
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			file_path:"required"
   		},
		messages: 
		{
   			file_path:"<?php echo lang('items_full_path_to_excel_file_required'); ?>"
		}
	});
});
</script>