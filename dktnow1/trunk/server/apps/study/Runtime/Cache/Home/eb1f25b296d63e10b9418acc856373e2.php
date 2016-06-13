<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/public.css" /><script type="text/javascript" src="/Public/Js/Public/jquery-1.9.1.js"></script><script type="text/javascript" src=" /Public/Js/Public/public.js"></script>
    <!--[if IE 6]>
    <script type="text/javascript" src="__PUBLIC__/Js/Public/png.js"></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('#logo,.cShare,.cEdit,.cIn,.cClone,.cExport,.cDel,.fw_baoming_left,.fw_btn,.anli_ico_link,.selected_green,.selected_gray,.to-left,.to-right,.current,.mt_tab li,.classhomework_top li,.choose_class,.current img,.res_click,.res_scan,.res_frame.png,#main_bg li img,.jCal .left,.jCal .right,.gro_app span,.class_li,.add_add,.flex_close,.main_new li a,.info_text,.info_pass,.body_right .title h2 i,.top_tab_menu .select_ed');
    </script>
    <![endif]-->
    <title>大课堂互动教学</title>
    <script>
        <!--//

            // 导航动画效果
            $(function(){

                $('#header li a').wrapInner('<span class="out"></span>');

                $('#header li a').each(function() {
                    $('<span class="over">' + $(this).text() + '</span>').appendTo(this);
                });

                $('#header li a').hover(function() {
                    $('.out',this).stop().animate({'top':'67px'},200);
                    $('.over',this).stop().animate({'top':'0px'},200);

                }, function() {
                    $('.out',this).stop().animate({'top':'0px'},200);
                    $('.over',this).stop().animate({'top':'-67px'},200);
                });
                
                $('#header li a').click(function(){

                    // 选中菜单项的样式
                    $(this).addClass('on').parent().siblings().find('a').removeClass('on');

                    // 恢复动画
                    $('.out',this).css('top','0px');
                    $('.over',this).css('top','-67px');
                    $(this).parent().siblings().find('a').hover(function(){
                        $('.out',this).stop().animate({'top':'67px'},200);
                        $('.over',this).stop().animate({'top':'0px'},200);
                    }, function() {
                        $('.out',this).stop().animate({'top':'0px'},200);
                        $('.over',this).stop().animate({'top':'-67px'},200);
                    })
                    // 停止当前点击导航的动画效果
                    $(this).hover(function(){
                        $('.out',this).stop();
                        $('.over',this).stop();
                    },function(){
                        $('.out',this).stop();
                        $('.over',this).stop();
                    })
                })

                if ($('#header .exit').size() == 0) {
                    // ajax登录
                    $.post("/Public/checkLogin", 'num='+Math.random(), function(json) {

                        if (json.status == 0) {
                            $(".conrig_error").html(json.message);
                            $("#verify").show();
                            $('#login').height(340);
                        } else {

                            $('#header .nav').next().attr('class', 'exit').attr('href', '/Public/logout').html('[退出]');
                            $('<a class="member" href="__APPURL__/School">会员中心</a>').insertBefore($('.download'));
                            $('.closeWin').click();

                        }
                    }, 'json')
                }
            })
        //-->
    </script>
</head>
<body id="body">
    <div id="header">
        <div>
            <a href="__APPURL__/Index/" id="logo"></a>
            <ul class="nav">
                <li><a <?php if(($bannerOn) == "1"): ?>class="on"<?php endif; ?> href="__APPURL__/Course">课程中心</a></li>
                <?php if(($resourceOn) != ""): ?><li><a <?php if(($bannerOn) == "2"): ?>class="on"<?php endif; ?> href="<?php echo ($resourceOn); ?>">资源中心</a></li><?php endif; ?>
                <li><a <?php if(($bannerOn) == "3"): ?>class="on"<?php endif; ?> href="__APPURL__/Space">我的空间</a></li>
                <li><a <?php if(($bannerOn) == "4"): ?>class="on"<?php endif; ?> href="javascript:;">应用中心</a></li>
            </ul>
            <?php if(($authInfo['a_id']) != "0"): ?><a href="/Public/logout" class="exit">[退出]</a>
            <?php else: ?>
                <a class="loginin">登陆</a><?php endif; ?>
            <a href="/Client/download" title="客户端下载" class="download">客户端下载&nbsp;&nbsp;</a>
        </div>
    </div>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/upgrade.css" />
<script type="text/javascript" src="/Public/Js/Public/provincesCity.js"></script><script type="text/javascript" src="/Public/Js/Public/provincesdata.js"></script>
    <script type="text/javascript">
    <!--
        $(function(){

            //var flag = true;
            var reg=/(^(13|15|18)[0-9]{9}$)|(^18[5-9]{1}[0-9]{8}$)|(^0{0,1}13[0-9]{9}$)/;

            // 如果所属区域选择了，则清除错误提示
            $('#province').change(function(){
                $('.school_area .show_message').html('');
            })

            // 如果学校类型选择了，则清除错误提示
            if($('.school_type input:checked').length > 0){
                
                $('.school_type .show_message').html('');
            }

            // 学校名称选中时，清除错误提示
            $('input[name=as_title]').focus(function(){
                $('.school_name .show_message').html('');
            })
            
            // 学校名称 鼠标离开 判断是否为空
            $('input[name=as_title]').blur(function(){
                if($(this).val() == ''){
                    $('.school_name .show_message').html('请输入学校名称');
                }
            })
            
            // 您的手机选中时，清除错误提示
            $('input[name=as_my_tel]').focus(function(){
                $('.your_tel .show_message').html('');
            })

            // 您的手机 鼠标离开 判断是否为空
            $('input[name=as_my_tel]').blur(function(){
                if($(this).val() == ''){
                    $('.your_tel .show_message').html('请输入您的手机号');
                } else if(reg.test($('.your_tel input').val()) == false){
                    $('.your_tel .show_message').html('您输入的手机号有误，请重新输入');
                }
            })

            // 提交申请
            $('button[name=submit]').on('click',function(){

                // 提交类型
                var type = $(this).attr('attr');
                // 获取学段,和地区
                var xueduan = $('input[name=choose_school]:checked').attr('attr');
                var region = $('input[name=a_region]').val();

                // 所属区域
                if (!region || region == '######') {
                    //showMessage('请选择省市区');
                    $('.school_area .show_message').html('请选择省市区');
                    return false;
                } else {
                    $('.school_area .show_message').html('');
                }

                // 学校类型
                if($('.school_type input:checked').length == 0){
                    //showMessage('请选择学校类型');
                    $('.school_type .show_message').html('请选择学校类型');
                    return false;
                } else {
                    $('.school_type .show_message').html('');
                }
                // 学校名称
                if($('.school_name input').val() == ''){
                    //showMessage('请输入学校名称');
                    $('.school_name .show_message').html('请输入学校名称');
                    return false;
                }else {
                    $('.school_name .show_message').html('');
                }

                // 您的电话
                if($('.your_tel input').val() == ''){
                    //showMessage('请输入您的电话');
                    $('.your_tel .show_message').html('请输入您的电话');
                    return false;
                } else if(reg.test($('.your_tel input').val()) == false){
                    //showMessage('您输入的电话号有误，请重新输入');
                    $('.your_tel .show_message').html('您输入的电话号有误，请重新输入');
                    return false;
                } else {
                    $('.your_tel .show_message').html('');
                }

                // 学校类型
                var as_type = '';

                $('input[name=as_type]:checked').each(function(){
                    as_type += ',' + $(this).val();
                });

                as_type = as_type.substr(1);

                // 学校名称
                var as_title = $('input[name=as_title]').val();

                // 您的电话
                var as_my_tel = $('input[name=as_my_tel]').val();

                // 校长电话
                var as_header_tel = $('input[name=as_header_tel]').val();
                //if(flag == true){
                    $.post('__APPURL__/ApplySchool/insert', 'region='+region+'&as_type='+as_type+'&as_title='+as_title+'&as_my_tel='+as_my_tel+'&as_header_tel='+as_header_tel, function(json){
                        if (json.status) {
                            if (type == 2) {
                                location.href='__APPURL__/ApplyClass/index/as_id/'+json.status;
                            } else {
                                showMessage('添加成功');
                                location.href='__APPURL__/Class/apply';
                            }
                        } else {
                            showMessage(json.info+'天之内请不要反复申请, 学校已在审核中...');
                        }

                    }, 'json');
                //}

            })

            // 学校所在地联动
            setProvince("province", "a_region", '');

        })

    //-->
    </script>

    <div class="warp">
        <div class="add_school">

            <div class="tit">
                <span>注册学校</span>
                <a href="javascript:history.go(-1)">返回</a>
            </div>

            <div class="mt_box">
                <div class="step_by">
                    <div class="school_area">
                        <cite class="space"><i>*</i>所属地区</cite>
                        <div>
                            <p class="prompt">填写新增学校信息<cite><!--（以下均为必填项）--></cite></p>
                            <div>
                                <label>省/直辖市：</label>
                                <span id="province"></span>
                                <input type="hidden" name="a_region"/>
                            </div>
                            <span class="show_message"></span>
                        </div>
                    </div>

                    <div class="school_type">
                        <cite class="space"><i>*</i>学校类型</cite>
                        <div>
                            <input type="checkbox" name="as_type" value="1">
                            <label>小学</label>
                            <input type="checkbox" name="as_type" value="2">
                            <label>初中</label>
                            <input type="checkbox" name="as_type" value="3">
                            <label>高中</label>
                            <input type="checkbox" name="as_type" value="4">
                            <label>大学</label>
                        </div>
                        <span class="show_message"></span>
                    </div>
                    <div class="school_name">
                        <cite class="space"><i>*</i>学校名称</cite>
                        <input type="text" name="as_title"></input>
                        <span class="show_message"></span>
                    </div>

                    <div class="smaster_tel">
                        <cite class="space">校长电话</cite>
                        <input type="text" name="as_header_tel"></input>
                        <span class="show_message"></span>
                    </div>

                    <div class="your_tel">
                        <cite class="space"><i>*</i>您的手机</cite>
                        <input type="text" name="as_my_tel"></input>
                        <span class="show_message"></span>
                    </div>

                    <div class="apply_button">
                        <i class="space"></i>
                        <button name="submit" class="apply_school_btn" attr="1">提交申请</button>
                        <button name="submit" class="apply_class_btn" attr="2">添加班级</button>
                    </div>
                </div>

            </div>

        </div>

        <div class="clear"></div>
    </div>

    <div class="clear"></div>
    <div class="foot_bot"></div>
    <div class="foot_top"></div>
    <div id="footer">
        <div class="nav back1"></div>
        Copyright &copy; 2007-2011 北京金商祺移动互联 All Rights Reserved.
    </div>
</body>
</html>