</div>
</div>
<div id="footer">
	<table id="footer_info">
		<tr>
			<td id="menubar_footer">
			<?php echo lang('common_welcome')." <b> $user_info->first_name $user_info->last_name! | </b>"; ?>
			<?php echo anchor("home/logout",lang("common_logout")); ?>
			</td>

			<td id="menubar_date_time" class="menu_date">
				<?php
				if($this->config->item('time_format') == '24_hour')
				{
					echo date('H:i');
				}
				else
				{
					echo date('h:i');
				}
				?>
			</td>
			<td id="menubar_date_day" class="menu_date mini_date">
				<?php echo date('D') ?>
				<br />
				<?php
				if($this->config->item('time_format') != '24_hour')
				{
					echo date('a');
				}
				?>
			</td>
			<td id="menubar_date_spacer" class="menu_date">
				|
			</td>
			<td id="menubar_date_date" class="menu_date">
				<?php echo date('d') ?>
			</td>
			<td id="menubar_date_monthyr" class="menu_date mini_date">
				<?php echo date('F') ?>
				<br />
				<?php echo date('Y') ?>
			</td>
		</tr>
	</table>

	<!--<div id="footer_spacer"></div>-->

	<!--<table id="footer">
		<tr>			
			<td id="footer_version">
				<?php //echo lang('common_you_are_using_phppos')?> <b><?php echo APPLICATION_VERSION; ?></b> -
                                <?php //echo lang('common_product_of')?> muoidv
			</td>
                        <!--<td>
                            <a href="http://lifetek.com.vn" style="margin-top:10px;">
                                    <img src="images/logosp/lifetek.png" alt="Công ty TNHH Công Nghệ Điện Tử-Phần Mềm-Viễn Thông LIFETEK" width="100"/>
                                </a>
                        </td>
		</tr>
	</table> -->
</div>
</body>
</html>