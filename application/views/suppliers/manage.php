<div id="menu-top">
    <table id="title_bar">
            <tr>
                    <td id="title_icon">
                            <img src='<?php echo base_url()?>images/menubar/<?php echo $controller_name; ?>.png' alt='title icon' />
                    </td>
                    <td id="title">
                            <?php echo lang('common_list_of').' '.lang('module_'.$controller_name); ?>
                    </td>
                    <td id="title_search">
                            <?php echo form_open("$controller_name/search",array('id'=>'search_form')); ?>
                        <input type="text" name ='search' id='search' class="search"/>
                            <img src='<?php echo base_url()?>images/spinner_small.gif' alt='spinner' id='spinner' />
                            </form>
                    </td>
            </tr>
    </table>
</div>
<div class="main-pos">

<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function() 
{ 
    init_table_sorting();
    enable_select_all();
    enable_checkboxes();
    enable_row_selection();
    enable_search('<?php echo site_url("$controller_name/suggest")?>','<?php echo lang("common_confirm_search")?>');
    enable_email('<?php echo site_url("$controller_name/mailto")?>');
    enable_delete('<?php echo lang($controller_name."_confirm_delete")?>','<?php echo lang($controller_name."_none_selected")?>');
}); 

function init_table_sorting()
{
	//Only init if there is more than one row
	if($('.tablesorter tbody tr').length >1)
	{
		$("#sortable_table").tablesorter(
		{ 
			sortList: [[1,0]], 
			headers: 
			{ 
				0: { sorter: false}, 
				6: { sorter: false} 
			} 

		}); 
	}
}

function post_person_form_submit(response)
{
	if(!response.success)
	{
		set_feedback(response.message,'error_message',true);	
	}
	else
	{
		//This is an update, just update one row
		if(jQuery.inArray(response.person_id,get_visible_checkbox_ids()) != -1)
		{
			update_row(response.person_id,'<?php echo site_url("$controller_name/get_row")?>');
			set_feedback(response.message,'success_message',false);	
			
		}
		else //refresh entire table
		{
			do_search(true,function()
			{
				//highlight new row
				highlight_row(response.person_id);
				set_feedback(response.message,'success_message',false);		
			});
		}
	}
}
</script>
<div class="table-content-menu">
    <div id="menu-table-data">
        <div class="menu-table-data" id="create-new-supplier">
            <?php echo anchor("$controller_name/view/-1/width~$form_width",
            lang($controller_name.'_new'),
            array('class'=>'thickbox none new', 'title'=>lang($controller_name.'_new')));
            ?>
        </div>
        
        <div class="menu-table-data">
            <?php echo anchor("$controller_name/excel_export",
            lang('common_excel_export'),
            array('class'=>'none import'));
            ?>
        </div>
        <div class="menu-table-data" id="send-mail">
            <a class="email email_inactive" href="<?php echo current_url(). '#'; ?>" id="email"><?php echo lang("common_email");?></a>
        </div>
        <div class="menu-table-data" id="delete-items">
            <?php echo anchor("$controller_name/delete",lang("common_delete"),array('id'=>'delete', 'class'=>'delete_inactive')); ?>
        </div>        
    </div>
    <div class="pagination-head">
        <div class="info-page">
            <!-- tổng số trang-->
        </div>
        <div class="site-previous-next">
            <div class="previous">
                <img class="image-previous" src="images/cleardot.gif">
                </img>
            </div>
            <div class="next">
                <img class="image-next" src="images/cleardot.gif">
                </img>
            </div>
        </div>
    </div>
</div>



<table id="contents">
	<tr>				
		<td id="item_table">
			<div id="table_holder">
			<?php echo $manage_table; ?>
			</div>
			<div id="pagination">
				<?php echo $this->pagination->create_links();?>
			</div>
		</td>
	</tr>
</table>
<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>