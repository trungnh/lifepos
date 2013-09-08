<?php
if($export_excel == 1)
{
	$rows = array();
	$row = array();
	foreach ($headers as $header) 
	{
		$row[] = strip_tags($header['data']);
	}
	
	$rows[] = $row;
	
	foreach($data as $datarow)
	{
		$row = array();
		foreach($datarow as $cell)
		{
			$row[] = strip_tags($cell['data']);
		}
		$rows[] = $row;
	}
	
	$content = array_to_csv($rows);
	
	force_download(strip_tags($title) . '.csv', $content);
	exit;
}
?>
<?php $this->load->view("partial/header"); ?>
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
<table id="contents">
	<tr>
		<td id="item_table">
			<div id="table_holder" style="width: 960px;">
				<table class="tablesorter report" id="sortable_table">
					<thead>
						<tr>
							<?php foreach ($headers as $header) { ?>
							<th align="<?php echo $header['align'];?>"><?php echo $header['data']; ?></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data as $row) { ?>
						<tr>
							<?php foreach ($row as $cell) { ?>
							<td align="<?php echo $cell['align'];?>"><?php echo $cell['data']; ?></td>
							<?php } ?>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>	
			<div id="report_summary" class="tablesorter report" style="margin-right: 10px;">
			<?php foreach($summary_data as $name=>$value) { ?>
				<div class="summary_row"><?php echo "<strong>".lang('reports_'.$name). '</strong>: '.to_currency($value); ?></div>
			<?php }?>
			</div>
		</td>
	</tr>
</table>
<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
function init_table_sorting()
{
	//Only init if there is more than one row
	if($('.tablesorter tbody tr').length >1)
	{
		$("#sortable_table").tablesorter(); 
	}
}
$(document).ready(function()
{
	init_table_sorting();
});
</script>