<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo lang('items_generate_barcodes'); ?></title>
        <style>
            body
            {
                width: 4in;
                margin: 0;
                height: 4in;
            }
            .label{
                /* Avery 5160 labels -- CSS and HTML by MM at Boulder Information Services */
                width: 1.2in; /* plus .6 inches from padding */
                height: 0.7in; /* plus .125 inches from padding */
                padding: 0.125in 0.035in 0;
                margin-right: 0.085in; /* the gutter */
                float: left;
                text-align: center;
                overflow: hidden;
                font-size: 9pt;
            }

            .label_right{
                /* Avery 5160 labels -- CSS and HTML by MM at Boulder Information Services */
                width: 1.2in; /* plus .6 inches from padding */
                height: 0.7in; /* plus .125 inches from padding */
                padding: 0.125in 0.035in 0;
                float: left;
                text-align: center;
                overflow: hidden;
                font-size: 9pt;
            }
            .label_top{
                /* Avery 5160 labels -- CSS and HTML by MM at Boulder Information Services */
                width: 1.2in; /* plus .6 inches from padding */
                height: 0.7in; /* plus .125 inches from padding */
                padding: 0 0.035in 0;
                margin-right: 0.085in; /* the gutter */
                float: left;
                text-align: center;
                overflow: hidden;
                font-size: 9pt;
            }
            .label_top_right{
                /* Avery 5160 labels -- CSS and HTML by MM at Boulder Information Services */
                width: 1.2in; /* plus .6 inches from padding */
                height: 0.7in; /* plus .125 inches from padding */
                padding: 0 0.035in 0;
                float: left;
                text-align: center;
                overflow: hidden;
                font-size: 9pt;
            }
            .page-break  {
                clear: left;
                display:block;
                page-break-after:always;
            }
            span{
                white-space:nowrap;
            }

        </style>
</head>
<body style="margin: 0;">
 <?php for($j=0;$j<15;$j++):?>
        <?php if($j<2):?>
        <div class="label_top">
        <?php elseif($j == 2):?>
        <div class="label_top_right">
        <?php elseif($j % 3 == 2):?>
        <div class="label_right">
        <?php else:?>
        <div class="label">
        <?php endif;?>
            <?php
{
	$item = $items[0];
	$barcode = $item['id'];
	$text = "";

//	$style = ($k == count($items) -1) ? 'text-align:center;font-size: 10pt;' : 'text-align:center;font-size: 10pt;page-break-after: always;';
//	echo "<span>".substr($this->Appconfig->get('company'),0,22)."</span><img src='".site_url('barcode')."?barcode=$barcode&text=$text&scale=$scale' />";
    echo "<span>".substr($this->Appconfig->get('company'),0,22)."</span><img src='".site_url('barcode')."?barcode=$barcode&text=$text&scale=$scale'/><span>".substr($item['name'],0,12).":".$item['price']."</span>";
}
?>
        </div>
            <?php endfor;?>
            <div class="page-break"></div>
</body>
</html>
