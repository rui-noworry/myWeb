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
            <a href="/" id="logo"></a>
            <ul class="nav">
                <li><a class="on" href="/">课程中心</a></li>
                <li><a href="/apps/resource/">资源中心</a></li>
                <li><a href="/">我的空间</a></li>
                <li><a href="javascript:;">应用中心</a></li>
            </ul>
            <?php if((intval($authInfo['a_id'])) != "0"): ?><a href="/Public/logout" class="exit">[退出]</a>
            <?php else: ?>
                <a class="loginin">登陆</a><?php endif; ?>
            <a href="/Client/download" title="客户端下载" class="download">客户端下载&nbsp;&nbsp;</a>
        </div>
    </div>
<link rel="stylesheet" type="text/css" href="/Public/Css/Home/client.css" />
    <script type="text/javascript">
    <!--
        $(function() {
            $(".wl-subnav-item").click(function() {
                $(this).addClass('current').siblings().removeClass('current');
                $(".wl-content .wl-items").eq($(this).index()).show().siblings().hide();
            }).eq(0).click();
            $(".wl-subnav-item").mouseover(function() {
                $(this).addClass('current').siblings().removeClass('current');
                $(".wl-content .wl-items").eq($(this).index()).show().siblings().hide();
            })
        })
    //-->
    </script>
    <div style="height:30px;"></div>
    <div class="hide"></div>
    <div style="height:62px;" class="wl-subnav">
        <div class="wl-subnav-content">
            <div class="wl-subnav-item current"><a class="androidicon" href="#"></a></div>
            <div class="wl-subnav-item"><a class="iosicon" href="#"></a></div>
            <div class="wl-subnav-item"><a class="editicon" href="#"></a></div>
            <div class="wl-subnav-item"><a class="yhscicon" href="#"></a></div>
        </div>
    </div>
    <div class="wl-content">
        <!-- android -->
        <div class="wl-items" style="background:url('/Public/Images/Home/clientbbg.png') no-repeat;height:640px;">
            <div class="wl-item">
                <div class="icon"><img src="/Public/Images/Home/icon/clientlogo.jpg"></div>
                <div class="info">
                    <div class="title">大课堂Android客户端教师版</div>
                    <div class="version">最新版本: </div><div class="text">V3.0&nbsp;&nbsp;&nbsp;&nbsp;发布日期: 2013.03.26 &nbsp;&nbsp;&nbsp;&nbsp;软件大小: 3.26M</div>
                    <div class="desc">产品介绍: </div><div class="text">大课堂产品是一种新型的教育模式，通过移动终端可以实现，本地资源的存放与阅读，教学视频的观看与点评。 课堂上讲课，互动练习，批改作业，发布作业以及记录学习的点点滴滴的记事本功能。</div>
                    <div class="desc">配置要求: </div><div class="text">最适分辨率 1280x800，系统版本4.0以上</div>
                </div>
                <?php if(($adst) != ""): ?><div class="dlbutton"><a href="<?php echo ($adst); ?>"><img src="/Public/Images/Home/adtd.png"></a></div><?php endif; ?>
            </div>
            <div class="clear"></div>
            <div class="wl-item">
                <div class="icon"><img src="/Public/Images/Home/icon/clientlogo.jpg"></div>
                <div class="info">
                    <div class="title">大课堂Android客户端学生版</div>
                    <div class="version">最新版本: </div><div class="text">V3.0&nbsp;&nbsp;&nbsp;&nbsp;发布日期: 2013.03.26 &nbsp;&nbsp;&nbsp;&nbsp;软件大小: 3.26M</div>
                    <div class="desc">产品介绍: </div><div class="text">大课堂产品是一种新型的教育模式，通过移动终端可以实现，本地资源的存放与阅读，教学视频的观看与点评。 课堂上讲课，互动练习，批改作业，发布作业以及记录学习的点点滴滴的记事本功能。</div>
                    <div class="desc">配置要求: </div><div class="text">最适分辨率 1280x800，系统版本4.0以上</div>
                </div>
                <?php if(($adss) != ""): ?><div class="dlbutton"><a href="<?php echo ($adss); ?>"><img src="/Public/Images/Home/adsd.png"></a></div><?php endif; ?>
            </div>
            <!--div class="clear"></div>
            <div class="wl-item">
                <div class="icon"><img src="/Public/Images/Home/icon/clientlogo.jpg"></div>
                <div class="info">
                    <div class="title">大课堂三星教师版</div>
                    <div class="version">最新版本: </div><div class="text">V3.0&nbsp;&nbsp;&nbsp;&nbsp;发布日期: 2013.04.24 &nbsp;&nbsp;&nbsp;&nbsp;软件大小: 3.26M</div>
                    <div class="desc">产品介绍: </div><div class="text">大课堂产品是一种新型的教育模式，通过移动终端可以实现，本地资源的存放与阅读，教学视频的观看与点评。 课堂上讲课，互动练习，批改作业，发布作业以及记录学习的点点滴滴的记事本功能。</div>
                    <div class="desc">配置要求: </div><div class="text">最适分辨率 1280x800，系统版本4.0以上</div>
                </div>
                <?php if(($adst) != ""): ?><div class="dlbutton"><a href="<?php echo ($adst); ?>"><img src="/Public/Images/Home/adtd.png"></a></div><?php endif; ?>
            </div>
            <div class="clear"></div>
            <div style="background: transparent;" class="wl-item">
                <div class="icon"><img src="/Public/Images/Home/icon/clientlogo.jpg"></div>
                <div class="info">
                    <div class="title">大课堂三星学生版</div>
                    <div class="version">最新版本: </div><div class="text">V3.0&nbsp;&nbsp;&nbsp;&nbsp;发布日期: 2013.04.24 &nbsp;&nbsp;&nbsp;&nbsp;软件大小: 3.26M</div>
                    <div class="desc">产品介绍: </div><div class="text">大课堂产品是一种新型的教育模式，通过移动终端可以实现，本地资源的存放与阅读，教学视频的观看与点评。 课堂上讲课，互动练习，批改作业，发布作业以及记录学习的点点滴滴的记事本功能。</div>
                    <div class="desc">配置要求: </div><div class="text">最适分辨率 1280x800，系统版本4.0以上</div>
                </div>
                <?php if(($adss) != ""): ?><div class="dlbutton"><a href="<?php echo ($adss); ?>"><img src="/Public/Images/Home/adsd.png"></a></div><?php endif; ?>
            </div-->
        </div>
        <!-- ios -->
        <div class="wl-items">
            <div class="wl-item">
                <div class="icon"><img src="/Public/Images/Home/icon/clientlogo.jpg"></div>
                <div class="info">
                    <div class="title">大课堂IOS客户端教师版</div>
                    <div class="version">最新版本: </div><div class="text">V1.0&nbsp;&nbsp;&nbsp;&nbsp;发布日期: 2013.04.24 &nbsp;&nbsp;&nbsp;&nbsp;软件大小: 1.7M</div>
                    <div class="desc">产品介绍: </div><div class="text">大课堂产品是一种新型的教育模式，通过移动终端可以实现，本地资源的存放与阅读，教学视频的观看与点评。
课堂上讲课，互动练习，批改作业，发布作业以及记录学习的点点滴滴的记事本功能。</div>
                    <div class="desc">配置要求: </div><div class="text">苹果ipad设备，iOS系统版本4.3或以上版本</div>
                </div>
                <?php if(($iotd) != ""): ?><div class="dlbutton"><a href="itms-services://?action=download-manifest&url=<?php echo ($iotd); ?>"><img src="/Public/Images/Home/iotd.png"></a></div><?php endif; ?>
            </div>
            <div class="clear"></div>
            <div style="background:transparent;" class="wl-item">
                <div class="icon"><img src="/Public/Images/Home/icon/clientlogo.jpg"></div>
                <div class="info">
                    <div class="title">大课堂IOS客户端学生版</div>
                    <div class="version">最新版本: </div><div class="text">V1.0&nbsp;&nbsp;&nbsp;&nbsp;发布日期: 2013.04.24 &nbsp;&nbsp;&nbsp;&nbsp;软件大小: 1.7M</div>
                    <div class="desc">产品介绍: </div><div class="text">大课堂产品是一种新型的教育模式，通过移动终端可以实现，本地资源的存放与阅读，离线作业，教学视频的观看与点评。
课堂上听课，互动练习，做作业以及记录学习的点点滴滴的记事本功能。</div>
                    <div class="desc">配置要求: </div><div class="text">苹果ipad设备，iOS系统版本4.3或以上版本</div>
                </div>
                <?php if(($iosd) != ""): ?><div class="dlbutton"><a href="itms-services://?action=download-manifest&url=<?php echo ($iosd); ?>"><img src="/Public/Images/Home/iosd.png"></a></div><?php endif; ?>
            </div>
        </div>
        <!-- 在线编辑器下载 -->
        <div class="wl-items">
            <div class="wl-item">
                <div class="icon"><img src="/Public/Images/Home/icon/clientlogo.jpg"></div>
                <div class="info">
                    <div class="title">大课堂IE浏览器在线编辑插件</div>
                    <div class="version">最新版本: </div><div class="text">V1.0&nbsp;&nbsp;&nbsp;&nbsp;发布日期: 2013.04.24 &nbsp;&nbsp;&nbsp;&nbsp;软件大小: 24.2M</div>
                    <div class="desc">产品介绍: </div><div class="text">IE浏览器下，安装此插件后，可在浏览器富文本编辑器中，直接复制粘贴WORD中的图片，无需手动上传。</div>
                </div>
                <div class="dlbutton"><a href="/apps/Uploads/Config/FileTransfer.rar"><img src="/Public/Images/Home/editdw.png"></a></div>
            </div>
            <div class="clear"></div>
            <div style="background: transparent;" class="wl-item">
                <div class="icon"><img src="/Public/Images/Home/icon/clientlogo.jpg"></div>
                <div class="info">
                    <div class="title">大课堂编辑器使用文档</div>
                    <div class="version">最新版本: </div><div class="text">V1.0&nbsp;&nbsp;&nbsp;&nbsp;发布日期: 2013.04.24 &nbsp;&nbsp;&nbsp;&nbsp;文件大小: 1.38M</div>
                    <div class="desc">产品介绍: </div><div class="text">富文本编辑器使用说明，各操作按钮详细示例。</div>
                </div>
                <div class="dlbutton"><a href="/apps/Uploads/Config/EditorInstructions.doc"><img src="/Public/Images/Home/use.png"></a></div>
            </div>
        </div>
        <!-- 用户手册下载 -->
        <div class="wl-items">
            <div class="wl-item">
                <div class="icon"><img src="/Public/Images/Home/icon/clientlogo.jpg"></div>
                <div class="info">
                    <div class="title">大课堂用户手册</div>
                    <div class="version">最新版本: </div><div class="text">V1.0&nbsp;&nbsp;&nbsp;&nbsp;发布日期: 2013.04.24 &nbsp;&nbsp;&nbsp;&nbsp;软件大小: 5.89M</div>
                    <div class="desc">产品介绍: </div><div class="text">用户可以详细的了解大课堂教学平台的使用方法，为用户提高操作效率的同时，让用户可以更全面，更系统的使用大课堂教学平台。</div>
                </div>
                <div class="dlbutton"><a href="/apps/Uploads/Config/UserManual.doc"><img src="/Public/Images/Home/scdw.png"></a></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>