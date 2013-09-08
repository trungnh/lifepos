<div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<?php
echo form_open('items/save/'.$item_info->item_id,array('id'=>'item_form'));
?>
<fieldset id="item_basic_info">
    <legend><?php echo lang("items_basic_information"); ?></legend>
    <div class="field_row clearfix">
        <?php echo form_label(lang('items_item_number').':', 'name',array('class'=>'wide')); ?>
        <div class='form_field'>
            <?php echo form_input(array(
            'name'=>'item_number',
            'id'=>'item_number',
            'value'=>$item_info->item_number)
            );?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label(lang('items_name').':', 'name',array('class'=>'required wide')); ?>
        <div class='form_field'>
            <?php echo form_input(array(
            'name'=>'name',
            'id'=>'name',
            'value'=>$item_info->name)
            );?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label(lang('items_category').':', 'category',array('class'=>'required wide')); ?>
        <div class='form_field'>
            <?php echo form_input(array(
            'name'=>'category',
            'id'=>'category',
            'value'=>$item_info->category)
            );?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label(lang('items_supplier').':', 'supplier',array('class'=>'required wide')); ?>
        <div class='form_field'>
            <?php echo form_dropdown('supplier_id', $suppliers, $selected_supplier);?>
        </div>
    </div>


    <div class="field_row clearfix">
        <?php echo form_label(lang('items_manufacturers').':', 'manufacturers',array('class'=>'wide')); ?>
        <div class='form_field'>
            <?php echo form_input(array(
            'name'=>'manufacturers',
            'id'=>'manufacturers',
            'value'=>$item_info->manufacturers)
            );?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label(lang('items_cost_price').':', 'cost_price',array('class'=>'required wide')); ?>
        <div class='form_field'>
            <?php echo form_input(array(
            'name'=>'cost_price',
            'size'=>'8',
            'id'=>'cost_price',
            'value'=>$item_info->cost_price)
            );?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label(lang('items_unit_price').':', 'unit_price',array('class'=>'required wide')); ?>
        <div class='form_field'>
            <?php echo form_input(array(
            'name'=>'unit_price',
            'size'=>'8',
            'id'=>'unit_price',
            'value'=>$item_info->unit_price)
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
            'value'=> isset($item_tax_info[0]['name']) ? $item_tax_info[0]['name'] : $this->config->item('default_tax_1_name'))
            );?>
            <?php echo form_input(array(
            'name'=>'tax_percents[]',
            'id'=>'tax_percent_name_1',
            'size'=>'3',
            'value'=> isset($item_tax_info[0]['percent']) ? $item_tax_info[0]['percent'] : $default_tax_1_rate)
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
            'value'=> isset($item_tax_info[1]['name']) ? $item_tax_info[1]['name'] : $this->config->item('default_tax_2_name'))
            );?>
            <?php echo form_input(array(
            'name'=>'tax_percents[]',
            'id'=>'tax_percent_name_2',
            'size'=>'3',
            'value'=> isset($item_tax_info[1]['percent']) ? $item_tax_info[1]['percent'] : $default_tax_2_rate)
            );?>
	%
            <?php echo form_checkbox('tax_cumulatives[]', '1', isset($item_tax_info[1]['cumulative']) && $item_tax_info[1]['cumulative'] ? (boolean)$item_tax_info[1]['cumulative'] : (boolean)$default_tax_2_cumulative); ?>
            <span class="cumulative_label">
                <?php echo lang('common_cumulative'); ?>
            </span>
        </div>
    </div>


    <div class="field_row clearfix">
        <?php echo form_label(lang('items_quantity').':', 'quantity',array('class'=>'required wide')); ?>
        <div class='form_field'>
            <?php echo form_input(array(
            'name'=>'quantity',
            'id'=>'quantity',
            'value'=>$item_info->quantity)
            );?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label(lang('items_reorder_level').':', 'reorder_level',array('class'=>'required wide')); ?>
        <div class='form_field'>
            <?php echo form_input(array(
            'name'=>'reorder_level',
            'id'=>'reorder_level',
            'value'=>$item_info->reorder_level)
            );?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label(lang('items_manufacturer_date').':', 'expire_date',array('class'=>'wide')); ?>
        <div class='form_field'>
            <?php
            echo form_input(array(
            'name'=>'expire_date',
            'id'=>'expire_date',
            'value'=>$item_info->expire_date != '1970-01-01'?date(get_date_format(),strtotime($item_info->expire_date != ''?$item_info->expire_date: date('Y-m-d'))):'')
            );?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label(lang('items_life_cycle').':', 'life_cycle',array('class'=>'wide')); ?>
        <div class='form_field'>
            <?php echo form_input(array(
            'name'=>'life_cycle',
            'id'=>'life_cycle',
            'value'=>$item_info->life_cycle)
            );?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label(lang('items_location').':', 'location',array('class'=>'wide')); ?>
        <div class='form_field'>
            <?php echo form_input(array(
            'name'=>'location',
            'id'=>'location',
            'value'=>$item_info->location)
            );?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label(lang('items_description').':', 'description',array('class'=>'wide')); ?>
        <div class='form_field'>
            <?php echo form_textarea(array(
            'name'=>'description',
            'id'=>'description',
            'value'=>$item_info->description,
            'rows'=>'5',
            'cols'=>'17')
            );?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label(lang('items_allow_alt_desciption').':', 'allow_alt_description',array('class'=>'wide')); ?>
        <div class='form_field'>
            <?php echo form_checkbox(array(
            'name'=>'allow_alt_description',
            'id'=>'allow_alt_description',
            'value'=>1,
            'checked'=>($item_info->allow_alt_description)? 1  :0)
            );?>
        </div>
    </div>

    <div class="field_row clearfix">
        <?php echo form_label(lang('items_is_serialized').':', 'is_serialized',array('class'=>'wide')); ?>
        <div class='form_field'>
            <?php echo form_checkbox(array(
            'name'=>'is_serialized',
            'id'=>'is_serialized',
            'value'=>1,
            'checked'=>($item_info->is_serialized)? 1 : 0)
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

    //validation and submit handling
    $(document).ready(function()
    {
        $( "#category" ).autocomplete({
            source: "<?php echo site_url('items/suggest_category');?>",
            delay: 10,
            autoFocus: false,
            minLength: 0
        });
        $( "#manufacturers" ).autocomplete({
            source: "<?php echo site_url('items/suggest_manufacturers');?>",
            delay: 10,
            autoFocus: false,
            minLength: 0
        });


        var submitting = false;

        $('#item_form').validate({
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
                        post_item_form_submit(response);
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
                cost_price:
                    {
                    required:true,
                    number:true
                },

                unit_price:
                    {
                    required:true,
                    number:true
                },
                tax_percent:
                    {
                    required:true,
                    number:true
                },
                quantity:
                    {
                    required:true,
                    number:true
                },
                reorder_level:
                    {
                    required:true,
                    number:true
                }
            },
            messages:
                {
                name:"<?php echo lang('items_name_required'); ?>",
                category:"<?php echo lang('items_category_required'); ?>",
                cost_price:
                    {
                    required:"<?php echo lang('items_cost_price_required'); ?>",
                    number:"<?php echo lang('items_cost_price_number'); ?>"
                },
                unit_price:
                    {
                    required:"<?php echo lang('items_unit_price_required'); ?>",
                    number:"<?php echo lang('items_unit_price_number'); ?>"
                },
                tax_percent:
                    {
                    required:"<?php echo lang('items_tax_percent_required'); ?>",
                    number:"<?php echo lang('items_tax_percent_number'); ?>"
                },
                quantity:
                    {
                    required:"<?php echo lang('items_quantity_required'); ?>",
                    number:"<?php echo lang('items_quantity_number'); ?>"
                },
                reorder_level:
                    {
                    required:"<?php echo lang('items_reorder_level_required'); ?>",
                    number:"<?php echo lang('items_reorder_level_number'); ?>"
                }

            }
        });

        $('#expire_date').datePicker({startDate: '<?php echo get_js_start_of_time_date(); ?>'});
        $( "#unit" ).autocomplete({
            source: "<?php echo site_url('items/suggest_unit');?>",
            delay: 10,
            autoFocus: false,
            minLength: 0
        });

    });
</script>