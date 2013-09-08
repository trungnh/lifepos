<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<base href="<?php echo base_url();?>" />
	<title><?php //echo $this->config->item('company').' -- '.lang('common_powered_by').' PHP Point Of Sale' ?></title>
<!--	<link rel="icon" href="<?php //echo base_url();?>favicon.ico" type="image/x-icon"/>-->
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/phppos.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/menubar.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/general.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/popupbox.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/register.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/receipt.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/reports.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/tables.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/thickbox.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/datepicker.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/editsale.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/footer.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/css3.css?<?php echo APPLICATION_VERSION; ?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/ui-lightness/jquery-ui-1.8.14.custom.css?<?php echo APPLICATION_VERSION; ?>" />	
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/phppos_print.css?<?php echo APPLICATION_VERSION; ?>"  media="print"/>
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/jquery.loadmask.css?<?php echo APPLICATION_VERSION; ?>" />
	
	
	
	<script type="text/javascript">
	var SITE_URL= "<?php echo site_url(); ?>";
	</script>
	<script src="<?php echo base_url();?>js/jquery-1.3.2.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery-ui-1.8.14.custom.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.color.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.form.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.tablesorter.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.validate.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/thickbox.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/common.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/manage_tables.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/date.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/datepicker.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.loadmask.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script type="text/javascript">
	Date.format = '<?php echo get_js_date_format(); ?>';
	</script>
<style type="text/css">
html {
    overflow: auto;
}
</style>

</head>
<body>    
<div id="menubar">
		<ul>			
			<?php
			foreach($allowed_modules->result() as $module)
			{
			?>
			<li class="menu_item menu_item_<?php echo $module->module_id;?>">
				<a href="<?php echo site_url("$module->module_id");?>">
                                    <img src="<?php echo base_url().'images/menubar/'.$module->module_id.'.png';?>" border="0" alt="Menubar Image" />
                                    <span class="menu-item-module"><?php echo lang("module_".$module->module_id) ?></span>
                                </a>                            
                        </li>
			<?php
			}
			?>
                </ul>

</div>
    <div id="menu-right">
        <ul>
            <?php
            foreach($allowed_module->result() as $modun){
            ?>
                        <li class="menu_item">
				<a href="<?php echo site_url("$modun->module_id");?>">
                                    <img src="<?php echo base_url().'images/menubar/'.$modun->module_id.'.png';?>" border="0" alt="Menubar Image" />
                                    <span class="menu-item-module"><?php echo lang("module_".$modun->module_id) ?></span>
                                </a>                            
                        </li>
            <?php }?>
        </ul>
    </div>
<div id="content_area_wrapper">
<!--<div id="content_area">-->
