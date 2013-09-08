<?php
$this->load->view("partial/header");
?>
<table id="title_bar">
	<tr>
		<td id="title_icon">
			<img src='<?php echo base_url()?>images/menubar/reports.png' alt='<?php echo lang('reports_reports'); ?> - <?php echo lang('reports_welcome_message'); ?>' />
		</td>
		<td id="title"><?php echo lang('reports_reports'); ?> - <?php echo $title ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><small><?php echo $subtitle ?></small></td>
	</tr>
</table>
<br />

<div style="text-align: center;">
<!--[if lte IE 8]><script src="<?php echo base_url();?>js/excanvas.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script><![endif]-->
<script src="<?php echo base_url();?>js/jquery.flot.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="<?php echo base_url();?>js/jquery.flot.pie.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>

<script type="text/javascript">
$.getScript('<?php echo $graph_file; ?>');
</script>
</div>
<div id="chart_wrapper">
	<div id="chart"></div>
</div>
<div id="report_summary">
<?php foreach($summary_data as $name=>$value) { ?>
	<div class="summary_row"><?php echo lang('reports_'.$name). ': '.to_currency($value); ?></div>
<?php }?>
</div>
<?php
$this->load->view("partial/footer"); 
?>