<?php
function to_currency($number) {
    $CI =& get_instance();
    $currency_symbol = $CI->config->item('currency_symbol') ? $CI->config->item('currency_symbol') : 'đ';
    if($number >= 0) {
        return number_format($number, 0, '.', ',').$currency_symbol;
    }
    else {
        return '-'.number_format(abs($number), 0, '.', ',').$currency_symbol;
    }
}


function to_currency_no_money($number) {
    return number_format($number, 0, '.', '');
}
?>