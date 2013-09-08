<?php $this->load->view('partial/header.php'); ?>
<style type="text/css">
	.error { float: none !important; }
</style>
<div id="register_container" class="sales">
<?php
echo form_open('sales/closeregister' . $continue, array('id'=>'closing_amount_form'));
?>
<fieldset id="item_basic_info">
<legend><?php echo lang("sales_closing_amount_desc"); ?></legend>
<div><?php echo sprintf(lang('sales_closing_amount_approx'), $closeout); ?></div>
<div class="field_row clearfix">
<?php echo form_label(lang('sales_closing_amount').':', 'closing_amount',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'closing_amount',
		'id'=>'closing_amount',
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

	$('#closing_amount_form').validate({
		rules:
		{
			closing_amount: {
				required: true,
				number: true
			}
   		},
		messages:
		{
			closing_amount: {
				required: "<?php echo $lang('sales_amount_required'); ?>",
				number: "<?php echo $lang('sales_amount_number'); ?>"
			}
		}
	});
});
</script>