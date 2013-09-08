<?php $this->load->view("partial/header"); ?>
<table id="title_bar">
	<tr>
		<td id="title_icon">
			<img src='<?php echo base_url()?>images/menubar/<?php echo $controller_name; ?>.png' alt='title icon' />
		</td>
		<td id="title">
			<?php echo lang('module_'.$controller_name); ?>
		</td>
	</tr>
</table>
<?php
	echo form_open_multipart('config/save/',array('id'=>'config_form'));
?>
<div id="config_wrapper">
	<div id="dbBackup">
		<?php echo anchor('config/backup', lang('config_backup_database'), array('class' => 'dbBackup')); ?>
	</div>
	
	
<fieldset id="config_info">
	<legend><?php echo lang("config_info"); ?></legend>

	<div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>
	<ul id="error_message_box"></ul>

<div class="field_row clearfix">	
	<?php echo form_label(lang('config_company_logo').':', 'company_logo',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_upload(array(
		'name'=>'company_logo',
		'id'=>'company_logo',
		'value'=>$this->config->item('company_logo')));?>		
	</div>	
</div>

<div class="field_row clearfix">	
	<?php echo form_label(lang('config_delete_logo').':', 'delete_logo',array('class'=>'wide')); ?>
	<div class='form_field'>
		<?php echo form_checkbox('delete_logo', '1');?>
	</div>	
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_company').':', 'company',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'company',
		'id'=>'company',
		'value'=>$this->config->item('company')));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_address').':', 'address',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>'address',
		'id'=>'address',
		'rows'=>4,
		'cols'=>30,
		'value'=>$this->config->item('address')));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_phone').':', 'phone',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'phone',
		'id'=>'phone',
		'value'=>$this->config->item('phone')));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_default_tax_rate_1').':', 'default_tax_1_rate',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'default_tax_1_name',
		'id'=>'default_tax_1_name',
		'size'=>'10',
		'value'=>$this->config->item('default_tax_1_name')!==FALSE ? $this->config->item('default_tax_1_name') : lang('items_sales_tax_1')));?>
		
	<?php echo form_input(array(
		'name'=>'default_tax_1_rate',
		'id'=>'default_tax_1_rate',
		'size'=>'4',
		'value'=>$this->config->item('default_tax_1_rate')));?>%
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_default_tax_rate_2').':', 'default_tax_1_rate',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'default_tax_2_name',
		'id'=>'default_tax_2_name',
		'size'=>'10',
		'value'=>$this->config->item('default_tax_2_name')!==FALSE ? $this->config->item('default_tax_2_name') : lang('items_sales_tax_2')));?>
		
	<?php echo form_input(array(
		'name'=>'default_tax_2_rate',
		'id'=>'default_tax_2_rate',
		'size'=>'4',
		'value'=>$this->config->item('default_tax_2_rate')));?>%
		<?php echo form_checkbox('default_tax_2_cumulative', '1', $this->config->item('default_tax_2_cumulative') ? true : false);  ?>
	    <span class="cumulative_label">
		<?php echo lang('common_cumulative'); ?>
	    </span>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_currency_symbol').':', 'currency_symbol',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'currency_symbol',
		'id'=>'currency_symbol',
		'value'=>$this->config->item('currency_symbol')));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('common_email').':', 'email',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'email',
		'id'=>'email',
		'value'=>$this->config->item('email')));?>
	</div>
</div>


<div class="field_row clearfix">	
<?php echo form_label(lang('config_fax').':', 'fax',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'fax',
		'id'=>'fax',
		'value'=>$this->config->item('fax')));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_website').':', 'website',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'website',
		'id'=>'website',
		'value'=>$this->config->item('website')));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('common_return_policy').':', 'return_policy',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>'return_policy',
		'id'=>'return_policy',
		'rows'=>'4',
		'cols'=>'30',
		'value'=>$this->config->item('return_policy')));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_language').':', 'language',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('language', array(
		'vietnam'  => 'Việt Nam',
//                'english'  => 'English',
//		'indonesia'    => 'Indonesia',
//		'spanish'   => 'Spanish'
                ), $this->config->item('language'));
		?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_timezone').':', 'timezone',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('timezone', 
	 array(
		'Pacific/Midway'=>'(GMT-11:00) Midway Island, Samoa',
		'America/Adak'=>'(GMT-10:00) Hawaii-Aleutian',
		'Etc/GMT+10'=>'(GMT-10:00) Hawaii',
		'Pacific/Marquesas'=>'(GMT-09:30) Marquesas Islands',
		'Pacific/Gambier'=>'(GMT-09:00) Gambier Islands',
		'America/Anchorage'=>'(GMT-09:00) Alaska',
		'America/Ensenada'=>'(GMT-08:00) Tijuana, Baja California',
		'Etc/GMT+8'=>'(GMT-08:00) Pitcairn Islands',
		'America/Los_Angeles'=>'(GMT-08:00) Pacific Time (US & Canada)',
		'America/Denver'=>'(GMT-07:00) Mountain Time (US & Canada)',
		'America/Chihuahua'=>'(GMT-07:00) Chihuahua, La Paz, Mazatlan',
		'America/Dawson_Creek'=>'(GMT-07:00) Arizona',
		'America/Belize'=>'(GMT-06:00) Saskatchewan, Central America',
		'America/Cancun'=>'(GMT-06:00) Guadalajara, Mexico City, Monterrey',
		'Chile/EasterIsland'=>'(GMT-06:00) Easter Island',
		'America/Chicago'=>'(GMT-06:00) Central Time (US & Canada)',
		'America/New_York'=>'(GMT-05:00) Eastern Time (US & Canada)',
		'America/Havana'=>'(GMT-05:00) Cuba',
		'America/Bogota'=>'(GMT-05:00) Bogota, Lima, Quito, Rio Branco',
		'America/Caracas'=>'(GMT-04:30) Caracas',
		'America/Santiago'=>'(GMT-04:00) Santiago',
		'America/La_Paz'=>'(GMT-04:00) La Paz',
		'Atlantic/Stanley'=>'(GMT-04:00) Faukland Islands',
		'America/Campo_Grande'=>'(GMT-04:00) Brazil',
		'America/Goose_Bay'=>'(GMT-04:00) Atlantic Time (Goose Bay)',
		'America/Glace_Bay'=>'(GMT-04:00) Atlantic Time (Canada)',
		'America/St_Johns'=>'(GMT-03:30) Newfoundland',
		'America/Araguaina'=>'(GMT-03:00) UTC-3',
		'America/Montevideo'=>'(GMT-03:00) Montevideo',
		'America/Miquelon'=>'(GMT-03:00) Miquelon, St. Pierre',
		'America/Godthab'=>'(GMT-03:00) Greenland',
		'America/Argentina/Buenos_Aires'=>'(GMT-03:00) Buenos Aires',
		'America/Sao_Paulo'=>'(GMT-03:00) Brasilia',
		'America/Noronha'=>'(GMT-02:00) Mid-Atlantic',
		'Atlantic/Cape_Verde'=>'(GMT-01:00) Cape Verde Is.',
		'Atlantic/Azores'=>'(GMT-01:00) Azores',
		'Europe/Belfast'=>'(GMT) Greenwich Mean Time : Belfast',
		'Europe/Dublin'=>'(GMT) Greenwich Mean Time : Dublin',
		'Europe/Lisbon'=>'(GMT) Greenwich Mean Time : Lisbon',
		'Europe/London'=>'(GMT) Greenwich Mean Time : London',
		'Africa/Abidjan'=>'(GMT) Monrovia, Reykjavik',
		'Europe/Amsterdam'=>'(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna',
		'Europe/Belgrade'=>'(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague',
		'Europe/Brussels'=>'(GMT+01:00) Brussels, Copenhagen, Madrid, Paris',
		'Africa/Algiers'=>'(GMT+01:00) West Central Africa',
		'Africa/Windhoek'=>'(GMT+01:00) Windhoek',
		'Asia/Beirut'=>'(GMT+02:00) Beirut',
		'Africa/Cairo'=>'(GMT+02:00) Cairo',
		'Asia/Gaza'=>'(GMT+02:00) Gaza',
		'Africa/Blantyre'=>'(GMT+02:00) Harare, Pretoria',
		'Asia/Jerusalem'=>'(GMT+02:00) Jerusalem',
		'Europe/Minsk'=>'(GMT+02:00) Minsk',
		'Asia/Damascus'=>'(GMT+02:00) Syria',
		'Europe/Moscow'=>'(GMT+03:00) Moscow, St. Petersburg, Volgograd',
		'Africa/Addis_Ababa'=>'(GMT+03:00) Nairobi',
		'Asia/Tehran'=>'(GMT+03:30) Tehran',
		'Asia/Dubai'=>'(GMT+04:00) Abu Dhabi, Muscat',
		'Asia/Yerevan'=>'(GMT+04:00) Yerevan',
		'Asia/Kabul'=>'(GMT+04:30) Kabul',
		'Asia/Yekaterinburg'=>'(GMT+05:00) Ekaterinburg',
		'Asia/Tashkent'=>'(GMT+05:00) Tashkent',
		'Asia/Kolkata'=>'(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi',
		'Asia/Katmandu'=>'(GMT+05:45) Kathmandu',
		'Asia/Dhaka'=>'(GMT+06:00) Astana, Dhaka',
		'Asia/Novosibirsk'=>'(GMT+06:00) Novosibirsk',
		'Asia/Rangoon'=>'(GMT+06:30) Yangon (Rangoon)',
		'Asia/Bangkok'=>'(GMT+07:00) Bangkok, Hanoi, Jakarta',
		'Asia/Krasnoyarsk'=>'(GMT+07:00) Krasnoyarsk',
		'Asia/Hong_Kong'=>'(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi',
		'Asia/Irkutsk'=>'(GMT+08:00) Irkutsk, Ulaan Bataar',
		'Australia/Perth'=>'(GMT+08:00) Perth',
		'Australia/Eucla'=>'(GMT+08:45) Eucla',
		'Asia/Tokyo'=>'(GMT+09:00) Osaka, Sapporo, Tokyo',
		'Asia/Seoul'=>'(GMT+09:00) Seoul',
		'Asia/Yakutsk'=>'(GMT+09:00) Yakutsk',
		'Australia/Adelaide'=>'(GMT+09:30) Adelaide',
		'Australia/Darwin'=>'(GMT+09:30) Darwin',
		'Australia/Brisbane'=>'(GMT+10:00) Brisbane',
		'Australia/Hobart'=>'(GMT+10:00) Hobart',
		'Asia/Vladivostok'=>'(GMT+10:00) Vladivostok',
		'Australia/Lord_Howe'=>'(GMT+10:30) Lord Howe Island',
		'Etc/GMT-11'=>'(GMT+11:00) Solomon Is., New Caledonia',
		'Asia/Magadan'=>'(GMT+11:00) Magadan',
		'Pacific/Norfolk'=>'(GMT+11:30) Norfolk Island',
		'Asia/Anadyr'=>'(GMT+12:00) Anadyr, Kamchatka',
		'Pacific/Auckland'=>'(GMT+12:00) Auckland, Wellington',
		'Etc/GMT-12'=>'(GMT+12:00) Fiji, Kamchatka, Marshall Is.',
		'Pacific/Chatham'=>'(GMT+12:45) Chatham Islands',
		'Pacific/Tongatapu'=>'(GMT+13:00) Nuku\'alofa',
		'Pacific/Kiritimati'=>'(GMT+14:00) Kiritimati'
		), $this->config->item('timezone') ? $this->config->item('timezone') : date_default_timezone_get());
		?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_date_format').':', 'date_format',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('date_format', array(
		'middle_endian'    => '12/30/2000',
		'little_endian'  => '30-12-2000',
		'big_endian'   => '2000-12-30'), $this->config->item('date_format'));
		?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_time_format').':', 'time_format',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('time_format', array(
		'12_hour'    => '1:00 PM',
		'24_hour'  => '13:00'
		), $this->config->item('time_format'));
		?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_print_after_sale').':', 'print_after_sale',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_checkbox(array(
		'name'=>'print_after_sale',
		'id'=>'print_after_sale',
		'value'=>'print_after_sale',
		'checked'=>$this->config->item('print_after_sale')));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_mailchimp_api_key').':', 'mailchimp_api_key',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'mailchimp_api_key',
		'id'=>'mailchimp_api_key',
		'value'=>$this->config->item('mailchimp_api_key')));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_number_of_items_per_page').':', 'number_of_items_per_page',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('number_of_items_per_page', 
	 array(
		'20'=>'20',
		'50'=>'50',
		'100'=>'100',
		'200'=>'200',
		'500'=>'500'
		), $this->config->item('number_of_items_per_page') ? $this->config->item('number_of_items_per_page') : '20');
		?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('config_track_cash').':', 'track_cash',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_checkbox(array(
		'name'=>'track_cash',
		'id'=>'track_cash',
		'value'=>'1',
		'checked'=>$this->config->item('track_cash')));?>
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
</div>
<?php
echo form_close();
?>
<div id="feedback_bar" style="top: 1150px;"></div>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	var submitting = false;
	$('#config_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;
			submitting = true;
			$(form).ajaxSubmit({
			success:function(response)
			{
				if(response.success)
				{
					set_feedback(response.message,'success_message',false);		
				}
				else
				{
					set_feedback(response.message,'error_message',true);		
				}
				submitting = false;
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			company: "required",
			address: "required",
    		phone: "required",
    		email:"email",
    		return_policy: "required"    		
   		},
		messages: 
		{
     		company: "<?php echo lang('config_company_required'); ?>",
     		address: "<?php echo lang('config_address_required'); ?>",
     		phone: "<?php echo lang('config_phone_required'); ?>",
     		email: "<?php echo lang('common_email_invalid_format'); ?>",
     		return_policy:"<?php echo lang('config_return_policy_required'); ?>"
	
		}
	});
});
</script>
<?php $this->load->view("partial/footer"); ?>


