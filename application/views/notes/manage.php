<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
    $(document).ready(function()
    {
        init_table_sorting();
        enable_select_all();
        enable_checkboxes();
        enable_row_selection();
        enable_search('<?php echo site_url("$controller_name/suggest")?>','<?php echo lang("common_confirm_search")?>');
        enable_delete('<?php echo lang($controller_name."_confirm_delete")?>','<?php echo lang($controller_name."_none_selected")?>');
        enable_bulk_edit('<?php echo lang($controller_name."_none_selected")?>');
        enable_cleanup('<?php echo lang("items_confirm_cleanup")?>');

        $('#generate_barcodes').click(function()
        {
            var selected = get_selected_values();
            if (selected.length == 0)
            {
                alert('<?php echo lang('items_must_select_item_for_barcode'); ?>');
                return false;
            }

            $(this).attr('href','<?php echo site_url("items/generate_barcodes");?>/'+selected.join('~'));
        });

        $('#generate_barcode_labels').click(function()
        {
            var selected = get_selected_values();
            if (selected.length == 0)
            {
                alert('<?php echo lang('items_must_select_item_for_barcode'); ?>');
                return false;
            }

            $(this).attr('href','<?php echo site_url("items/generate_barcode_labels");?>/'+selected.join('~'));
        });
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
                    8: { sorter: false},
                    9: { sorter: false}
                }

            });
        }
    }

    function post_item_form_submit(response)
    {
        if(!response.success)
        {
            set_feedback(response.message,'error_message',true);
        }
        else
        {
            //This is an update, just update one row
            if(jQuery.inArray(response.item_id,get_visible_checkbox_ids()) != -1)
            {
                update_row(response.item_id,'<?php echo site_url("$controller_name/get_row")?>');
                set_feedback(response.message,'success_message',false);

            }
            else //refresh entire table
            {
                do_search(true,function()
                {
                    //highlight new row
                    highlight_row(response.item_id);
                    set_feedback(response.message,'success_message',false);
                });
            }
        }
    }

    function post_bulk_form_submit(response)
    {
        if(!response.success)
        {
            set_feedback(response.message,'error_message',true);
        }
        else
        {
            set_feedback(response.message,'success_message',false);
            setTimeout(function(){window.location.reload();}, 2500);
        }
    }
</script>


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
            <input type="text" name ='search' id='search'/>
            <img src='<?php echo base_url()?>images/spinner_small.gif' alt='spinner' id='spinner' />
            </form>
        </td>
    </tr>
</table>
<table id="contents">
    <tr>
        <td id="commands">
            <div id="new_button">
                <?php echo
                anchor("$controller_name/view/-1/width~$form_width",
                lang($controller_name.'_new'),
                array('class'=>'thickbox none new',
                'title'=>lang($controller_name.'_new')));
                ?>

                <?php echo
                anchor("$controller_name/bulk_edit/width~$form_width",
                lang("items_bulk_edit"),
                array('id'=>'bulk_edit',
                'class' => 'bulk_edit_inactive',
                'title'=>lang('items_edit_multiple_items')));
                ?>
                <?php echo
                anchor("$controller_name/generate_barcode_labels",
                lang("common_barcode_labels"),
                array('id'=>'generate_barcode_labels',
                'class' => 'generate_barcodes_inactive',
                'target' =>'_blank',
                'title'=>lang('common_barcode_labels')));
                ?>
                <?php echo
                anchor("$controller_name/generate_barcodes",
                lang("common_barcode_sheet"),
                array('id'=>'generate_barcodes',
                'class' => 'generate_barcodes_inactive',
                'target' =>'_blank',
                'title'=>lang('common_barcode_sheet')));
                ?>

                <?php echo anchor("$controller_name/excel_import/width~$form_width",
                lang('common_excel_import'),
                array('class'=>'thickbox none import',
                'title'=>lang('items_import_items_from_excel')));
                ?>

                <?php echo anchor("$controller_name/excel_export",
                lang('common_excel_export'),
                array('class'=>'none import'));
                ?>

                <?php echo
                anchor("$controller_name/delete",
                lang("common_delete"),
                array('id'=>'delete',
                'class'=>'delete_inactive'));
                ?>
                <?php echo
                anchor("$controller_name/cleanup",
                lang("items_cleanup_old_items"),
                array('id'=>'cleanup',
                'class'=>'cleanup'));
                ?>
            </div>
        </td>
        <td style="width:10px;"></td>
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