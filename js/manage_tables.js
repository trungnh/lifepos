function checkbox_click(event)
{
	event.stopPropagation();
	do_email(enable_email.url);
	if($(event.target).attr('checked'))
	{
		$(event.target).parent().parent().find("td").addClass('selected').css("backgroundColor","");		
	}
	else
	{
		$(event.target).parent().parent().find("td").removeClass('selected');		
	}
	
	determine_checkbox_status();
}

function enable_search(suggest_url,confirm_search_message)
{
	//Keep track of enable_email has been called
	if(!enable_search.enabled)
		enable_search.enabled=true;

        $('#search').click(function()
        {
            $(this).attr('value','');
        });


        $( "#search" ).autocomplete({
            source: suggest_url,
            delay: 10,
            autoFocus: false,
            minLength: 0,
            select: function( event, ui )
            {
                $( "#search" ).val(ui.item.label);
                do_search(true);
            }
        });

        $('#search_form').submit(function(event)
        {
            event.preventDefault();

            if(get_selected_values().length >0)
            {
                if(!confirm(confirm_search_message))
                    return;
            }
            do_search(true);
        });
}
enable_search.enabled=false;

function do_search(show_feedback,on_complete)
{	
	//If search is not enabled, don't do anything
	if(!enable_search.enabled)
		return;
		
	if(show_feedback)
		$('#spinner').show();
		
	$('#sortable_table tbody').load($('#search_form').attr('action'),{'search':$('#search').val()},function()
	{
		if(typeof on_complete=='function')
			on_complete();
				
		$('#spinner').hide();
		//re-init elements in new table, as table tbody children were replaced
		tb_init('#sortable_table a.thickbox');
		update_sortable_table();	
		enable_row_selection();		
		$('#sortable_table tbody :checkbox').click(checkbox_click);
		$("#select_all").attr('checked',false);
	});
}

function enable_email(email_url)
{
	//Keep track of enable_email has been called
	if(!enable_email.enabled)
		enable_email.enabled=true;

	//store url in function cache
	if(!enable_email.url)
	{
		enable_email.url=email_url;
	}
	
	$('#select_all, #sortable_table tbody :checkbox').click(checkbox_click);
}
enable_email.enabled=false;
enable_email.url=false;

function do_email(url)
{
	//If email is not enabled, don't do anything
	if(!enable_email.enabled)
		return;

	$.post(url, { 'ids[]': get_selected_values() },function(response)
	{
		$('#email').attr('href',response);
	});

}

function enable_checkboxes()
{
	$('#select_all, #sortable_table tbody :checkbox').click(checkbox_click);
}

function enable_delete(confirm_message,none_selected_message)
{
	//Keep track of enable_delete has been called
	if(!enable_delete.enabled)
		enable_delete.enabled=true;
	
	$('#delete-customers').click(function(event)
	{
		event.preventDefault();
		if($("#sortable_table tbody :checkbox:checked").length >0)
		{
			if(confirm(confirm_message))
			{
				do_delete($("#delete").attr('href'));
			}
		}
		else
		{
			alert(none_selected_message);
		}
	});
}
enable_delete.enabled=false;

function do_delete(url)
{
	//If delete is not enabled, don't do anything
	if(!enable_delete.enabled)
		return;
	
	var row_ids = get_selected_values();
	var selected_rows = get_selected_rows();
	$.post(url, { 'ids[]': row_ids },function(response)
	{
		//delete was successful, remove checkbox rows
		if(response.success)
		{
			set_feedback(response.message,'success_message',false);	

			$(selected_rows).each(function(index, dom)
			{
				$(this).find("td").animate({backgroundColor:"#FF0000"},1200,"linear")
				.end().animate({opacity:0},1200,"linear",function()
				{
					$(this).remove();
					//Re-init sortable table as we removed a row
					update_sortable_table();
					
				});
			});	
		}
		else
		{
			set_feedback(response.message,'error_message',true);	
		}
		

	},"json");
}

function enable_bulk_edit(none_selected_message)
{
	//Keep track of enable_bulk_edit has been called
	if(!enable_bulk_edit.enabled)
		enable_bulk_edit.enabled=true;
	
	$('#bulk_edit').click(function(event)
	{
		event.preventDefault();
		if($("#sortable_table tbody :checkbox:checked").length >0)
		{
			tb_show($(this).attr('title'),$(this).attr('href'),false);
			$(this).blur();
		}
		else
		{
			alert(none_selected_message);
		}
	});
}
enable_bulk_edit.enabled=false;

function enable_select_all()
{
	//Keep track of enable_select_all has been called
	if(!enable_select_all.enabled)
		enable_select_all.enabled=true;

	$('#select_all').click(function()
	{
		if($(this).attr('checked'))
		{	
			$("#sortable_table tbody :checkbox").each(function()
			{
				$(this).attr('checked',true);
				$(this).parent().parent().find("td").addClass('selected').css("backgroundColor","");

			});
		}
		else
		{
			$("#sortable_table tbody :checkbox").each(function()
			{
				$(this).attr('checked',false);
				$(this).parent().parent().find("td").removeClass('selected');				
			});    	
		}
	 });	
}
enable_select_all.enabled=false;

function enable_row_selection(rows)
{
	//Keep track of enable_row_selection has been called
	if(!enable_row_selection.enabled)
		enable_row_selection.enabled=true;
	
	if(typeof rows =="undefined")
		rows=$("#sortable_table tbody tr");
	
	rows.hover(
		function row_over()
		{
			$(this).find("td").addClass('over').css("backgroundColor","");
			$(this).css("cursor","pointer");
		},
		
		function row_out()
		{
			if(!$(this).find("td").hasClass("selected"))
			{
				$(this).find("td").removeClass('over');
			}
		}
	);
	
	rows.click(function row_click(event)
	{
		var checkbox = $(this).find(":checkbox");
		checkbox.attr('checked',!checkbox.attr('checked'));
		do_email(enable_email.url);
		
		if(checkbox.attr('checked'))
		{
			$(this).find("td").addClass('selected').css("backgroundColor","");
		}
		else
		{
			$(this).find("td").removeClass('selected');
		}
		
		determine_checkbox_status();
	});
}
enable_row_selection.enabled=false;

function update_sortable_table()
{
	//let tablesorter know we changed <tbody> and then triger a resort
	$("#sortable_table").trigger("update");
	
	if(typeof $("#sortable_table")[0].config!="undefined")
	{
		var sorting = $("#sortable_table")[0].config.sortList; 		
		$("#sortable_table").trigger("sorton",[sorting]);
	}
}

function update_row(row_id,url)
{
	$.post(url, { 'row_id': row_id },function(response)
	{
		//Replace previous row
		var row_to_update = $("#sortable_table tbody tr :checkbox[value="+row_id+"]").parent().parent();
		row_to_update.replaceWith(response);	
		reinit_row(row_id);
		highlight_row(row_id);
	});
}

function reinit_row(checkbox_id)
{
	var new_checkbox = $("#sortable_table tbody tr :checkbox[value="+checkbox_id+"]");
	var new_row = new_checkbox.parent().parent();
	enable_row_selection(new_row);
	//Re-init some stuff as we replaced row
	update_sortable_table();
	tb_init(new_row.find("a.thickbox"));
	//re-enable e-mail
	new_checkbox.click(checkbox_click);	
}

function highlight_row(checkbox_id)
{
	var new_checkbox = $("#sortable_table tbody tr :checkbox[value="+checkbox_id+"]");
	var new_row = new_checkbox.parent().parent();

	new_row.find("td").animate({backgroundColor:"#e1ffdd"},"slow","linear")
		.animate({backgroundColor:"#e1ffdd"},5000)
		.animate({backgroundColor:"#e9e9e9"},"slow","linear");
}

function get_selected_values()
{
	var selected_values = new Array();
	$("#sortable_table tbody :checkbox:checked").each(function()
	{
		selected_values.push($(this).val());
	});
	return selected_values;
}

function get_selected_rows() 
{ 
	var selected_rows = new Array(); 
	$("#sortable_table tbody :checkbox:checked").each(function() 
	{ 
		selected_rows.push($(this).parent().parent()); 
	}); 
	return selected_rows; 
}

function get_visible_checkbox_ids()
{
	var row_ids = new Array();
	$("#sortable_table tbody :checkbox").each(function()
	{
		row_ids.push($(this).val());
	});
	return row_ids;
}

function determine_checkbox_status()
{
	if ($("#sortable_table tbody :checkbox:checked").length > 0)
	{
                /*hiển thị menu-table-data*/
                $("#items-bulk-edit").show();
                $("#common-barcode-labels").show();
                $("#common-barcode-sheet").show();
                $("#delete-items").show();
                $("#send-mail").show();
                $("#delete-customers").show();
                
		//$("#email").removeClass("email_inactive");
		$("#delete").removeClass("delete_inactive");
		$("#generate_barcodes").removeClass("generate_barcodes_inactive");
		$("#generate_barcode_labels").removeClass("generate_barcodes_inactive");
		$("#bulk_edit").removeClass("bulk_edit_inactive");
	}
	else
	{       /* ẩn menu-table-data*/
                $("#items-bulk-edit").hide();
                $("#common-barcode-labels").hide();
                $("#common-barcode-sheet").hide();
                $("#delete-items").hide();
                $("#send-mail").hide();
                $("#delete-customers").hide();
                
		//$("#email").addClass("email_inactive");
		$("#delete").addClass("delete_inactive");
		$("#generate_barcodes").addClass("generate_barcodes_inactive");
		$("#generate_barcode_labels").addClass("generate_barcodes_inactive");
		$("#bulk_edit").addClass("bulk_edit_inactive");
	}
}

function enable_cleanup(confirm_message)
{
	if(!enable_cleanup.enabled)
		enable_cleanup.enabled=true;
	
	$('#cleanup-old-customers').click(function(event)
	{
		do_cleanup(event, confirm_message);
	});
}	
enable_cleanup.enabled=false;

function do_cleanup(event, confirm_message)
{
	event.preventDefault();
	
	if(!enable_cleanup.enabled)
		return;

	if (confirm(confirm_message))
	{
		$.post($(event.target).attr('href'), {},function(response)
		{
			set_feedback(response.message,'success_message',false);
		}, 'json');	
	}
}