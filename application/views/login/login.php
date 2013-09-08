<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <!--<link rel="icon" href="<?php //echo base_url();?>favicon.ico" type="image/x-icon"/> -->
        <link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/login.css?<?php echo APPLICATION_VERSION; ?>" />
        <title>LifeMap - <?php echo lang('login_login'); ?></title>
        <script src="<?php echo base_url();?>js/jquery-1.3.2.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
        <script type="text/javascript">
            $(document).ready(function()
            {
                $("#login_form input:first").focus();
            });
        </script>
    </head>
    <body>
        <div class="lifepos-header-bar">
            <div class="header content clearfix">                
                <span class="signup-button">                    
                    <a href="#" class="g-button g-button-red" id="link-signup">
                        Tạo tài khoản
                    </a>
                </span>
            </div>
        </div>
        <!-- hiển thị lỗi tại đây khi đăng nhập sai -->
        
        
        <div id="page">
            <div class="introduct-info">
                
            </div>
            <div id="form">
                <div class="sigin-box">
                <?php echo form_open('login') ?>
                <div id="container">
                    <div id="top">
                        Đăng nhập
                    </div>
                    <table id="login_form">

                        <tr id="form_field_username">
                            <td class="form_field_label"><?php echo lang('login_username'); ?>: </td>
                            <td class="form_field">
                                <?php echo form_input(array(
                                'name'=>'username',
                                'value'=> '',
                                'size'=>'20')); ?>
                            </td>
                        </tr>

                        <tr id="form_field_password">
                            <td class="form_field_label"><?php echo lang('login_password'); ?>: </td>
                            <td class="form_field">
                                <?php echo form_password(array(
                                'name'=>'password',
                                'value'=>'',
                                'size'=>'20')); ?>
                            </td>
                        </tr>

                        <tr id="form_field_submit">
                            <td id="submit_button" colspan="2">
                                <?php echo form_submit('login_button',lang('login_login')); ?>
                            </td>
                        </tr>
                    </table>
                    <!--<table id="bottom">
                        <tr>
                            <td id="left">
                                <?php //echo anchor('login/reset_password', lang('login_reset_password')); ?>
                            </td>
                            <td id="right">
                                <?php //echo date("Y")?> <?php echo lang('login_version'); ?> <?php echo APPLICATION_VERSION; ?>
                            </td>
                        </tr>
                    </table> -->
                </div>
                <?php echo form_close(); ?>                
            </div>
            </div>
        </div>
        <div class="clear"></div>        
    </body>
</html>