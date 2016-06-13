<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/public.css" /><script type="text/javascript" src=" /Public/Js/Public/jquery-1.9.1.js"></script><script type="text/javascript" src=" /Public/Js/Public/public.js"></script>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/jquery-ui.css" />
    <script type="text/javascript" src="/Public/Js/Home/jquery-ui.js"></script>
    <!--[if IE 6]>
    <script type="text/javascript" src="__PUBLIC__/Js/Public/png.js" ></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('#logo,.cShare,.cEdit,.cIn,.cClone,.cExport,.cDel,.fw_baoming_left,.fw_btn,.anli_ico_link,.anli_ico,.selected,.selected_green,.selected_gray,.to-left,.to-right,.current,.mt_tab li,.classhomework_top li,.choose_class,.current img,.res_click,.res_scan,.res_frame.png,#main_bg li img,.jCal .left,.jCal .right');
    </script>
    <![endif]-->
    <title>大课堂互动教学</title>
    <script><!--//

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

            // 登陆弹出窗口
            /*$("#login").dialog({
                draggable: true,
                resizable: true,
                autoOpen: false,
                position :'center',
                stack : true,
                modal: true,
                bgiframe: true,
                width: '450',
                height: 'auto',

                show: {
                    effect: "blind",
                    duration: 500
                },
                hide: {
                  effect: "explode",
                  duration: 500
                },
                overlay: {
                    backgroundColor: '#000',
                    opacity: 0.5
                },
                buttons: {
                    确定: function() {
                        $(this).dialog('close');
                    },
                    取消: function() {
                        $(this).dialog('close');
                    }
                }
            });

            $('.loginin').on('click',function(){
                $("#login").dialog("open");
            })*/

            // 弹出登陆窗口
            $(".loginin").on('click',function () {
                popCenterWindow();
            });

            $('input[name=account]').blur(function() {
                $(".conrig_error span").hide();
            })

            $('input[name=password]').blur(function() {
                $(".conrig_error span").hide();
            })

            // 登陆验证
            $('.conrig_land').click(function(){

                // 用户名不能为空判断
                var account = $("input[name=account]").val();
                if (account=='') {
                    $(".conrig_error span").html("请输入用户名");
                    $(".conrig_error span").show();
                    $("input[name=name]").focus();
                    return false;
                } else {
                    $(".conrig_error span").hide();
                }

                // 密码不能为空判断
                var password = $("input[name=password]").val();
                if (password=='') {
                    $(".conrig_error span").html("请输入密码");
                    $(".conrig_error span").show();
                    $("input[name=password]").focus();
                    return false;
                } else {
                    $(".conrig_error span").hide();
                }

                // 是否记住登录状态
                var remember = $("input[name=remember]:checked").size();

                // 验证码
                var verify = '';
                if ($("#verify").css('display') == 'none') {
                    verify = 0;
                } else {
                    verify = $("input[name=verify]").val();
                    if (!verify) {
                        $(".conrig_error span").html("请输入验证码");
                        $(".conrig_error span").show();
                        $("input[name=verify]").focus();
                        return false;
                    }
                }

            })

            if ($('#header .exit').size() == 0) {
                // ajax登录
                $.post("/Public/checkLogin", 'num='+Math.random(), function(json) {

                    if (json.status == 0) {
                        $(".conrig_error").html(json.message);
                        $("#verify").show();
                        $('#login').height(340);
                    } else {

                        $('#header .nav').next().attr('class', 'exit').attr('href', '__APPURL__/Public/logout').html('[退出]');
                        $('<a class="member" href="__APPURL__/School">会员中心</a>').insertBefore($('.download'));
                        $('.closeWin').click();

                    }
                }, 'json')
            }

            // 关闭登陆窗口
            /*$('.closeWin').click(function(){
                $('#login').dialog('close');
            })*/


            // 设置登陆窗口遮罩层的宽和高
            $("#Win_cover").css({
                height: function () {
                    return $(document).height();
                },
                width: function () {
                    return $(document).width();
                }
            })
        })

        // 判断回车
        function keydown(e){

            var e = e || event;
            if (e.keyCode==13) {
                $(".conrig_land").click();
            }
        }

        // 重载验证码
        function fleshVerify(){
            $(".verifyImg").attr('src', '__APPURL__/Public/verify/'+ Math.random());
        }
        //-->

        //获取窗口的宽度
        var windowWidth;
        //获取窗口的高度
        var windowHeight;
        //获取弹窗的宽度
        var popWidth;
        //获取弹窗高度
        var popHeight;
        function init(){
            windowWidth = $(window).width();
            windowHeight = $(window).height();
            popWidth = $("#login").width();
            popHeight = $("#login").height();
        }
        //关闭窗口的方法
        function closeWindow(){
            $(".closeWin").click(function(){
                $(this).parent().fadeOut("slow");
                $("#Win_cover").hide();
            });
        }
        //定义弹出居中窗口的方法
        function popCenterWindow(){
            init();

            //计算弹出窗口的左上角Y的偏移量
            var popY = (windowHeight - popHeight) / 2;
            var popX = (windowWidth - popWidth) / 2;
            //alert(popX+"@@@@@@@@"+popY);
            //设定窗口的位置
            $("#Win_cover").show();
            $("#login").css("top",popY).css("left",popX).slideToggle("slow");
            closeWindow();
       }

    </script>
</head>
<body id="body">
    <div id="header">
        <div>
            <a href="__APPURL__/Index/" id="logo"></a>
            <ul class="nav">
                <?php if(($studyOn) != ""): ?><li><a <?php if(($bannerOn) == "1"): ?>class="on"<?php endif; ?> href="<?php echo ($studyOn); ?>/Course">课程中心</a></li><?php endif; ?>
                <li><a <?php if(($bannerOn) == "2"): ?>class="on"<?php endif; ?> href="<?php echo ($resourceOn); ?>">资源中心</a></li>
                <?php if(($studyOn) != ""): ?><li><a <?php if(($bannerOn) == "3"): ?>class="on"<?php endif; ?> href="<?php echo ($studyOn); ?>/Space">我的空间</a></li><?php endif; ?>
                <li><a <?php if(($bannerOn) == "4"): ?>class="on"<?php endif; ?> href="javascript:;">应用中心</a></li>
            </ul>
            <?php if((intval($authInfo['a_id'])) != "0"): ?><a href="/Public/logout" class="exit">[退出]</a>
                <a class="member" href="__APPURL__/School">会员中心</a>
            <?php else: ?>
                <a class="loginin">登陆</a><?php endif; ?>
            <a href="/Client/download" title="客户端下载" class="download">客户端下载&nbsp;&nbsp;</a>
        </div>
    </div>
<!-- 登陆窗口 -->
<div id="Win_cover">
    <div id="login" title="用户登陆">
        <div class="closeWin"></div>
        <form method="post" name="form1" id="form1" action="">
            <div class="conrig_error"><span></span></div>
            <div class="conrig_name">
                <input type="text" value="" name="account" class="name_inp" placeholder="用户名"/>
            </div>
            <div class="conrig_name">
                 <input type="password" value="" name="password" class="name_inp" onkeydown="keydown(event)" placeholder="密码"/>
            </div>
            <div id="verify" style="display:none">
                  <input type="text" id="verify" name="verify" onkeydown="keydown(event)" placeholder="验证码"/></li>
                  <img src="__APPURL__/Public/verify/" class="verifyImg" onclick="fleshVerify();" border="0">
            </div>
            <div style="clear:both"></div>

            <div class="conrig_remember fl">
                <input name="remember" type="checkbox" class="fl" />记住密码
            </div>
            <a href="#" class="conrig_forget fr">忘记密码？</a>
            <div class="clear"></div>
            <a href="javascript:void(0)" class="conrig_land fl"></a>
            <a href="__APPURL__/Public/register" class="conrig_enroll fl"></a>
        </form>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/upload.css" />

<!--多文件上传plupload插件开始-->
<link rel="stylesheet" href="/Public/Js/Public/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css">
<script type="text/javascript" src="/Public/Js/Public/plupload/plupload.full.js"></script>
<script type="text/javascript" src="/Public/Js/Public/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="/Public/Js/Public/plupload/i18n/zh-cn.js"></script>
<!--多文件上传plupload插件结束-->

<script type="text/javascript">
<!--
    $(function() {

        //判断IE6浏览器（上传文件不兼容）
        if (window.ActiveXObject) {

              var ua = navigator.userAgent.toLowerCase();

              var ie=ua.match(/msie ([\d.]+)/)[1];

            if(ie==6.0){

             //alert("您的浏览器版本过低，在本系统中不能达到良好的视觉效果，建议你升级到ie8以上！");

             $('.upload_top').remove();

             $('.warn_file').show();

            //window.close();//关闭浏览器窗口;

            }

        }

        //IE6 添加列表，删除 滑过滑离
        $(document).on('mouseover','.file_add,.file_bottom li span',function(){
            $(this).addClass('on');
        }).on('mouseout','.file_add,.file_bottom li span',function(){
            $(this).removeClass('on')
        })

        //IE6 添加列表 点击
        $(document).on('click','.file_add',function(){
            var size = $('.file_bottom li').size();
            if (size > 9) {
                showMessage('一次最多只能上传10个');
            } else {
                $('.file_bottom').append("<li><label>上传资源</label><input type='file' name='a_avatar'/><span>删除</span></li>");
            }
        })

        //IE6 删除 点击
        $(document).on('click','.file_bottom li span',function(){
            if (confirm('确定要删除该项吗')) {
                $(this).parent().remove();
            } else {
                return false;
            }
        })

        //资源多文件上传
        reloadFileUpload();

        // 添加资源
        $('.finish_btn').click(function(){

            // 如果用户上传了附件，但忘了点击上传按钮，自动点击上传
            if ($('.plupload_buttons').css('display') != 'none' && $('#uploader_filelist').children().size() > 0) {
                $('.plupload_start').click();
                var uploader = $('#uploader').pluploadQueue();
                // Files in queue upload them first
                if (uploader.files.length > 0) {
                    // When all files are uploaded submit form
                    uploader.bind('UploadComplete', function() {
                        if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                            urlTurn();
                        }
                    });
                    uploader.start();
                }
            } else {

                urlTurn();
            }
        });

    })

    // 附件上传后，页面转向
    function urlTurn() {

        // 等待转码
        Loading();

        $.post('__URL__/insert', '', function(json){

            // 取消等待
            close_Loading();

            if (json) {

                var str = '';
                    for (var i = 0; i < json.length; i ++) {
                        str += ','+json[i];
                    }

                str = str.substr(1);

                location.href="__URL__/resourcePublish/ids/"+str;

            } else {
                showMessage('上传文件失败');
            }

        }, 'json');
    }

    // 初始化多文件上传
    function reloadFileUpload(){
        var maxSize = <?php echo ($maxSize); ?>;
        $("#uploader").pluploadQueue({
            // General settings
            runtimes : 'html4,html5,flash,silverlight,gears,browserplus',
            url : '__URL__/uploadAttach',
            max_file_size : maxSize+'mb',
            chunk_size : '10mb',
            unique_names : true,

            // Resize images on clientside if we can
            resize : {width : 320, height : 240, quality : 90},
            dragdrop : true,
            // Specify what files to browse for
            filters : [
                {title : "Image files", extensions : "png,jpg,gif,bmp,jpeg"},
                {title : "Zip files", extensions : "zip,rar"},
                {title : "Audio files", extensions : "mp3,m4a,m4v"},
                {title : "Mindmark files", extensions : "db"},
                {title : "Video files", extensions : "mpeg,mp4,avi,rmvb,rm,wmv,fla,3gp,flv"},
                {title : "Docs files", extensions : "txt,doc,xls,ppt,docx,xlsx,pptx,pdf"}
            ],

            // Flash settings
            flash_swf_url : '/Public/Js/Public/plupload/plupload.flash.swf',

            // Silverlight settings
            silverlight_xap_url : '/Public/Js/Public/plupload/plupload.silverlight.xap',
            init: {
                FileUploaded: function(up, file, info) {
                    var reg = /error(.*)<\/p>/ig;
                    var res = info.response.match(reg);
                    if (res) {
                        var str = res.toString();
                        alert(str.slice(7,-4));
                        location.reload(true);
                    }
                }
            }
        });
    }

//-->
</script>
<div class="warp">
    <div id="left_sider">
    <div class="main_user">
        <a href="#"><img src="<?php echo (getauthavatar($authInfo["a_avatar"],$authInfo['a_type'],$authInfo['a_sex'],96)); ?>"/></a>
        <div class="info">
            <a href="__APPURL__/Auth/index" class="name fl" title="<?php echo ($authInfo["a_nickname"]); ?>"><?php echo ($authInfo["a_nickname"]); ?></a>
            <a href="__APPURL__/Auth/index" class="university fl"><?php echo ($school); ?></a>
            <a href="__APPURL__/Auth/index" class="mody_data fl"><span>修改资料</span></a>
        </div>
    </div>

    <div class="main_app">
        <p class="title">
            <cite></cite>
            <span>我的应用</span>
        </p>
       <ul>
            <?php if(is_array($myapps)): $i = 0; $__LIST__ = $myapps;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$myapp): $mod = ($i % 2 );++$i; if(($myapp["title"]) != ""): ?><li><a href="<?php echo ($myapp["url"]); ?>"><?php echo ($myapp["title"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            <!-- <li><a id="add_app">添加</a></li> -->
        </ul>
    </div>

    <?php if(!empty($crowds)): ?><div class="main_group">
            <p class="title">
                <cite></cite>
                <span>我的群组</span>
            </p>

            <?php if(is_array($crowds)): $i = 0; $__LIST__ = $crowds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$crow): $mod = ($i % 2 );++$i;?><ul class="group">
                    <li>
                        <a href='javascript:void(0);'><img src="__APPURL__/Images/Tmp/groupAvatar.png"></a>
                        <a href="javascript:void(0);" class="gname"><?php echo ($crow["cro_title"]); ?></a>
                        <a href="javascript:void(0);" class="gtag">创建时间：<?php echo (date("Y-m-d",$crow["cro_created"])); ?></a>
                    </li>
                </ul><?php endforeach; endif; else: echo "" ;endif; ?>

            <a class="addGroup" href="Crowd/"></a>
            <span class="clear"></span>
        </div><?php endif; ?>
</div>
        <div class="res_upload">
            <ul class="top">
                <li class="title fl">上传资源</li>
            </ul>
            <div class="upload_box">
                <p class="tit">资源选择</p>
                <div class="upload_top">
                    <div id="uploader">
                        <p>上传资源控件加载错误，可能是您的浏览器不支持 Flash, Silverlight, Gears, BrowserPlus 或 HTML5，请检查</p>
                    </div>
                    <div class="warn_word">
                        资源上传须知：<br/>
                        1.请不要上传侵犯他人版权的文件<br/>
                        2.每份文档不超过<?php echo ($maxSize); ?>M
                    </div>
                    <div class="clear"></div>
                    <!--div class="tools">
                        <span class="save_to_wp">保存到我的网盘</span>
                        <span class="save_to_library">保存并发布到资源库</span>
                        <cite class="">*为必填项</cite>
                    </div-->
                    <div class="finish">
                        <button class="finish_btn">完成</button>
                    </div>
                </div>
                <div class="warn_file">
                    <div class="file_left">
                        <div class="file_top">
                            <span>多文件上传</span>
                            <span class="file_add">添加列表</span>
                        </div>
                        <ul class="file_bottom">
                            <li>
                                <label>上传资源</label>
                                <input type="file" name="a_avatar"/>
                                <span>删除</span>
                            </li>
                            <li>
                                <label>上传资源</label>
                                <input type="file" name="a_avatar"/>
                                <span>删除</span>
                            </li>
                            <li>
                                <label>上传资源</label>
                                <input type="file" name="a_avatar"/>
                                <span>删除</span>
                            </li>
                            <li>
                                <label>上传资源</label>
                                <input type="file" name="a_avatar"/>
                                <span>删除</span>
                            </li>
                            <li>
                                <label>上传资源</label>
                                <input type="file" name="a_avatar"/>
                                <span>删除</span>
                            </li>
                            <li>
                                <label>上传资源</label>
                                <input type="file" name="a_avatar"/>
                                <span>删除</span>
                            </li>
                        </ul>
                    </div>
                    <div class="warn_word fr">
                        资源上传须知：<br/>
                        1.请不要上传侵犯他人版权的文件<br/>
                        2.每份文档不超过<?php echo ($maxSize); ?>M
                    </div>
                    <div class="clear"></div>
                    <div class="file_but">
                        <button class="finish_btn">完成</button>
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