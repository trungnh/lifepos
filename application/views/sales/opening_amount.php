<?php $this->load->view('partial/header.php'); ?>
<style type="text/css">
	.error { float: none !important; }
</style>
<div id="register_container" class="sales">
<?php
echo form_open('sales', array('id'=>'opening_amount_form'));
?>
<fieldset id="item_basic_info">
<legend><?php echo lang("sales_opening_amount_desc"); ?></legend>

<div class="field_row clearfix">
<?php echo form_label(lang('sales_opening_amount').':', 'opening_amount',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'opening_amount',
		'id'=>'opening_amount',
		'value'=>'')
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
</div>
<?php $this->load->view('partial/footer.php'); ?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	var submitting = false;

	$('#opening_amount_form').validate({
		rules:
		{
			opening_amount: {
				required: true,
				number: true
			}
   		},
   		messages: {
	   		closing_amount: {
				required: "<?php echo $lang('sales_amount_required'); ?>",
				number: "<?php echo $lang('sales_amount_number'); ?>"
			}
   		}
	});
});
</script>