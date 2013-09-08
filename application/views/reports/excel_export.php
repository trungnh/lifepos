<?php $this->load->view("partial/header"); ?>
<div id="page_title" style="margin-bottom:8px;"><?php echo lang('reports_report_input'); ?></div>
<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}
?>
	<div>
		<?php echo lang('reports_export_to_excel'); ?>: <input type="radio" name="export_excel" id="export_excel_yes" value='1' /> <?php echo lang('common_yes'); ?>
		<input type="radio" name="export_excel" id="export_excel_no" value='0' checked='checked' /> <?php echo lang('common_no'); ?>
	</div>

<?php
echo form_button(array(
	'name'=>'generate_report',
	'id'=>'generate_report',
	'content'=>lang('common_submit'),
	'class'=>'submit_button')
);
?>

<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$("#generate_report").click(function()
	{
		var export_excel = 0;
		if ($("#export_excel_yes").attr('checked'))
		{
			export_excel = 1;
		}
		
		window.location = window.location+'/' + export_excel;
	});	
});
</script>