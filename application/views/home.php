<div id="menu-top">
    
</div>
<div class="main-pos">
<?php $this->load->view("partial/header"); ?>
<div id="home_module_list">
	<?php
	foreach($allowed_modules->result() as $module)
	{
	?>
	<div class="module_item">
		<a href="<?php echo site_url("$module->module_id");?>">
			<img src="<?php echo base_url().'images/menubar/'.$module->module_id.'.png';?>" border="0" alt="Menubar Image" />
			<span><?php echo lang("module_".$module->module_id) ?></span>
		</a>
		- <span><?php echo lang('module_'.$module->module_id.'_desc');?></span>
	</div>
	<?php
	}
	?>
</div>    
<?php //$this->load->view("partial/footer"); ?>
</div>
