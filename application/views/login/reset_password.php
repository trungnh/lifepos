<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon"/>
        <link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/login.css?<?php echo APPLICATION_VERSION; ?>" />
        <title>LifeTek POS - <?php echo lang('login_reset_password'); ?></title>
        <script src="<?php echo base_url();?>js/jquery-1.3.2.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
        <script type="text/javascript">
            $(document).ready(function()
            {
                $("#login_form input:first").focus();
            });
        </script>
    </head>
    <body>
        <div id="welcome_message" class="top_message">
            <?php echo lang('login_reset_password_title'); ?>
        </div>
        <?php if (validation_errors()) {?>
        <div id="welcome_message" class="top_message_error">
                <?php echo validation_errors(); ?>
        </div>
        <?php } ?>
        <div id="page">
            <div id="introduce">
                <div>
                    <table border="0">
                        <tr>
                            <td><img src="/images/logosp/lifepos.png" alt="LifePos" class="lifepos_logo"/></td>
                            <td><span style="color:blue;font-size:21px"> - Phần mềm quản lý bán hàng online</span></td>
                        </tr>
                    </table>
                </div>
                <div style="padding-top:15px;padding-left:25px;">
                    <table border="0">
                        <tr class="lifepos_slogan_tr_padding">
                            <td class="lifepos_slogan_tr_padding"><img src="/images/logosp/slogan_noheadache.jpg" alt="LifePos" class="lifepos_slogan_img"/></td>
                            <td class="lifepos_slogan_padding">
                                <span class="lifepos_slogan_text_bold">Không phải cài đặt</span><br/>
                                <span class="lifepos_slogan_text">Không đau đầu - Chỉ là duyệt web</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="lifepos_slogan_tr_padding"><img src="/images/logosp/slogan_easyuse.jpg" alt="LifePos" class="lifepos_slogan_img"/></td>
                            <td class="lifepos_slogan_padding">
                                <span class="lifepos_slogan_text_bold">Đơn giản - Dễ dùng</span><br/>
                                <span class="lifepos_slogan_text">Dùng mọi lúc mọi nơi - Giúp quản lý công việc từ xa</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="lifepos_slogan_tr_padding"><img src="/images/logosp/slogan_novirus.jpg" alt="LifePos" class="lifepos_slogan_img"/></td>
                            <td class="lifepos_slogan_padding">
                                <span class="lifepos_slogan_text_bold">Không sợ vi rút</span><br/>
                                <span class="lifepos_slogan_text">Không lo mất dữ liệu khi hỏng máy</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="lifepos_slogan_tr_padding"><img src="/images/logosp/slogan_upgrade.jpg" alt="LifePos" class="lifepos_slogan_img"/></td>
                            <td class="lifepos_slogan_padding">
                                <span class="lifepos_slogan_text_bold">Dễ dàng nâng cấp - bảo trì</span><br/>
                                <span class="lifepos_slogan_text">Chi phí thấp - Được hỗ trợ tối đa từ nhà sản xuất</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="footer-inside">
                    <div class="f1">
                        <p id="p-footer">
                            <a target="_blank" href="http://pos.vn">Trang chủ</a>|
                            <a target="_blank" href="#">Hướng dẫn sử dụng</a>|
                            <a target="_blank" href="#">Liên hệ</a>|
                            <a target="_blank" href="#">Quảng cáo</a>|
                            <a target="_blank" href="http://lifetek.com.vn">Về LifeTek</a></p>
                        <p>@Công ty TNHH Công Nghệ Điện Tử-Phần Mềm-Viễn Thông LIFETEK</p>
                        <hr/>
                    </div>
                </div>
            </div>
            <div id="form">
                <?php echo form_open('login/do_reset_password_notify') ?>
                <div id="container">
                    <div id="top">
                        <?php echo img(array('src' => $this->Appconfig->get_logo_image()));?>
                    </div>
                    <table id="login_form">

                        <tr id="form_field_username">
                            <td class="form_field_label"><?php echo lang('login_username'); ?>/<br /><?php echo lang('common_email'); ?>: </td>
                            <td class="form_field">
                                <?php echo form_input(array(
                                'name'=>'username_or_email',
                                'size'=>'20')); ?>
                            </td>
                        </tr>
                        <tr id="form_field_submit">
                            <td id="submit_button" colspan="2">
                                <?php echo form_submit('login_button',lang('login_reset_password')); ?>
                            </td>
                        </tr>
                    </table>
                    <table id="bottom">
                        <tr>
                            <td id="right">
                                <?php echo date("Y")?> <?php echo lang('login_version'); ?> <?php echo APPLICATION_VERSION; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php echo form_close(); ?>
                <div style="float:right;">
                    <table>
                        <tr>
                            <td>
                                Một sản phẩm của
                            </td>
                            <td>
                                <a href="http://lifetek.com.vn" style="margin-top:10px;">
                                    <img src="/images/logosp/lifetek.png" alt="Công ty TNHH Công Nghệ Điện Tử-Phần Mềm-Viễn Thông LIFETEK" width="100"/>
                                </a>
                            </td>
                        </tr>
                    </table>
                    <hr/>
                </div>
            </div>

        </div>
        <div class="clear"></div>
        <div class="lifetek_logos">
            <iframe src="http://pos.vn/pos/logo_product.html" style="width:1000px;border:0px;"></iframe>
        </div>
    </body>
</html>