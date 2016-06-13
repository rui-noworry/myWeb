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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/myres.css" />
<script type="text/javascript">
<!--
    $(function() {

        // 资源模块切换
        $(".res_tab li").click(function(){

            $(this).addClass("current").siblings().removeClass("current");
            var index =  $(".res_tab li").index(this);

            if (index == 2 || index == 3) {
                var check = $(".res_box .mouldBox").eq(index).find('li.listli').size() > 0 ? 0 : 1;
                $('.order_switch a').eq(check).addClass('on').siblings().removeClass('on');
                $(".res_box .mouldBox").eq(index).show().siblings().hide();
                getList(0);
                var check = $(".res_box .mouldBox").eq(index).find('li.listli').size() > 0 ? 0 : 1;
                $('.order_switch a').eq(check).addClass('on').siblings().removeClass('on');
                $(".res_box .mouldBox").eq(index).show().siblings().hide();
            } else {
                if ($(".res_box .mouldBox").eq(index).find('li').size() < 1 && $(".res_box .mouldBox").eq(index).find('.noData').size() < 1) {
                    getList(0);
                } else {
                    var check = $(".res_box .mouldBox").eq(index).find('li.listli').size() > 0 ? 0 : 1;
                    $('.order_switch a').eq(check).addClass('on').siblings().removeClass('on');
                    $(".res_box .mouldBox").eq(index).show().siblings().hide();
                }
            }


        }).eq(0).click();

        // 鼠标滑过资源
        $(document).on('mouseenter','.res_cover',function() {
            $(this).parent().addClass('on');
        })
        $(document).on('mouseleave','.res_cover',function() {
            $(this).parent().removeClass('on');
        })

        // 鼠标点击选中资源（多选）
        $(document).on('click','.res_cover',function() {
            choose($(this));
        })

        // 鼠标点击选中资源（单选）
        $(document).on('click','.two .res_cover',function() {

            if($(this).parent().hasClass('click')) {
                $(this).parent().addClass('click').siblings().removeClass('click');
            }
            var chooseNum =$(this).parents('.mould').find('li.click').size();
            $(this).parents('.mould').find('.tools i').html(chooseNum);

            // 有选中资源则显示资源操作按钮
            if (chooseNum > 0) {
                $(this).parents('.mould').find('.tools p').show();
            } else {
                $(this).parents('.mould').find('.tools p').hide();
            }
        })

        // 选中复选框
        $(document).on('click', '.ck', function() {
            choose($(this));
        })

        // 批量删除资源
        $(document).on('click','.tools .del',function() {

            if (confirm("您确定删除所选资源吗？")) {

                var str = getChoosed(0);
                if (!str) {
                    return false;
                }

                $.post('__URL__/del', 'id='+str, function(data) {
                    if (data != 0) {

                        $('.mouldBox').eq(0).find('li.click').remove();
                        $('.mouldBox').eq(0).find('.tools i').html(0);

                    } else {
                        showMessage('操作失败');
                    }
                }, 'json')
            }
        })

        // 编辑资源
        $(document).on('click','.tools .edit',function() {

                var str = getChoosed(0);
                if (!str || str != parseInt(str)) {
                    showMessage('多个资源不可同时编辑');
                    return false;
                }
                var id = 0;
                if (str.indexOf(',') == -1) {
                    id = str;
                } else {
                    id = str.substr(0, str.indexOf(','));
                }

                location.href="__APPURL__/AuthResource/edit/id/"+id;

        })

        // 发布
        $(document).on('click','.tools .publish',function() {

            if (confirm("现在发布所选资源吗？")) {

                var str = getChoosed(0);
                if (!str) {
                    return false;
                }

                location.href = '__APPURL__/AuthResource/resourcePublish/dis/1/ids/'+str;
            }
        })

        // 下载
        $(document).on('click','.tools .download',function() {

            var index = $(this).parents('.mouldBox').index();

            var str = getChoosed(index);
            if (!str || str != parseInt(str)) {
                showMessage('多个资源不可同时下载');
                return false;
            }

            if (index == 0) {
                location.href = '__APPURL__/AuthResource/download/id/'+str;
            } else {
                location.href = '__APPURL__/MyResource/download/id/'+str;
            }
        })

        // 分享
        $(document).on('click', '.share', function () {

            if ($('.mould ul li.click').size() == 0) {
                showMessage('请选择要分享的资源');
                return false;
            }

            // 复原
            $('.coType span').eq(0).click();
            $('.select_right ul').html('');
            $('.num_remove span').removeClass('xin_ds');
            $('.num_remove ul').hide();

            // 弹出分享窗口
            $('.select_add').dialog("open");
        });

        // 群组弹窗
        $(".select_add").dialog({
            draggable: true,        // 是否允许拖动,默认为 true
            resizable: true,        // 是否可以调整对话框的大小,默认为 true
            autoOpen: false,        // 初始化之后,是否立即显示对话框,默认为 true
            position :'center',       // 用来设置对话框的位置
            stack : true,       // 对话框是否叠在其他对话框之上。默认为 true
            modal: true,       // 是否模式对话框,默认为 false(模式窗口打开后，页面其他元素将不能点击，直到关闭模式窗口)
            bgiframe: true,         // 在IE6下,让后面遮罩层盖住select
            width: '680',
            height: 'auto',

            show: {     // 对话框打开效果
                effect: "blind",
                duration: 400
            },
            hide: {     // //对话框关闭效果
              effect: "explode",
              duration: 400
            },
            overlay: {
                backgroundColor: '#000',
                opacity: 0.5
            },
            buttons: {
                确定: function() {

                    // 群组成员数量
                    var number = $('.select_right ul').children().size();

                    if(number <= 0){
                        showMessage('请添加成员');
                        return false;
                    }

                    var _this = $(this);

                    // 被分享的用户ID
                    var aid = returnAid().slice(1, -1);

                    // 被分享的资源ID
                    var resId = [];
                    $('.mould ul li.click').each(function (i) {
                        resId[i] = $(this).attr('attr');
                    })
                    resId = resId.join(',');

                    // 异步添加组以及成员
                    $.post('__APPURL__/ResourceShare/insert', {a_id: aid, ar_id: resId}, function (json) {

                        // 把返回的群组id赋值给rel属性
                        if (json['status'] == 1) {
                            showMessage(json['info'], 1);
                            _this.dialog('close');
                        } else {
                            showMessage(json['info']);
                        }
                    });

                },
                取消: function() {
                    $(this).dialog('close');
                }
            }
        });

        // 全选
        $(document).on('click', 'input[name=checkall]', function(){
            var obj = $(this).parents('.mould');

            if (this.checked) {
                obj.find('li').addClass('click');
                obj.find('.ck').prop('checked', true);
                obj.find('.tools i').html(obj.find('li').size());
                obj.find('.tools p').show();
            } else {
                obj.find('li').removeClass('click');
                obj.find('.ck').prop('checked', false);
                obj.find('.tools i').html(0);
                obj.find('.tools p').hide();
            }
        })

        // 切换显示方式
        $(document).on('click', '.order_switch a', function() {
            $(this).addClass('on').siblings().removeClass('on');
            var index = $('.res_tab li.current').index() - 1;

            showStyle($(this), index);
        })

        // 选中
        $(document).on('click', '.search_box span', function(){

            $(this).addClass('on').siblings().removeClass('on');
        })

        // 显示高级搜索
        $('.common_search .gj').click(function(){

            $('.high_search li').each(function() {
                $(this).find('span').eq(0).click();
            })

            $('.ks').toggle();
            $('.high_search').slideToggle();

        })

        // 高级搜索 点击取消 收起
        $('.cancel').click(function(){

            $(this).parent().parent().slideUp();
            $('.ks').show();
        })

        $('.ks').click(function() {
            search();
        })

        $('.confirm').click(function(){
            search();
        })

        // 前端新建群组切换
        $(document).on('click','.coType span',function(){
            $(this).addClass('xin_ds').siblings().removeClass('xin_ds');
            $('.select_num').children('div').eq($(this).index()).show().siblings('div').hide();
        })

        // 点击班级获取群组，异步加载班其下的成员
        $(document).on('click','.num_remove span',function(){

            var _this = $(this);
            if (_this.hasClass('xin_ds')) {
                _this.removeClass('xin_ds');
                _this.next().find('ul').hide();
            } else {
                $('.num_remove span').removeClass('xin_ds');
                $('.num_remove ul').hide();
                var para;

                // 班级
                if ($('.coType .xin_ds').index() == 0) {
                    para = 'c_id=' + _this.attr('rel');
                }

                // 群组
                if ($('.coType .xin_ds').index() == 1) {
                    para = 'cro_id=' + _this.attr('rel');
                }

                $.post('__URL__/searchMember', para, function (json) {
                    var htm = '';
                    var aId = ',' + returnAid() + ',';
                    var tmp = '';
                    if (json) {
                        for (var i=0, len=json.length; i<len; i++) {
                            tmp = ',' + json[i]['a_id'] +','
                            htm += '<li rel="' + json[i]['a_id'] + '" class="' + (aId.indexOf(tmp) != -1 ? 'xin_reli' : '') + '">'
                            + json[i]['a_nickname'] +  '</li>'
                        }
                    }
                    _this.next().find('ul').html(htm);
                    _this.addClass('xin_ds');
                    _this.next().find('ul').show();
                }, 'json') ;
            }
        })

        // 班级成员点击
        $(document).on('click','.num_remove ul li',function(){
            if(!$(this).hasClass('xin_reli')){
                if($(this).hasClass('xin_relian')){
                    $(this).removeClass('xin_relian');
                }else{
                    $(this).addClass('xin_relian');
                }
            }
        })

        // 新组成员添加
        $(document).on('click','.select_cen a',function(){
            $('.num_remove ul li').each(function(){
                if($(this).hasClass('xin_relian')){
                    var tText = $(this).text();
                    $('.select_right ul').append("<li rel='"+$(this).attr('rel')+"'>"+tText+"<a href='#' class='flex_close cDel'></a></li>");
                    $(this).removeClass('xin_relian').addClass('xin_reli');
                }
            })
        })

        // 新组成员删除
        $(document).on('click','.select_right ul .flex_close',function(){
            if(confirm('你确定要删除吗')){
                var oVal = $(this).parent().text();
                $('.num_remove li').each(function(){
                    if($(this).text() == oVal){
                        $(this).removeClass('xin_reli')
                    }
                })
                $(this).parent().remove();
            } else {
                return false;
            }
        })

        // 新增群组成员 滑过
        $(document).on('mouseover','.select_right ul li',function(){
            $(this).addClass('on').children('a').show();
        })

        // 新增群组成员 滑离
        $(document).on('mouseout','.select_right ul li',function(){
            $(this).removeClass('on').children('a').hide();
        })

        // 取消分享
        $(document).on('click', '.cancel_share', function () {
            if (confirm('确定要取消分享该资源么？')) {
                var ar_id = [];
                $('.mould ul li.click').each(function (i) {
                    ar_id[i] = $(this).attr('attr');
                })
                ar_id = ar_id.join(',');
                $.post('__APPURL__/ResourceShare/delete', {ar_id:ar_id}, function (json) {
                    if (json.status == 1) {
                        $('.mould ul li.click').each(function (i) {
                            $(this).remove();
                        })
                    }
                }, 'json')
            }
        });

        // 删除给我的分享资源
        $(document).on('click', '.shareDel', function () {
            if (confirm('确定要删除该分享资源么？')) {
                var ar_id = [];
                $('.mould ul li.click').each(function (i) {
                    ar_id[i] = $(this).attr('attr');
                })
                ar_id = ar_id.join(',');
                $.post('__APPURL__/ResourceShare/delete', {ar_id:ar_id, flag:true}, function (json) {
                    if (json.status == 1) {
                        $('.mould ul li.click').each(function (i) {
                            $(this).remove();
                        })
                    }
                }, 'json')
            }
        });
    })

    // 返回右侧选中的学生ID
    function returnAid() {
        var a_id = ',';
        $('.select_right ul li').each(function () {
            a_id +=  $(this).attr('rel') + ',';
        })
        return a_id;
    }

    function getChoosed(num) {
        var str = '';
        $('.mouldBox').eq(num).find('li.click').each(function(){
             str += ',' + $(this).attr('attr');
        })

        return str.slice(1);
    }

    function choose(obj) {
        if (obj.parent().hasClass('click')) {
            obj.parent().removeClass('click');
        } else {
            obj.parent().addClass('click');
        }

        var chooseNum = obj.parents('.mould').find('li.click').size();
        obj.parents('.mould').find('.tools i').html(chooseNum);

        //有选中资源则显示资源操作按钮
        if (chooseNum > 0) {
            obj.parents('.mould').find('.tools p').show();
        } else {
            obj.parents('.mould').find('.tools p').hide();
        }
    }

    function getList(p) {
        p = parseInt(p);

        var index = $('.res_tab li.current').index() - 1;
        $.post('__URL__/lists', 'type='+ index + '&p=' + p, function(json) {
            var str = '';
            var page = '';
            $('.mouldBox').eq(index).find('page').html('');
            $('.mouldBox').eq(index).find('ul').html('');

            if (json.list) {

                var obj = json.list;
                if (index == 1) {
                    for (var i = 0; i < obj.length; i ++) {
                        str += '<li attr="'+obj[i]['re_id']+'"><input type="checkbox" class="ck" name="list_check"><a class="res_cover" href="javascript:void(0)"><img class="old_frame" src="'+obj[i]['re_img']+'" width="100" height="75"/><img class="res_scan hide" src="__APPURL__/Public/Images/Home/res_scan.png"><img class="res_click hide" src="__APPURL__/Public/Images/Home/res_click.png"></a>'
                        if (obj[i]['re_is_pass'] == 1) {
                            if (obj[i]['re_is_transform'] == 1) {
                                str += '<a class="res_title" target="_blank" href="__APPURL__/Resource/index/id/'+obj[i]['re_id'];
                            } else {
                                str += '<a class="res_title" href="javascript:showMessage(\'审核处理中\');';
                            }
                        } else {
                            str += '<a class="res_title" href="javascript:showMessage(\'审核中\');';
                        }
                        str += '" title="'+obj[i]['re_title']+'">'+obj[i]['re_title']+'</a><span>'+obj[i]['a_nickname']+'</span><span><i>'+obj[i]['re_download_points']+'</i>积分</span><span class="time">'+obj[i]['time']+'</span></li>';
                    }
                } else {
                    for (var i = 0; i < obj.length; i ++) {
                        str += '<li attr="'+obj[i]['ar_id']+'"><input type="checkbox" class="ck" name="list_check"><a class="res_cover" href="javascript:void(0)"><img class="old_frame" src="'+obj[i]['re_img']+'" width="100" height="75"/><img class="res_scan hide" src="__APPURL__/Public/Images/Home/res_scan.png"><img class="res_click hide" src="__APPURL__/Public/Images/Home/res_click.png"></a><a class="res_title" target="_blank" href="__APPURL__/AuthResource/edit/id/'+obj[i]['ar_id']+'" title="'+obj[i]['ar_title']+'">'+obj[i]['ar_title']+'</a><span class="time">'+obj[i]['time']+'</span></li>';
                    }
                }
                page = json.page;
                if (index == 2 && $('.mouldBox').eq(index).html().indexOf('<p class="noData">暂无资源</p>') != -1) {
                    var htm ='<div class="mould">'
                             +'<div class="tools">'
                             +'<a class="choose"><input type="checkbox" name="checkall"><span>已选中<i>0</i>个文件</span></a>'
                             +'<p class="hide">'
                             +'<a class="cancel_share"></a>'
                             +'</p>'
                             +'</div>'
                             +'<ul></ul>'
                             +'</div>'
                             +'<div class="page"></div>'
                    $('.three').html(htm);
                    $('.three').eq(index).find('.page').html(page);
                    $('.three').eq(index).find('ul').html(str);
                } else if (index == 3) {
                    var htm ='<div class="mould">'
                             +'<div class="tools">'
                             +'<a class="choose"><input type="checkbox" name="checkall"><span>已选中<i>0</i>个文件</span></a>'
                             +'<p class="hide">'
                             +'<a class="del shareDel"></a>'
                             +'</p>'
                             +'</div>'
                             +'<ul></ul>'
                             +'</div>'
                             +'<div class="page"></div>'
                    $('.four').html(htm);
                    $('.four').eq(index).find('.page').html(page);
                    $('.four').eq(index).find('ul').html(str);
                }
                $('.mouldBox').eq(index).find('.page').html(page);
                $('.mouldBox').eq(index).find('ul').html(str);
            } else {
                str = '<div class="mould"><p class="noData">暂无资源</p></div>';
                $('.mouldBox').eq(index).html(str);
            }

            showStyle($('.order_switch a.on'), index);

            $(".res_box .mouldBox").eq(index).show().siblings().hide();
        }, 'json');
    }

    // 切换显示模式
    function showStyle(type, index) {

        var obj = $('.mouldBox').eq(index).find('li');
        obj.each(function() {
            $(this).find('.ck').prop('checked', $(this).hasClass('click'));
        })

        if (type.index()) {
            obj.removeClass('listli');
        } else {
            obj.addClass('listli');
        }
    }

    function search() {
        var keywords = $('input[name=keywords]').val();
        var resFrom = $('.resFrom span.on').attr('attr');
        var resType = $('.resType span.on').attr('attr');

        var str = '';

        if (keywords) {
            str += 'keywords/' + keywords + '/';
        }

        if (resFrom > 0) {
            str += 'resFrom/' + resFrom + '/';
        }

        if (resType > 0) {
            str += 'resType/' + resType + '/';
        }

        str = str.slice(0, -1);

        if (!str) {
            return false;
        }

        location.href="__APPURL__/SchoolSearch/index/" + str;
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
        <div class="myres_right">
        <!-- 搜索开始 -->
        <div class="search_box">
            <div class="common_search">
                <input type="text" value="<?php echo ($keywords); ?>" name="keywords" placeHolder="资源名称">
                <a class="gj"></a>
                <a class="ks"></a>
            </div>
            <div class="high_search hide">
                <ul>
                    <!--li class="resFrom">
                        <label>来源</label>
                        <div>
                            <span class="hand on" attr="1">系统</span>
                            <span class="hand" attr="2">数字学校</span>
                        </div>
                    </li-->
                    <li class="resType">
                        <label>资源类型</label>
                        <div>
                            <span class="hand on" attr="0">全部</span>
                            <?php if(is_array($model)): $i = 0; $__LIST__ = $model;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$model): $mod = ($i % 2 );++$i;?><span class="hand" attr="<?php echo ($model["m_id"]); ?>"><?php echo ($model["m_title"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </li>
                </ul>
                <div class="btn">
                    <a class="confirm">确定</a>
                    <a class="cancel">取消</a>
                </div>
            </div>
        </div>
        <!-- 搜索结束 -->
        <!-- 资源列表开始 -->
        <div class="res_list fl">
            <ul class="res_tab">
                <p class="order_switch">
                    <a href="#" class="list"></a>
                    <a href="#" class="thumb on"></a>
                </p>
                <li class="current">我的网盘</li>
                <li>我发布的资源</li>
                <li>我分享的资源</li>
                <li>分享给我的资源</li>
            </ul>
            <!-------------------------------------表格显示开始------------------------------------------->
            <div class="res_box fl">
                <!-- 我的网盘开始 -->
                <div class="mouldBox one fl">
                    <div class="mould">
                        <div class="tools">
                            <a class="choose"><input type="checkbox" name="checkall"><span>已选中<i>0</i>个文件</span></a>
                            <p class="hide">
                                <a class="edit"></a>
                                <a class="publish"></a>
                                <a class="download"></a>
                                <a class="share"></a>
                                <a class="del"></a>
                            </p>
                        </div>
                        <ul>
                        </ul>
                    </div>
                    <div class="page"></div>
                </div>
                <!-- 我的网盘结束 -->
                <!-- 我发布的资源开始 -->
                <div class="mouldBox two fl hide">
                    <div class="mould">
                        <div class="tools">
                            <a class="choose"><input type="checkbox" name="checkall"><span>已选中<i>0</i>个文件</span></a>
                            <p class="hide">
                                <a class="download"></a>
                            </p>
                        </div>
                        <ul>
                        </ul>
                    </div>
                    <div class="page"></div>
                </div>
                <!-- 我发布的资源结束 -->

                <!-- 我分享的资源开始 -->
                <div class="mouldBox three fl hide">
                    <div class="mould">
                        <div class="tools">
                            <a class="choose"><input type="checkbox" name="checkall"><span>已选中<i>0</i>个文件</span></a>
                            <p class="hide">
                                <!--a class="share_set"></a-->
                                <a class="cancel_share"></a>
                            </p>
                        </div>
                        <ul>
                        </ul>
                    </div>
                    <div class="page">
                    </div>
                </div>
                <!-- 我分享的资源结束 -->

                <!-- 分享我的资源开始 -->
                <div class="mouldBox four fl hide">
                    <div class="mould">
                    </div>
                </div>
                <!-- 分享我的资源结束 -->
            </div>
            <!-------------------------------------表格显示结束------------------------------------------->
        </div>
        <!-- 资源列表结束 -->
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
<div class="select_add hide" title="新增群组">
    <div class="select_left fl">
        <ul class="sel_ul">
            <li>
                <label>分类：</label>
                <div class="coType">
                    <span class="xin_ds">我的班级</span>
                    <span>我的群组</span>
                </div>
            </li>
            <li class="coGrade"></li>
        </ul>
        <div class="select_num fl">
            <label>成员：</label>
            <div class="num_remove">
                <?php if(is_array($class)): $i = 0; $__LIST__ = $class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="aaa">
                    <span rel="<?php echo ($vo["c_id"]); ?>"><?php echo ($vo["c_name"]); ?></span>
                    <div>
                        <ul>
                        </ul>
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="num_remove fl" style="display:none">
                <?php if(is_array($crowd)): $i = 0; $__LIST__ = $crowd;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="aaa">
                    <span rel="<?php echo ($vo["cro_id"]); ?>"><?php echo ($vo["cro_title"]); ?></span>
                    <div>
                        <ul>
                        </ul>
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
    <div class="select_cen">
        <a title='添加'>>></a>
    </div>
    <div class="select_right fr">
        <label>成员：</label>
        <ul></ul>
    </div>
    <div class="clear"></div>
</div>