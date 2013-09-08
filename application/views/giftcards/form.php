<div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<?php
echo form_open('giftcards/save/'.$giftcard_info->giftcard_id,array('id'=>'giftcard_form'));
?>
<fieldset id="giftcard_basic_info">
<legend><?php echo lang("giftcards_basic_information"); ?></legend>

<div class="field_row clearfix">
<?php echo form_label(lang('giftcards_giftcard_number').':', 'name',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'giftcard_number',
		'size'=>'8',
		'id'=>'giftcard_number',
		'value'=>$giftcard_info->giftcard_number)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('giftcards_card_value').':', 'name',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'value',
		'size'=>'8',
		'id'=>'value',
		'value'=>$giftcard_info->value)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('giftcards_customer_name').':', 'customer_id',array('class'=>'wide')); ?>
	<div class='form_field'>
		<?php echo form_dropdown('customer_id', $customers, $giftcard_info->customer_id, 'id="customer_id"');?>
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
	$('#giftcard_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;
			submitting = true;
			$(form).mask("<?php echo lang('common_wait'); ?>");
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_giftcard_form_submit(response);
				submitting = false;
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules:
		{
			giftcard_number:
			{
				required:true
			},
			value:
			{
				required:true,
				number:true
			}
   		},
		messages:
		{
			giftcard_number:
			{
				required:"<?php echo lang('giftcards_number_required'); ?>",
				number:"<?php echo lang('giftcards_number'); ?>"
			},
			value:
			{
				required:"<?php echo lang('giftcards_value_required'); ?>",
				number:"<?php echo lang('giftcards_value'); ?>"
			}
		}
	});
});
</script>