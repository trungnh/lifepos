<div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<?php
echo form_open('item_kits/save/'.$item_kit_info->item_kit_id,array('id'=>'item_kit_form'));
?>
<fieldset id="item_kit_info">
<legend><?php echo lang("item_kits_info"); ?></legend>

<div class="field_row clearfix">
<?php echo form_label(lang('item_kits_add_item').':', 'item',array('class'=>'wide')); ?>
	<div class='form_field'>
		<?php echo form_input(array(
			'name'=>'item',
			'id'=>'item'
		));?>
	</div>
</div>

<table id="item_kit_items">
	<tr>
		<th><?php echo lang('common_delete');?></th>
		<th><?php echo lang('item_kits_item');?></th>
		<th><?php echo lang('item_kits_quantity');?></th>
	</tr>
	
	<?php foreach ($this->Item_kit_items->get_info($item_kit_info->item_kit_id) as $item_kit_item) {?>
		<tr>
			<?php
			$item_info = $this->Item->get_info($item_kit_item->item_id);
			?>
			<td><a href="#" onclick='return deleteItemKitRow(this);'>X</a></td>
			<td><?php echo $item_info->name; ?></td>
			<td><input class='quantity' onchange="calculateSuggestedPrices();" id='item_kit_item_<?php echo $item_kit_item->item_id ?>' type='text' size='3' name=item_kit_item[<?php echo $item_kit_item->item_id ?>] value='<?php echo $item_kit_item->quantity ?>'/></td>
		</tr>
	<?php } ?>
</table>

<div class="field_row clearfix">
<?php echo form_label(lang('items_item_number').':', 'name',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'item_kit_number',
		'id'=>'item_kit_number',
		'value'=>$item_kit_info->item_kit_number)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('item_kits_name').':', 'name',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'name',
		'id'=>'name',
		'value'=>$item_kit_info->name)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_category').':', 'category',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'category',
		'id'=>'category',
		'value'=>$item_kit_info->category)
	);?>
	</div>
</div>


<div class="field_row clearfix">
<?php echo form_label(lang('items_cost_price').':', 'cost_price',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'cost_price',
		'id'=>'cost_price',
		'value'=>$item_kit_info->cost_price)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_unit_price').':', 'unit_price',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'unit_price',
		'id'=>'unit_price',
		'value'=>$item_kit_info->unit_price)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_tax_1').':', 'tax_percent_1',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'tax_names[]',
		'id'=>'tax_name_1',
		'size'=>'8',
		'value'=> isset($item_kit_tax_info[0]['name']) ? $item_kit_tax_info[0]['name'] : $this->config->item('default_tax_1_name'))
	);?>
	<?php echo form_input(array(
		'name'=>'tax_percents[]',
		'id'=>'tax_percent_name_1',
		'size'=>'3',
		'value'=> isset($item_kit_tax_info[0]['percent']) ? $item_kit_tax_info[0]['percent'] : $default_tax_1_rate)
	);?>
	%
	<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_tax_2').':', 'tax_percent_2',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'tax_names[]',
		'id'=>'tax_name_2',
		'size'=>'8',
		'value'=> isset($item_kit_tax_info[1]['name']) ? $item_kit_tax_info[1]['name'] : $this->config->item('default_tax_2_name'))
	);?>
	<?php echo form_input(array(
		'name'=>'tax_percents[]',
		'id'=>'tax_percent_name_2',
		'size'=>'3',
		'value'=> isset($item_kit_tax_info[1]['percent']) ? $item_kit_tax_info[1]['percent'] : $default_tax_2_rate)
	);?>
	%
	<?php echo form_checkbox('tax_cumulatives[]', '1', isset($item_kit_tax_info[1]['cumulative']) && $item_kit_tax_info[1]['cumulative'] ? (boolean)$item_kit_tax_info[1]['cumulative'] : (boolean)$default_tax_2_cumulative); ?>
	<?php echo lang('common_cumulative'); ?>
	</div>
</div>


<div class="field_row clearfix">
<?php echo form_label(lang('item_kits_description').':', 'description',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>'description',
		'id'=>'description',
		'value'=>$item_kit_info->description,
		'rows'=>'5',
		'cols'=>'17')
	);?>
	</div>
</div>


<?php
echo form_submit(array(
	'name'=>'submit',
	'id'=>'submit',
	'value'=>lang('common_submit'),
	'class'=>'submit_button float_right')
);
?>
</fieldset>
<?php
echo form_close();
?>
<script type='text/javascript'>

$( "#item" ).autocomplete({
	source: '<?php echo site_url("items/item_search"); ?>',
	delay: 10,
	autoFocus: false,
	minLength: 0,
	select: function( event, ui ) 
	{	
		$( "#item" ).val("");
		if ($("#item_kit_item_"+ui.item.value).length ==1)
		{
			$("#item_kit_item_"+ui.item.value).val(parseFloat($("#item_kit_item_"+ui.item.value).val()) + 1);
		}
		else
		{
			$("#item_kit_items").append("<tr><td><a href='#' onclick='return deleteItemKitRow(this);'>X</a></td><td>"+ui.item.label+"</td><td><input class='quantity' onchange='calculateSuggestedPrices();' id='item_kit_item_"+ui.item.value+"' type='text' size='3' name=item_kit_item["+ui.item.value+"] value='1'/></td></tr>");
		}
		
		calculateSuggestedPrices();
		
		return false;
	}
});

//validation and submit handling
$(document).ready(function()
{
	$( "#category" ).autocomplete({
		source: "<?php echo site_url('items/suggest_category');?>",
		delay: 10,
		autoFocus: false,
		minLength: 0
	});
	var submitting = false;
	$('#item_kit_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;
			submitting = true;
			$(form).mask("<?php echo lang('common_wait'); ?>");
			$(form).ajaxSubmit({
			success:function(response)
			{
				submitting = false;
				tb_remove();
				post_item_kit_form_submit(response);
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules:
		{
			name:"required",
			category:"required",
			unit_price: "number",
			cost_price: "number"
		},
		messages:
		{
			name:"<?php echo lang('items_name_required'); ?>",
			category:"<?php echo lang('items_category_required'); ?>",
			unit_price: "<?php echo lang('items_unit_price_number'); ?>",
			cost_price: "<?php echo lang('items_cost_price_number'); ?>"
		}
	});
});

function deleteItemKitRow(link)
{
	$(link).parent().parent().remove();
	calculateSuggestedPrices();
	return false;
}

function calculateSuggestedPrices()
{
	var items = [];
	$("#item_kit_items").find('input').each(function(index, element)
	{
		var quantity = parseFloat($(element).val());
		var item_id = $(element).attr('id').substring($(element).attr('id').lastIndexOf('_') + 1);
		
		items.push({
			item_id: item_id,
			quantity: quantity
		});
	});
	calculateSuggestedPrices.totalCostOfItems = 0;
	calculateSuggestedPrices.totalPriceOfItems = 0;
	getPrices(items, 0);
}

function getPrices(items, index)
{
	if (index > items.length -1)
	{
		$("#unit_price").val(calculateSuggestedPrices.totalPriceOfItems);
		$("#cost_price").val(calculateSuggestedPrices.totalCostOfItems);
	}
	else
	{
		$.get('<?php echo site_url("items/get_info");?>'+'/'+items[index]['item_id'], {}, function(item_info)
		{
			calculateSuggestedPrices.totalPriceOfItems+=items[index]['quantity'] * parseFloat(item_info.unit_price);
			calculateSuggestedPrices.totalCostOfItems+=items[index]['quantity'] * parseFloat(item_info.cost_price);
			getPrices(items, index+1);
		}, 'json');
	}
}
</script>