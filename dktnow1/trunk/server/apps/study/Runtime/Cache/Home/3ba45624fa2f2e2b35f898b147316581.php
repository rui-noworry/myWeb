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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/jquery-ui.css" />
<script type="text/javascript" src="/Public/Js/Home/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/hour.css" />

<!--多文件上传plupload插件开始-->
<link rel="stylesheet" href="/Public/Js/Public/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css">
<script type="text/javascript" src="/Public/Js/Public/plupload/plupload.full.js"></script>
<script type="text/javascript" src="/Public/Js/Public/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="/Public/Js/Public/plupload/i18n/zh-cn.js"></script>
<!--多文件上传plupload插件结束-->

<script type="text/javascript" language="javascript">


    // 决定弹窗类型
    var dialogNum = 0;

    // 这里是为了页面默认加载处于最下方的单元、课文、课时
    var onloadNum = 0;

    $(function () {

        var inputCid = $('input[name=c_id]').val();
        var inputCroid = $('input[name=cro_id]').val();

        // 作业多文件上传
        reloadFileUpload();

        if (inputCid != 0 || inputCroid != 0) {
            $('.add_tache').hide();
        }

        // 页面加载时，隐藏中间模块的添加资源、添加活动和发布、网格
        $('.tache_box').hide();
        $('.add_res').hide();
        $('.order_switch').hide();
        $('.main_top:eq(1)').hide();

        // 导入课文
        $(document).on('click', '.lesson_lead', function () {

            // 先从DOM节点判断
            if ($('.tree li').size() > 0) {
                showMessage('请先清空课文');
            }

            $.post('__URL__/import', 'co_id=' + $('input[name=co_id]').val(), function (json) {
                if (json.status == 1) {
                    location.reload(true);
                } else {
                    showMessage(json.info);
                }
            }, 'json');
        })

        // 分页加载课时资源
        $(document).on('click', '.resourcePage', function () {

            // 重新加载课时分页
            getPage(1);
        })

        // 下载课时资源
        $(document).on('click', '.res_dw', function () {
            location.href = "__APPURL__/Activity/download/?id=" + $(this).parents('.res_cover').attr('rel');
        })

        // 浏览课时资源
        $(document).on('click', '.scan', function () {
            var obj = $(this).parents('.res_cover');
            var id = obj.attr('rel');
            if (obj.attr('trans') != 1) {
                showMessage('该附件未转码，暂时不能预览');
                return;
            } else {
                $(this).attr({'target':'_blank', 'href':'__APPURL__/AuthResource/show/ar_id/' + id});
            }
        })

        // 删除课时资源
        $(document).on('click', '.res_del', function () {
            if (confirm('确定要删除该课时资源么?')) {
                var obj = $(this).parents('.res_cover');
                var id = obj.attr('rel');

                $.post('__APPURL__/Classhour/syncMoveFile', {cl_id: $('input[name=cl_id]').val(), ar_id: id}, function (json) {
                    obj.parent().remove();
                }, 'json');
            }
        })

        // 子页面点击从资源库中检索时，单机该样式，异步加载数据
        $(document).on('click', '.title', function () {

            // 如果没有iframe打开，则不允许打开窗口
            if ($('.act_box[rel=1] iframe').css('display') == undefined || $('.act_box[rel=1] iframe').css('display') == 'none') {
                return;
            }
            $('input[name=resTitleFlag]').val(0);
            // 如果没有iframe里触发相关的事件，则也不允许打开窗口
            if (dialogNum == 1) {
                getSearchResult();
                $('#reslibrary').dialog("open");
            } else if (dialogNum == 2) {
                getSearchResult();
                $('#read_reslibrary').dialog("open");
            }

        })

        // 资源库检索窗口
        $("#read_reslibrary").dialog({
            draggable: true,
            resizable: true,
            autoOpen: false,
            position :'center',
            stack : true,
            modal: true,
            bgiframe: true,
            width: '780',
            height: '545',

            show: {     // 对话框打开效果
                effect: "blind",
                duration: 500
            },
            hide: {     // 对话框关闭效果
              effect: "explode",
              duration: 500
            },
            overlay: {
                backgroundColor: '#000',
                opacity: 0.5
            },
            buttons: {
                确定: function() {

                    // 追加从资源库中选择的资源
                    var chooseRes = '';

                    $('.resource li').each(function(){

                        if ($(this).hasClass('click')) {

                            chooseRes += '<li attr="'+$(this).attr('attr')+'" class="ListFiles" trans="' + $(this).attr('trans') + '"><a class="res_li" href="javascript:void(0)"><img src="'+$(this).find('img:eq(0)').attr('src')+'" width="100" height="75"><a class="del_res" style="display: none;"></a></a><a class="res_title" href="javascript:void(0)" title="'+$(this).find('a.res_title').attr('title')+'">'+$(this).find('a.res_title').attr('title')+'</a></li>';

                        }
                    });

                    if (chooseRes) {
                        $('.act_box[rel=1] iframe').contents().find('.res_list ul').append(chooseRes);
                    }

                    $(this).dialog('close');
                    $('.act_box[rel=1] iframe').contents().find('.res_list').show();
                    $('.act_box[rel=1] iframe').contents().find('.finishBtn').show();

                },
                取消: function() {

                    $(this).dialog('close');
                }
            }
        });

        // 资源库检索题目窗口
        $("#reslibrary").dialog({
            draggable: true,
            resizable: true,
            autoOpen: false,
            position: 'center',
            stack: true,
            modal: true,
            bgiframe: true,
            width: '800',
            height: '570',
            show: {
                effect: "blind",
                duration: 400
            },
            hide: {
                effect: "explode",
                duration: 400
            },
            overlay: {
                backgroundColor: '#000',
                opacity: 0.5
            },
            buttons: {
                确定: function() {

                    // 在点击添加之前，存储已经添加好的题目ID
                    var toId = '';
                    var addTopic = $('#actIframe').contents().find('.addTopic_list li');
                    if (addTopic.size() > 0) {
                        addTopic.each(function () {
                            toId += ',' + $(this).attr('rel');
                        })
                        toId = toId + ',';
                    }

                    var topics = '';
                    var operate = "<p class='operate'><label class='lc'>操作：</label><label style='cursor: pointer' class='com editTopic'>编辑</label><label style='cursor: pointer' class='delTopic'>删除</label></p>";

                    // 获取题目内容前，要对比下该题目的ID是否在已添加的题目列表里，在就不用添加
                    $('.topicOption p span.selected').each(function() {
                        var tmp = ',' + $(this).parent().parent().attr('rel') + ',';
                        if (toId.indexOf(tmp) == -1) {
                            var title = $(this).parent().siblings().find('.ttitle').text();
                            var content = $(this).parent().siblings().find('.tcontent').text();
                            var answer = $(this).parent().parent().attr('attr') == 3 ? '<ul class="dalist1">' + $(this).parent().siblings().find('.tanswer').html() + '</ul>' : $(this).parent().siblings().find('.tanswer').html();
                            topics += "<li rel='" + $(this).parent().parent().attr('rel') + "' attr='" + $(this).parent().parent().attr('attr') + "'><div class='ttitle'>" + title + "</div><div class='tcontent'>" + content + "</div><div class='tanswer'>" + answer + "</div>" + operate + "</li>";
                        }
                    })

                    // 放入子窗口中
                    $('.act_box[rel=1] iframe').contents().find('.addedTopic_box .addTopic_list').append(topics);

                    // 关闭窗口
                    $(this).dialog('close');
                },
                取消: function() {

                    $(this).dialog('close');
                }
            }
        })

        // 点击资源库检索窗口中的编辑按钮
        $(document).on('click', '.editResource', function () {

            // 关闭资源库检索窗口
            $("#reslibrary").dialog('close');

            // 调用子窗口中的topicEdit方法
            $('.act_box[rel=1] iframe')[0].contentWindow.topicEdit($(this));
        })

        // 添加单元
        $('.add_unit').click(function(){

            var unitNum = $('.tree > li').size() + 1;
            var context = "单元" + unitNum;
            $.post('__URL__/insert', 'co_id=' + $('input[name=co_id]').val() + '&l_pid=0&l_title=' + context, function (json) {
                var htm = '';
                if (json) {
                    htm += "<li rel='" + json.info + "'>"
                          +"<span class='tree_switch tree_switch_plus unit_switch'></span>"
                          +"<a class='tree_unit_a parent_node_a' rel='" + json.info + "'>"
                          +"<span class='tree_branch' rel='" + json.info + "' title='" + context + "'>" + context + "</span>"
                          +"<span class='button add'></span>"
                          +"<span class='button edit'></span>"
                          +"<span class='button del'></span>"
                          +"</a>"
                          +"<ul class='ctree ctree_drag ui-sortable'></ul>"
                          +"</li>"
                }
                $('.tree').append(htm);
            }, 'json')
        })

        // 添加课文
        $(document).on('click','.add',function(){

            var _this = $(this);
            var kwNum = _this.parent().siblings('.ctree').children().size() + 1;
            var context = "课文" + kwNum;

            // 判断是否异步加载
            if (_this.parent().prev().hasClass('tree_switch_plus') && _this.parent().siblings('.ctree').children().size() == 0) {
                $('input[name=l_title]').val(context);
                _this.prev().click();
            } else {
                $.post('__URL__/insert', 'co_id=' + $('input[name=co_id]').val() + '&l_pid=' + _this.prev().attr('rel') + '&l_title=' + context + '&l_sort=0', function (json) {
                    var htm = '';
                    if (json) {
                        htm += "<li rel='" + json.info + "'>"
                            +"<span class='tree_switch tree_switch_plus kw_switch'></span>"
                            +"<a class='tree_unit_a child_node_a' rel='" + json.info + "'>"
                            +"<span class='tree_branch child_node_span' rel='" + json.info + "' title='" + context + "'>" + context + "</span>"
                            +"<span class='button add_c'></span>"
                            +"<span class='button edit_c'></span>"
                            +"<span class='button del_c'></span>"
                            +"</a>"
                            +"<ul class='stree stree_drag ui-sortable'></ul>"
                            +"</li>"
                    }
                    _this.parent().siblings('.ctree').append(htm);

                    // 子节点区域展开
                    _this.parent().prev().removeClass('tree_switch_plus').addClass('tree_switch_sub');
                    _this.parent().siblings('.ctree').slideDown();
                }, 'json')
            }

            ctree(_this.parent().siblings('.ctree'));
        })

        // 添加课时
        $(document).on('click','.add_c',function(){

            var _this = $(this);
            var ksNum = _this.parent().siblings('.stree').children().size() + 1;
            var context = "第"+ksNum+"课时";

            // 判断是否异步加载
            if (_this.parent().prev().hasClass('tree_switch_plus') && _this.parent().siblings('.stree').children().size() == 0) {
                $('input[name=cl_title]').val(context);
                _this.prev().click();
            } else {
                $.post('__APPURL__/Classhour/insert', 'co_id=' + $('input[name=co_id]').val() + '&l_id=' + _this.prev().attr('rel') + '&cl_title=' + context, function (json) {
                    var htm = '';
                    if (json) {
                        htm += '<li rel="' + json.info + '" c_id="" cro_id="">'
                               +'<a class="tree_unit_a grandchild_node_a">'
                               +'<span class="tree_branch grandchild_node_span" rel="' + json.info + '" title="' + context + '">' + context + '</span>'
                               +'<span class="button edit_ks" style="display: none;"></span>'
                               +'<span class="button del_ks" style="display: none;"></span>'
                               +'</a>'
                               +'</li>'
                    }

                    // 子节点区域展开
                    _this.parent().prev().removeClass('tree_switch_plus').addClass('tree_switch_sub');
                    _this.parent().siblings('.stree').slideDown();

                    _this.parent().siblings('.stree').append(htm);
                }, 'json')
            }
            ctree(_this.parent().siblings('.stree'));
        })

        // 鼠标移到单元名称上 显示操作按钮（添加、编辑、删除）
        $(document).on('mouseover','.parent_node_a',function(){

            $(this).find('.add').show();
            $(this).find('.edit').show();
            $(this).find('.del').show();

        }).on('mouseout','.parent_node_a',function(){

            $(this).find('.add').hide();
            $(this).find('.edit').hide();
            $(this).find('.del').hide();
        })

        // 鼠标移到课文上 显示操作按钮（添加、编辑、删除）
        $(document).on('mouseover','.child_node_a',function(){

            $(this).find('.add_c').show();
            $(this).find('.edit_c').show();
            $(this).find('.del_c').show();

        }).on('mouseout','.child_node_a',function(){

            $(this).find('.add_c').hide();
            $(this).find('.edit_c').hide();
            $(this).find('.del_c').hide();
        })

        // 鼠标移动到课时上 显示操作按钮（编辑、删除）
        $(document).on('mouseover','.grandchild_node_a',function(){

            $(this).find('.edit_ks').show();
            $(this).find('.del_ks').show();

        }).on('mouseout','.grandchild_node_a',function(){

            $(this).find('.edit_ks').hide();
            $(this).find('.del_ks').hide();
        })

        // 单元展开与收缩
        $(document).on('click','.unit_switch',function(){

            var _this = $(this);
            if(_this.hasClass('tree_switch_plus')){
                _this.removeClass('tree_switch_plus').addClass('tree_switch_sub');
            } else {
                _this.removeClass('tree_switch_sub').addClass('tree_switch_plus');
            }

            if (_this.hasClass('tree_switch_sub')) {
                if (_this.next().next().find('li').size() == 0) {
                    $.post('__URL__/lists', 'co_id=' + $('input[name=co_id]').val() + '&l_id=' + _this.parent().attr('rel'), function (json) {
                        var htm = '';
                        if (json) {
                            for (var i=0, len=json.length; i<len; i++) {
                                htm += "<li rel='" + json[i]['l_id'] + "'>"
                                    +"<span class='tree_switch tree_switch_plus kw_switch'></span>"
                                    +"<a class='tree_unit_a child_node_a' rel='" + json[i]['l_id'] + "'>"
                                    +"<span class='tree_branch child_node_span' rel='" + json[i]['l_id'] + "' title='" + json[i]['l_title'] + "'>" + json[i]['l_title'] + "</span>"
                                    +"<span class='button add_c'></span>"
                                    +"<span class='button edit_c'></span>"
                                    +"<span class='button del_c'></span>"
                                    +"</a>"
                                    +"<ul class='stree stree_drag ui-sortable'></ul>"
                                    +"</li>"
                            }
                        }
                        _this.parent().children('.ctree').html(htm);
                        _this.siblings('.ctree').slideToggle();
                    }, 'json')
                }
                _this.siblings('.ctree').slideToggle();
            } else {
                _this.siblings('.ctree').slideToggle();
            }
        })

        // 课文展开与收缩
        $(document).on('click','.kw_switch',function(){

            var _this = $(this);

            if(_this.hasClass('tree_switch_plus')){
                _this.removeClass('tree_switch_plus').addClass('tree_switch_sub');
            } else {
                _this.removeClass('tree_switch_sub').addClass('tree_switch_plus');
            }

            if (_this.hasClass('tree_switch_sub')) {
                if (_this.next().next().find('li').size() == 0) {
                    $.post('__APPURL__/Classhour/lists', 'co_id=' + $('input[name=co_id]').val() + '&l_id=' + _this.parent().attr('rel'), function (json) {
                        var htm = '';
                        if (json) {
                            for (var i=0, len=json.length; i<len; i++) {
                                htm += '<li rel="' + json[i]['cl_id'] + '" c_id="' + json[i]['c_id'] + '" cro_id="' + json[i]['cro_id'] + '">'
                                    +'<a class="tree_unit_a grandchild_node_a">'
                                    +'<span class="tree_branch grandchild_node_span" title="' + json[i]['cl_title'] + '" rel="' + json[i]['cl_id'] + '" ar_id="' + json[i]['ar_id'] + '">' + json[i]['cl_title'] + '</span>'
                                    +'<span class="button edit_ks" style="display: none;"></span>'
                                    +'<span class="button del_ks" style="display: none;"></span>'
                                    +'</a>'
                                    +'</li>'
                            }
                        }
                        _this.parent().children('.stree').html(htm);
                        _this.siblings('.stree').slideToggle();
                        ctree(_this.siblings('.stree'))
                        // 这里需要做个判断，如果是页面一开始加载，同时此元素是最下方的单元
                        if (onloadNum == 2) {
                            if ($('.stree li').size()) {
                                $('.stree li').last().find('.grandchild_node_span').click();
                                onloadNum++;
                            }
                        }

                    }, 'json')
                }
                _this.siblings('.stree').slideToggle();
            } else {
                _this.siblings('.stree').slideToggle();
            }
            ctree(_this.siblings('.stree'))
        })

        // 点击单元题目 展开子节点
        $(document).on('click', '.tree_branch', function(){
            var _this = $(this);
            if (!_this.hasClass('child_node_span') && _this.html() != '<input class="edit_node" onkeydown="keydown(event)">') {
                if(_this.parent().prev().hasClass('tree_switch_plus')){
                    _this.parent().prev().removeClass('tree_switch_plus').addClass('tree_switch_sub');
                } else {
                    _this.parent().prev().removeClass('tree_switch_sub').addClass('tree_switch_plus');
                }

                if (_this.parent().prev().hasClass('tree_switch_sub')) {
                    if (_this.parent().next().find('li').size() < 1) {
                        syncLesson(_this);
                    }
                    _this.parent().siblings('.ctree').slideToggle();
                } else {
                    _this.parent().siblings('.ctree').slideToggle();
                }
            }
        })

        // 点击课文题目 展开课时
        $(document).on('click', '.child_node_span', function(){
            var _this = $(this);
            if (_this.html() != '<input class="edit_node" onkeydown="keydown(event)">') {
                if(_this.parent().prev().hasClass('tree_switch_plus')){
                    _this.parent().prev().addClass('tree_switch_sub').removeClass('tree_switch_plus');
                } else {
                    _this.parent().prev().removeClass('tree_switch_sub').addClass('tree_switch_plus');
                }

                if (_this.parent().prev().hasClass('tree_switch_sub')) {
                    if (_this.parent().next().find('li').size() < 1) {
                        syncClasshour(_this);
                    }
                    _this.parent().siblings('.stree').slideToggle();
                } else {
                    _this.parent().siblings('.stree').slideToggle();
                }
            }
        })

        // 删除单元
        $(document).on('click','.del',function(){

            var unit_size = $(this).parent().siblings('.ctree').children().size();
            var unit_name = $(this).prevAll('.tree_branch').text();
            var _this = $(this);
            if (unit_size > 0) {
                showMessage("请先清空单元下的课文");
                return false;
            } else {
                if(confirm("确定要删除"+unit_name+"吗？")){
                    delNode(_this, 'lesson');
                }
            }
        })

        // 删除课文
        $(document).on('click','.del_c',function(){

            var kwText = $(this).prevAll('.child_node_span').text();
            var _this = $(this);
            if(confirm("确定要删除"+kwText+"吗？")) {
                delNode(_this, 'lesson');
            }
        })

        // 删除课时
        $(document).on('click','.del_ks',function(){

            // 如果有活动在添加或编辑时就禁止编辑
            if ($('.act_box[rel=1] iframe').css('display') && $('.act_box[rel=1] iframe').css('display') != 'none') {
                showMessage('活动添加、编辑中，请勿删除课时');
                return;
            }

            var ksText = $(this).prevAll('.grandchild_node_span').text();
            var _this = $(this);
            if(confirm("确定要删除"+ksText+"吗？")) {
                delNode(_this, 'classhour');
            }
        })

        // 编辑单元
        $(document).on('click','.edit',function(){
            editNode($(this), 'lesson');
        })

        // 编辑课文
        $(document).on('click','.edit_c',function(){
            editNode($(this), 'lesson');
        })

        // 编辑课时
        $(document).on('click','.edit_ks',function(){
            editNode($(this), 'classhour');
        })

        // 单元拖拽
        ctree($('.tree_drag'));

        // 课文拖拽
        ctree($('.ctree_drag'));

        // 课时拖拽
        ctree($('.stree_drag'));

        var _widthL = $('.lesson_left').width();
        var _widthMid = $('.lesson_main').width();
        var _widthR = $('.lesson_right').width();

        // 左侧收起
        $(document).on('click', '.l-shrink-open', function () {

            var _widthL1 = $('.lesson_left').width();
            var _widthMid1 = $('.lesson_main').width();
            $('.lesson_left').animate({width:"0%"});
            $('.lesson_left .l-shrink-open').animate({left:"0"});
            $('.lesson_main').animate({width:_widthMid1 + _widthL1});

            $(this).removeClass('l-shrink-open').addClass('l-shrink-close');

            // 控制网格间隙比例
            $('.act_box_ul').css('margin-left','25px');
        })

        // 左侧展开
        $(document).on('click', '.l-shrink-close', function () {

            var _widthMid3 = $('.lesson_main').width();

            $('.lesson_left').animate({width:_widthL});
            $('.lesson_left .l-shrink-close').animate({left:"200px"});
            $('.lesson_main').animate({width:_widthMid3 - _widthL});

            $(this).removeClass('l-shrink-close').addClass('l-shrink-open');

            // 控制网格间隙比例
            $('.act_box_ul').css('margin-left','10px');
        })

        // 右侧收起
        $(document).on('click', '.r-shrink-open', function () {

            var _widthR1 = $('.lesson_right').width();
            var _widthMid2 = $('.lesson_main').width();
            $('.lesson_right').animate({width:"0%"});
            $('.lesson_left .r-shrink-open').animate({left:"0"});
            $('.lesson_main').animate({width:_widthMid2 + _widthR1});

            $(this).removeClass('r-shrink-open').addClass('r-shrink-close');
        })

        // 右侧展开
        $(document).on('click', '.r-shrink-close', function () {

            var _widthMid4 = $('.lesson_main').width();

            $('.lesson_right').animate({width:_widthR});
            $('.lesson_right .l-shrink-close').animate({left:"-11px"});
            $('.lesson_main').animate({width:_widthMid4 - _widthR});

            $(this).removeClass('r-shrink-close').addClass('r-shrink-open');
        })


        // 添加活动
        $(document).on('click','.addTache',function(){

            // 如果有活动在添加或编辑时就禁止编辑
            if ($('.act_box[rel=1] iframe').css('display') && $('.act_box[rel=1] iframe').css('display') != 'none') {
                return;
            }

            // 在收缩的状态下，需要先拉伸
            if ($(this).next().next().hasClass('tache_down')) {
                $(this).next().next().click();
            }

            // 标识当前的环节
            var rel = $(this).prev().attr('rel');
            $('.act_box').each(function () {
                if (rel == $(this).prev().find('.node_name').attr('rel')) {
                    $(this).attr('rel', 1);
                } else {
                    $(this).attr('rel', 0);
                }
            })

            // 记录当前的活动类型
            $(this).parents('.tache').addClass('activityFlag').siblings().removeClass('activityFlag');

            // 添加活动弹出窗口
            popCenterWindow();

        })

        // 取消添加活动
        $(document).on('click','.cancel_add',function(){

            $(this).parent().siblings('.act_box').find('#actIframe').hide();
            $(this).hide();
        })

        // 选中活动
        $('#add_module li').click(function(){

            // 如果有活动在添加或编辑时就禁止编辑
            if ($('.act_box[rel=1] iframe').css('display') && $('.act_box[rel=1] iframe').css('display') != 'none') {
                return;
            }

            var type = $(this).attr('attr'), taId = $('.activityFlag').attr('rel');

            $(this).addClass('on').siblings().removeClass('on');

            // 获取当前选择的组件类型
            var moduleType = $('#add_module li.on').attr('attr');
            var moduleText = "";
            switch(moduleType) {
                case "1":
                    moduleText = "作业";
                    $('input[name=resourceFlag]').val(1);
                    break;
                case "2":
                    $('input[name=resourceFlag]').val(2);
                    moduleText = '课堂练习';
                    break;
                case "3":
                    moduleText = '文本';
                    break;
                case "4":
                    moduleText = '链接';
                    break;
                case "5":
                    moduleText = '拓展阅读';
                    $('input[name=resourceFlag]').val(5);
                    break;
                case "6":
                    moduleText = '讨论';
                    break;
            }
            $('.act_box[rel=1]').children('iframe').show();

            // 左右侧展开
            setTimeout("$('.l-shrink-open').click()", 100);
            setTimeout("$('.r-shrink-open').click()", 500);

            // 这里要判断url里的c_id或cro_id是否和已绑定的课时里的班级或群组吻合
            // 是的话，便传过去，否则就不传
            var c_id = $('.tree .on').parent().parent().attr('c_id');
            var cro_id = $('.tree .on').parent().parent().attr('cro_id');
            var moduleTag = '__APPURL__/Activity/add/type/' + type + '/taId/' + taId;
            if (inputCid == 0 && inputCroid == 0) {
                $('.act_box[rel=1]').children('iframe').attr('src',moduleTag);
                // 关闭dialog窗口
                $(".closeWin").trigger("click");
            } else if (c_id == '' && cro_id == '') {
                moduleTag = '__APPURL__/Activity/add/type/' + type + '/taId/' + taId + '/flag/1';
                $('.act_box[rel=1]').children('iframe').attr('src',moduleTag);
                // 关闭dialog窗口
                $(".closeWin").trigger("click");
            } else if ((inputCid != 0 && c_id.indexOf(',' + inputCid + ',') == -1) || (inputCroid != 0 && cro_id.indexOf(',' + inputCroid + ',') == -1)) {
                moduleTag = '__APPURL__/Activity/add/type/' + type + '/taId/' + taId + '/flag/1';
                $('.act_box[rel=1]').children('iframe').attr('src',moduleTag);
                // 关闭dialog窗口
                $(".closeWin").trigger("click");
            } else {
                $.post('__APPURL__/Classhour/validateInfo', 'cl_id=' + $('input[name=cl_id]').val() + '&c_id=' + inputCid + '&cro_id=' + inputCroid, function (json) {
                    if (json == 0) {
                        moduleTag = '__APPURL__/Activity/add/type/' + type + '/taId/' + taId;
                    } else {
                        moduleTag = '__APPURL__/Activity/add/type/' + type + '/taId/' + taId + '/c_id/' + inputCid + '/cro_id/' + inputCroid;
                    }
                    $('.act_box[rel=1]').children('iframe').attr('src',moduleTag);
                    // 关闭dialog窗口
                    $(".closeWin").trigger("click");
                }, 'json');
            }
            $('.activityFlag .cancel_add').show();
        })

        // 编辑活动(网格)
        $(document).on('click', '.thumbAct', function () {

            editIframe($(this));
        })

        // 列表
        $(document).on('click', '.act_title span', function () {

            editIframe($(this));
        })

        // 显示和隐藏删除活动图标
        if (inputCid == 0 && inputCroid == 0) {
            $(document).on('mouseover','.flex_add',function(){
                $(this).find('.flex_del').show();
            }).on('mouseout','.flex_add',function(){
                $(this).find('.flex_del').hide();
            })
        }

        // 删除活动
        $(document).on('click','.flex_del',function(){

            // 如果有活动在添加或编辑时就禁止编辑
            if ($('.act_box[rel=1] iframe').css('display') && $('.act_box[rel=1] iframe').css('display') != 'none') {
                return false;
            }

            var _this = $(this);

            if (_this.attr('rel') == 1) {
                showMessage('该活动已经被发布，不能删除');
                return false;
            }

            if(confirm("确定要删除该活动吗？")){
                $.post('__APPURL__/Activity/delete', 'co_id=' + $('input[name=co_id]').val() + '&ta_id=' + _this.parents('.tache').attr('rel') + '&act_id=' + _this.prev().attr('rel'), function (json) {
                    if (json.status == 1) {
                        _this.closest('.liliObj').remove();
                    } else {
                        showMessage(json.info);
                    }
                }, 'json')
            }
            return false;
        })

        // 添加环节
        $(document).on('click', '.add_node', function(){

            // 如果有活动在添加或编辑时就禁止编辑
            if ($('.act_box[rel=1] iframe').css('display') && $('.act_box[rel=1] iframe').css('display') != 'none') {
                return;
            }

            // 默认添加10个环节
            if ($('.tache') && $('.tache').size() > 10) {
                showMessage('只能添加十个节点');
                return false;
            }

            var nodeNum = $('.tache_box .tache').size() + 1;
            var context = "环节" + nodeNum;
            $.post('__APPURL__/Tache/insert', 'co_id=' + $('input[name=co_id]').val() + '&l_id=' + $('input[name=l_id]').val() + '&cl_id=' + $('input[name=cl_id]').val() + '&ta_title=' + context, function (json) {
                var htm = "";
                if (json.status == 1) {
                    htm = "<div class='tache fl' rel=" + json.info + ">"
                         +"<div class='act_top'>"
                         +"<a class='del_tache'></a>"
                         +"<label class='node_name cap_cor' rel='" + json.info + "' title='" + context + "'>" + context + "</label>"
                         +"<a class='addTache'>+添加活动</a>"
                         +"<a class='cancel_add'>取消活动</a>"

                         +"<a class='tache_arrow tache_up'></a>"
                         +"</div>"
                         +"<div class='act_box fl'>"
                         +"<iframe id='actIframe' width='100%' height='627' frameborder='no' border='0' scrolling='yes' style='display:none;'></iframe>"
                         +"<ul class='act_box_ul sortable ui-sortable'></ul>"
                         +"</div>"
                         +"</div>";
                    $('.tache_box').children('.add_tache').before(htm);

                    // 活动模块拖拽
                    dragAct($('.sortable'));
                } else {
                    showMessage(json.info);
                }
            }, 'json')
        })

        // 删除环节
        $(document).on('click','.del_tache',function(){

            // 先查询环节下是否有多动
            var _this = $(this);
            if (_this.parents('.tache').find('.act_box_ul').children().size() > 0) {
                showMessage('请先删除该环节下的活动');
                return false;
            }

            if(confirm("确定要删除该环节吗？")){
                $.post('__APPURL__/Tache/delete', 'ta_id=' + _this.parents('.tache').attr('rel') + '&co_id=' + $('input[name=co_id]').val() + '&cl_id=' + $('input[name=cl_id]').val(), function (json) {
                    if (json.status == 0) {
                        showMessage(json.info);
                    } else {
                        _this.parent().parent().remove();
                    }
                }, 'json');
            }
        })

        // 编辑环节名称
        $(document).on('click','.cap_cor',function(){

            var this_ks = $(this);

            $(this).removeClass('cap_cor');

            // 取出当前的文本内容保存起来
            var text = $(this).text();
            if (text != '') {
                $('input[name=tacheInput]').val(text);
                $('input[name=ta_id]').val($(this).parents('.tache').attr('rel'));
            }
            $(this).html("");
            var input = $("<input class='edit_tache' onkeydown='keydown(event)'>");
            $(this).append(input);
            var thisInput = $(this).parent().find('.edit_tache');
            thisInput.val($('input[name=tacheInput]').val());

            // 获取焦点
            input.focus();

            // 失去焦点 存储新值
            input.focusout(function(event) {
                var new_text = $.trim(input.val());
                if(new_text == '') {
                    showMessage("名称不能为空");
                    input.focus();
                    return false;
                } else if(new_text.length > 15) {
                    showMessage("输入的名称请不要超过15个字");
                    input.focus();
                    return false;
                } else {
                    var _this = $(this);
                    if (new_text != $('input[name=tacheInput]').val()) {
                        $.post('__APPURL__/Tache/update', 'co_id=' + $('input[name=co_id]').val() + '&ta_id=' + $('input[name=ta_id]').val() + '&ta_title=' + new_text, function (json) {
                            if (json.status == 1) {
                                _this.parent().text(new_text);
                                $('input[name=tacheInput]').val(new_text);
                                this_ks.addClass('cap_cor');
                            } else {
                                showMessage(json.info);
                            }
                        }, 'json');
                    } else {
                        _this.parent().text(new_text);
                        this_ks.addClass('cap_cor');
                    }
                }
            })
        })

        // 环节展开与收缩
        $(document).on('click','.tache_arrow',function(){

            var _arrow = $(this);
            if(_arrow.hasClass("tache_up")){
                _arrow.removeClass('tache_up').addClass('tache_down');
            }else {
                _arrow.removeClass('tache_down').addClass('tache_up');
            }

            _arrow.parent().siblings().slideToggle();
        })

        // 网格切换列表
        $('.order_switch .list').click(function(){
            $(this).addClass('on').siblings().removeClass('on');

            $('.tache_box .tache li').each(function(){
                $(this).find('.thumbAct').parent().hide();
                $(this).find('.listAct').parent().show();
            })

            // 收缩与展开比例设置
            $('.liliObj').width('100%');
        })

        // 列表切换网格
        $('.order_switch .thumb').click(function(){
            $(this).addClass('on').siblings().removeClass('on');

            $('.tache_box .tache li').each(function(){
                $(this).find('.thumbAct').parent().show();
                $(this).find('.listAct').parent().hide();
            })

            // 收缩与展开比例设置
            $('.liliObj').removeAttr('style');
        })

        // 教学活动的展开与收缩
        $(document).on('click','.topic_arrow',function(){

            var _arrow = $(this);
            if(_arrow.hasClass("topic_up")){
                _arrow.removeClass('topic_up').addClass('topic_down');
            }else {
                _arrow.removeClass('topic_down').addClass('topic_up');
            }

            if (_arrow.hasClass('topic_up')) {

                var act_type = _arrow.next().attr('act_type');

                // 异步传值
                $.post('__APPURL__/Activity/search', {act_id:_arrow.next().attr('rel'), act_type:act_type}, function(json) {

                    var html = '';

                    if (json) {

                        // 作业、练习、扩展阅读
                        if (act_type == 1 || act_type == 2 || act_type == 5) {
                            for (var i = 0; i < json.length; i++) {
                                for (var j=0; j< json[i].length; j++) {
                                    if (j == 0) {
                                        // 如果有附件
                                        if (json[i][j]['attachment']) {
                                            html += '<ul class="uploadedList">';
                                            for (var k=0; k<json[i][j]['attachment'].length; k++) {
                                                html += '<li rel="' + json[i][j]['attachment'][k]['ar_id'] + '" class="ListFiles" trans="' + json[i][j]['attachment'][k]['ar_is_transform'] + '">'
                                                     +  '<a class="li_cover" href="javascript:void(0)" target="">'
                                                     +  '<img src="' + json[i][j]['attachment'][k]['img_path'] + '" width="100" height="75" title="' + json[i][j]['attachment'][k]['ar_title'] + '">'
                                                     +  '</a>'
                                                     +  '<a class="file_name" href="javascript:void(0);" title="' + json[i][j]['attachment'][k]['ar_title'] + '">' + json[i][j]['attachment'][k]['ar_title'] + '</a>'
                                                     +  '</li>'
                                            }
                                            html += '</ul>';
                                        }
                                    } else {
                                        html += '<div class="resour_online" rel="' + json[i][j]['to_id'] + '" attr="' + json[i][j]['to_type'] + '">'
                                                +  '<i>' + getIdByTopicType(json[i][j]['to_type']) + '</i>'
                                                +  '<div class="online_cursor">'
                                                +  '' + json[i][j]['to_title'] + ''
                                                + '<div>';
                                        if (json[i][j]['to_type'] == 1) {
                                            html += syncSingle(syncCount, json[i][j]['to_option'], json[i][j]['to_answer']);
                                        } else if (json[i][j]['to_type'] == 2) {
                                            html += syncMulti(json[i][j]['to_option'], json[i][j]['to_answer']);
                                        } else if (json[i][j]['to_type'] == 3) {
                                            html += syncFill(json[i][j]['to_option'], json[i][j]['to_answer']);
                                        } else if (json[i][j]['to_type'] == 4) {
                                            html += syncJudge(syncCount, json[i][j]['to_option'], json[i][j]['to_answer']);
                                        } else {
                                            html += '<label style="cursor: pointer;">' + syncShort(json[i][j]['to_answer']) + '</label>';
                                        }
                                        html += '</div>';
                                        html += '</div>';
                                        html += '</div>';
                                        syncCount++;
                                    }
                                }
                            }

                        // 文本
                        } else if (act_type == 3) {
                            for (var i in json) {
                                html += '<div class="resour_online" rel="' + json[i]['act_id'] + '">'
                                     + '<i>&nbsp;</i>'
                                     + '<div class="online_cursor">'
                                     + '<div>' + json[i]['act_note'] + '</div>';
                                html += '</div>';
                                html += '</div>';
                            }

                        // 外链
                        } else if (act_type == 4) {
                            for (var i = 0; i < json.length; i++) {
                                for (var j=0; j< json[i].length; j++) {
                                    if (j == 0) {
                                    } else {
                                        html += '<div class="resour_online" rel="' + json[i][j]['li_id'] + '>'
                                                +  '<i>' + json[i][j]['li_title'] + '</i>'
                                                +  '<div class="online_cursor">'
                                                + '<div><a href="' + json[i][j]['li_url'] + '" target="_blank">' + json[i][j]['li_title'] + '</a></div>';
                                        html += '</div>';
                                        html += '</div>';
                                    }
                                }
                            }
                        // 讨论
                        } else if (act_type == 6) {
                            for (var i in json) {
                                html += '<div class="resour_online" rel="' + json[i]['act_id'] + '">'
                                     + '<i>&nbsp;</i>'
                                     + '<div class="online_cursor">'
                                     + '<div>' + json[i]['act_note'] + '</div>';
                                html += '</div>';
                                html += '</div>';
                            }
                        }
                    } else {
                        html += '';
                    }

                    // 显示内容
                    _arrow.parent().next().find('.resourceContent').html(html);

                }, 'json')

                _arrow.parent().siblings().slideToggle();
            } else {
                _arrow.parent().siblings().slideToggle();
            }
        })

        // 点击未转码的资源，自动下载
        $(document).on('click', '.ListFiles', function () {
            var rel = $(this).parent().hasClass('lessonList') ? $(this).attr('attr') : $(this).attr('rel');
            if ($(this).attr('trans') != 1) {
                if (confirm('该附件未转码，是否下载该附件？')) {
                    location.href = "__APPURL__/Activity/download/?id=" + rel;
                }
            } else {
                $(this).find('a').each(function () {
                    $(this).attr({'target':'_blank', 'href':'__APPURL__/AuthResource/show/ar_id/' + rel});
                })
            }
        })

        // 右侧资源tab切换
        $(document).on('click', '.res_tab li', function(){

            $(this).addClass("on").siblings().removeClass("on");
            var index =  $(".res_tab li").index(this);
            $(".resBox .res_box").eq(index).show().siblings().hide();
        })

        // 鼠标移上资源 显示资源操作按钮
        $(document).on('mouseover', '.res_cover', function(){
            $(this).children('.tools_cover').show();
        })
        $(document).on('mouseout', '.res_cover', function(){
            $(this).children('.tools_cover').hide();
        })

        // 外链---删除外链
        $(document).on('click','.del-link',function(){

            if(confirm("确定要删除该外链吗？")){
                $(this).parent().parent().remove();
            }
        })

        // 拓展阅读---资源列表 鼠标移上 删除列表中的资源
        $(document).on('mouseover','.ListFiles',function(){
            $(this).find('.del_res').show();
        }).on('mouseout','.ListFiles',function(){
            $(this).find('.del_res').hide();
        })

        // 资源库检索--选中学科领域
        $('.topic_type span').click(function() {

            $(this).addClass('on').siblings().removeClass('on');
        })

        // 选择题目
        $(document).on('click', '.topicOption p span', function() {

            if ($(".topicOption p span.selected")) {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    $(this).addClass('selected');
                }
            }
        })

        // 学科领域标签添加
        $(document).on('click', '.add_subjectArea', function() {
            $('.tagcustom').dialog('open');
            $('.addTag_list').html('');
        })

        // 删除已添加标签
        $(document).on('mouseover', '.addTag_list span', function() {

            $(this).children().show();
        }).on('mouseout', '.addTag_list span', function() {

            $(this).children().hide();
        })
        $(document).on('click', '.addTag_list cite', function() {

            if (confirm("确定要删除该标签吗？")) {

                $(this).parent().remove();
            }
        })

        // 添加学科领域标签 弹出窗口
        $(".tagcustom").dialog({
            draggable: true,
            resizable: true,
            autoOpen: false,
            position: 'center',
            stack: true,
            modal: true,
            bgiframe: true,
            width: '600',
            height: 'auto',
            show: {
                effect: "blind",
                duration: 400
            },
            hide: {
                effect: "explode",
                duration: 400
            },
            overlay: {
                backgroundColor: '#000',
                opacity: 0.5
            },
            buttons: {
                确定: function() {

                    // 把确定按钮里的字清空
                    $('.tag_input').val('');

                    // 把已选中的标签添加到资源库检索弹出框里
                    var html = '<span class="add_subjectArea on">+添加</span>';
                    html += $('.list_append').html();

                    $('.subjectAdd').html(html);
                    $(this).dialog('close');
                },
                取消: function() {

                    // 取消时，清空已选中的标签
                    $('.list_append').html('');

                    // 清空input框的内容
                    $('.tag_input').val('');

                    $(this).dialog('close');
                }
            }
        });

        // 添加标签
        $(document).on('click', '.list_hand .hand', function() {
            var tVal = $(this).text();
            var rel = $(this).attr('rel');

            if ($('.list_append span').size() < 5) {

                var flag = true;
                $('.list_append span').each(function() {

                    // 如果有相同的rel属性，则说明，已经添加过该标签
                    if ($(this).attr('rel') == rel) {
                        flag = false;
                        return false;
                    }
                })

                if (flag) {
                    $('.list_append').append("<span class='hand' rel=" + rel + ">" + tVal + "<a class='flex_close cDel'></a></span>");
                    // 更新
                    $.post('__APPURL__/TopicTerm/update', {tt_id: rel}, function(json) {
                    }, 'json');
                }
            } else {
                showMessage('标签已上限!');
            }

        })

        // 换一批
        var tlen = 1;
        $('.append_list li').eq(0).show().siblings().hide();
        $(document).on('click', '.list_lab', function() {
            if (tlen == $('.append_list li').size()) {
                tlen = 0
            }
            $('.append_list li').eq(tlen).show().siblings().hide();
            tlen++
        })

        // 弹窗删除按钮 显示，隐藏
        $(document).on('mouseover', '.tag_room span', function() {
            $(this).children('a').show();
        }).on('mouseout', '.tag_room span', function() {
            $(this).children('a').hide();
        })

        $(document).on('mouseover', '.list_append span', function() {
            $(this).children('a').show();
        }).on('mouseout', '.list_append span', function() {
            $(this).children('a').hide();
        })

        // 弹窗删除按钮
        $(document).on('click', '.cDel', function() {
            if (confirm("确定要删除该课程吗？")) {
                $(this).parent().remove();
                $.post('__APPURL__/TopicTerm/update', {tt_id: $(this).parent().attr('rel'), flag: 1}, function(json) {
                }, 'json');
            } else {
                return false;
            }
        })

        // 弹窗添加按钮
        $(document).on('click', '.tag_append', function() {

            // 在输入之前判断下，是否已经添加了5个标签
            if ($('.list_append span').size() == 5) {
                showMessage('标签已上限!');
                return;
            }

            var tVal = $(this).prev().val();
            var tLen = $.trim(tVal);

            if (!tLen == "") {

                if ($('.list_append span').size() < 5) {

                    var flag = true;
                    $('.list_append span').each(function() {

                        if ($(this).text() == tLen) {
                            flag = false;
                            return false;
                        }
                    })

                    if (flag) {
                        $('.list_append').append("<span class='hand' ref=''>" + tLen + "<a class='flex_close cDel'></a></span>");
                        $.post('__APPURL__/TopicTerm/insert', {tt_title: tLen}, function(json) {
                            if (json['status']) {
                                // 把返回的标签id赋值给rel属性
                                $('.list_append span').last().attr('rel', json['info']);
                            }
                        }, 'json')
                    }
                    $(this).prev().val('');
                } else {
                    showMessage('标签已上限!');
                }
            }
        })

        // 资源库检索 鼠标滑过资源
        $(document).on('mouseenter','.res_cover',function() {
            $(this).parent().addClass('on');
        })
        $(document).on('mouseleave','.res_cover',function() {
            $(this).parent().removeClass('on');
        })

        // 资源库检索 选中资源
        $(document).on('click','.res_cover',function() {
            choose($(this));
        })

        // 删除资源列表中的资源
        $('.res_list div ul li').mouseover(function(){

            $(this).find('.del_res').show();
        }).mouseout(function(){

            $(this).find('.del_res').hide();
        })

        $(document).on('click','.del_res',function(){
            if(confirm("确定要删除该资源吗？")){
                $(this).parent().remove();
                return false;
            }
            return false;
        })

        // 点击课时
        $(document).on('click','.grandchild_node_span',function(){

            var _this = $(this);

            // 选中的课时加亮显示
            $('.grandchild_node_span').each(function () {
                if (_this.attr('rel') == $(this).attr('rel')) {
                    $(this).addClass('on')
                } else {
                    $(this).removeClass('on')
                }
            })

            // 在编辑课时标题时或是当前环节是该课时下时，禁止加载内容
            if (_this.html() != '<input class="edit_node" onkeydown="keydown(event)">' && $('input[name=cl_id]').val() != _this.attr('rel')) {

                // 存储课时ID，避免重复加载
                $('input[name=cl_id]').val(_this.attr('rel'));

                // 存储课时资源ID
                $('input[name=ar_id]').val(_this.attr('ar_id'));

                // 存储课文ID
                $('input[name=l_id]').val(_this.parents('.stree').parent().attr('rel'));

                // 动态显示课文 >> 课时
                var courseTitle = "<?php echo ($title); ?>"
                $('.courseTitle').html('');
                $('.courseTitle').html(courseTitle + ' >> ' + _this.parents('.ctree').prev().find('.tree_branch').text());
                $('.title').html(_this.parents('.stree').prev().find('.tree_branch').text() + ' >> ' + _this.text());

                // 异步加载环节、活动
                $.post('__APPURL__/Tache/lists', 'cl_id=' + _this.attr('rel') + '&c_id=' + $('input[name=c_id]').val() + '&cro_id=' + $('input[name=cro_id]').val(), function (json) {
                    var htm='';
                    if (json && json['tache']) {
                        for (var i=0, len=json['tache'].length; i<len; i++) {
                            htm += '<div class="tache fl" rel="' + json['tache'][i]['ta_id'] + '">'
                                +'<div class="act_top" rel="' + json['tache'][i]['ta_id'] + '">'
                                +'<a class="del_tache"></a>'
                                +'<label class="node_name cap_cor" rel="' + json['tache'][i]['ta_id'] + '" title="' + json['tache'][i]['ta_title'] + '">' + json['tache'][i]['ta_title'] + '</label>';

                            if (inputCid ==0 && inputCroid == 0) {
                                htm += '<a class="addTache">+添加活动</a>';
                            }

                            htm += "<a class='cancel_add'>取消活动</a>"

                                +'<a class="tache_arrow tache_up"></a>'
                                +'</div>'
                                +'<div class="act_box fl">'
                                +'<iframe id="actIframe" width="100%" height="627" frameborder="no" border="0" scrolling="yes" style="display:none;"></iframe>'
                                +'<ul class="act_box_ul sortable">';
                            if (json['tache'][i]['act_id'] && json['tache'][i]['act_id'].length) {
                                var obj = json['tache'][i]['act_id'];
                                for (var j=0, l=obj.length; j<l; j++) {
                                    htm += '<div class="liliObj" name="'+obj[j]['ap_id']+'" rel="' + obj[j]['act_id'] + '">'
                                        +'<li class="lili flex_add homework ' + obj[j]['act_flag'] + '">'
                                        +'<div class="thumbAct" act_type="' + obj[j]['act_type'] +'">'
                                        +'<div class="' + getTypeById(obj[j]['act_type']) + '"></div>'
                                        +'<span title="' + obj[j]['act_title'] + '" rel="' + obj[j]['act_id'] + '">' + obj[j]['act_title'] + '</span>'
                                        + '<a class="flex_del" rel="' + obj[j]['act_is_published'] + '" style="display: none;"></a>'
                                        + '</div>'
                                        +'</li>'
                                        +'<li class="lili act_option" style="display: none;">'
                                        +'<div class="listAct">'
                                        +'<div class="act_title">'
                                        +'<a class="topic_arrow topic_down"></a>'
                                        +'<span title="' + obj[j]['act_title'] + '" rel="' + obj[j]['act_id'] + '" act_type="' + obj[j]['act_type'] + '">' + obj[j]['act_title'] + '</span>'
                                        +'</div>'
                                        +'<div class="topicBox" style="display: none;">'
                                        +'<div class="resourceContent"></div>'
                                        +'</div>'
                                        +'</div>'
                                        +'</li>'
                                        +'</div>'
                                }
                            }
                            htm +='</ul>'
                                +'</div>'
                                +'</div>';
                        }
                    }
                    $('.tacheList').html(htm);
                    $('.tache_box').show();
                    $('.add_res').show();
                    $('.order_switch').show();
                    $('.main_top:eq(1)').show();

                    // 活动模块拖拽
                    dragAct($('.sortable'));

                    if (inputCid != 0 || inputCroid != 0) {
                        $('.order_switch .list').click();
                        $('.act_box_ul .topic_arrow').click();
                    } else {
                        $('.order_switch .thumb').click();
                    }

                }, 'json')
                $('.res_box_ul').html('');
                getPage();
            }
        })

        // 课时发布
        $('.publish').click(function(){

            // 如果有活动在添加或编辑时就禁止编辑
            if ($('.act_box[rel=1] iframe').css('display') && $('.act_box[rel=1] iframe').css('display') != 'none') {
                return;
            }

            $('.bindClass span').show();
            $('.bindGroup span').show();

            // 把弹出班级窗口中选中的样式给清掉
            $('.bindClass span').removeClass('xin_ds');
            $('.bindGroup span').removeClass('xin_ds');

            // 单击课时，首先查看该课时是否已有绑定的班级和群组，有的话便在打开班级弹窗时隐藏掉绑定的班级群组
            var c_id = $('.ctree .on').parent().parent().attr('c_id');
            var cro_id = $('.ctree .on').parent().parent().attr('cro_id');
            var tmp = '';
            var html = '';
            $('.xin_sain .bindClass span').each(function () {
                tmp = ',' + $(this).attr('rel') + ',';
                if (c_id.indexOf(tmp) != -1) {
                    $(this).hide();
                    html += '<span rel="' + $(this).attr('rel') + '">' + $(this).text() + '</span>';
                }
            })
            $('.xin_sain .bindGroup span').each(function () {
                tmp = ',' + $(this).attr('rel') + ',';
                if (cro_id.indexOf(tmp) != -1) {
                    $(this).hide();
                    html += '<span rel="' + $(this).attr('rel') + '">' + $(this).text() + '</span>';
                }
            })

            // 添加到已绑定的班级和群组节点上
            $('.allClassGroup').html(html);

            $('.xin_add').dialog("open");
        })

        $(".xin_add").dialog({
            draggable: true,
            resizable: true,
            autoOpen: false,
            position :'center',
            stack : true,
            modal: true,
            bgiframe: true,
            width: '480',
            height: 'auto',

            show: {     // 对话框打开效果
                effect: "blind",
                duration: 400
            },
            hide: {     // 对话框关闭效果
              effect: "explode",
              duration: 400
            },
            overlay: {
                backgroundColor: '#000',
                opacity: 0.5
            },
            buttons: {
                确定: function() {

                    // 单击确定时，查找bindClass和bindGroup两个里是否有被选中的值，有的话，就判断是班级还是群组，异步提交
                    var c_id = '';
                    var cro_id = '';
                    var cName = [];
                    var croName = [];
                    var obj = $('.ctree .on');
                    var c_id1 = obj.parent().parent().attr('c_id') ? obj.parent().parent().attr('c_id') : '';
                    var cro_id1 = obj.parent().parent().attr('cro_id') ? obj.parent().parent().attr('cro_id') : '';

                    if ($('.bindClass').children().length > 0) {
                        $('.bindClass span.xin_ds').each(function () {
                            c_id += ',' + $(this).attr('rel') ;
                            cName.push($(this).text());
                        })
                        c_id = c_id.slice(1);
                        c_id1 = c_id1 ? c_id1 + c_id + ',' : ',' + c_id + ',' ;
                    }

                    if ($('.bindGroup').children().length > 0) {
                        $('.bindGroup span.xin_ds').each(function () {
                            cro_id += ',' + $(this).attr('rel');
                            croName.push($(this).text());
                        })
                        cro_id = cro_id.slice(1);
                        cro_id1 = cro_id1 ? cro_id1 + cro_id + ',' : ',' + cro_id + ',' ;
                    }

                    // 如果班级和群组都为空，则直接关闭窗口
                    var dialog = $(this);
                    if (c_id == '' && cro_id == '') {
                        showMessage('未指定班级或群组，若发布过，请点击取消关闭窗口');
                        return;
                    } else {
                        obj.parent().parent().attr({c_id: c_id1,cro_id: cro_id1});

                        $.post('__APPURL__/ClasshourPublish/insert', {c_id: c_id, cro_id: cro_id, co_id: $('input[name=co_id]').val(), l_id: obj.parents('.stree').prev().attr('rel'), cl_id:obj.attr('rel'), ap_course: $('input[name=subjectName]').val()}, function (json) {
                            if (json['status'] == 1) {
                                showMessage(json['info'], 1);
                                dialog.dialog('close');
                            }
                        }, 'json');
                    }
                },
                取消: function() {
                    $(this).dialog('close');
                }
            }
        })

        // 绑定群组添加
        $(document).on('click','.xin_sain .bindGroup span',function(){
            if ($(this).hasClass('xin_ds')){
                $(this).removeClass('xin_ds');
            } else {
                $(this).addClass('xin_ds');
            }
        })

        // 绑定班级添加
        $(document).on('click','.xin_sain .bindClass span',function(){

            if ($(this).hasClass('xin_ds')){
                $(this).removeClass('xin_ds');
            } else {
                $(this).addClass('xin_ds');
            }
        })

        // 设置教学组件窗口遮罩层的宽和高
        $("#Win_cover").css({
            height: function () {
                return $(document).height();
            },
            width: function () {
                return $(document).width();
            }
        })

        // 单击搜索
        $('.searchTopic').on('click', function() {
            getSearchResult();
        })

        // 资源上翻看
        $(document).on('click', '.pageUp', function(){
            var page = parseInt($(this).attr('page'));
            if (page && page > 0) {
                getPage(page, 'up');
            }
        })

        // 资源下翻看
        $(document).on('click', '.pageDown', function(){
            var page = parseInt($(this).attr('page'));
            if (page && page > 1) {
                getPage(page, 'down');
            }
        })

        // 添加资源
        $('.add_res').click(function(){
            if ($('#actIframe').css('display') && $('#actIframe').css('display') != 'none') {
                return;
            }
            $('#addResBox').dialog('open');
        })

        // 添加资源 弹出窗口
        $("#addResBox").dialog({
            draggable: true,
            resizable: true,
            autoOpen: false,
            position: 'center',
            stack: true,
            modal: true,
            bgiframe: true,
            width: '600',
            height: 'auto',
            show: {
                effect: "blind",
                duration: 400
            },
            hide: {
                effect: "explode",
                duration: 400
            },
            overlay: {
                backgroundColor: '#000',
                opacity: 0.5
            },
            buttons: {
                确定: function() {

                    var _this = $(this);
                    var ar_id = '';

                    // 检测是否有从资源检索窗口中添加的资源
                    if ($('.lessonList li').size() > 0) {
                        $('.lessonList li').each(function () {
                            ar_id += ',' + $(this).attr('attr');
                        })
                        ar_id = ar_id.slice(1);
                    }
                    if ($('.lessonList li').size() == 0 && $('.plupload_filelist li').size() == 0) {
                        showMessage('请添加课时资源');
                        return;
                    }

                    // 如果用户上传了附件，但忘了点击上传按钮，自动点击上传
                    if ($('.plupload_buttons').css('display') != 'none' && $('#uploader_filelist').children().size() > 0) {
                        $('.plupload_start').click();
                        var uploader = $('#uploader').pluploadQueue();
                        // Files in queue upload them first
                        if (uploader.files.length > 0) {
                            // When all files are uploaded submit form
                            uploader.bind('UploadComplete', function() {
                                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                                    uploadResource(ar_id, _this);
                                }
                            });
                            uploader.start();
                        }
                    } else {
                        uploadResource(ar_id, _this);
                    }
                },
                取消: function() {

                    var _this = $(this);
                    // 这里需要判断下，如果用户上传了，则需要异步取消session
                    if ($('.plupload_upload_status').text() != '') {
                        $.post('__APPURL__/Classhour/unsetSession', '', function (json) {}, 'json');
                    }
                    // 作业多文件上传
                    reloadFileUpload();
                    $('.lessonList').html('');
                    _this.dialog('close');
                }
            }
        });

        // 从资源库中检索
        $('.win_fromLibrary').click(function() {
            $('input[name=resourceFlag]').val(5);
            $('input[name=resFlag]').val(5);
            $('input[name=resTitleFlag]').val(1);
            $('#res_reslibrary').dialog("open");
            getSearchResult();
        })

        // 资源库检索窗口
        $("#res_reslibrary").dialog({
            draggable: true,
            resizable: true,
            autoOpen: false,
            position :'center',
            stack : true,
            modal: true,
            bgiframe: true,
            width: '780',
            height: '545',

            show: {     // 对话框打开效果
                effect: "blind",
                duration: 500
            },
            hide: {     // 对话框关闭效果
              effect: "explode",
              duration: 500
            },
            overlay: {
                backgroundColor: '#000',
                opacity: 0.5
            },
            buttons: {
                确定: function() {

                    // 追加从资源库中选择的资源
                    var chooseRes = '';

                    $('.resource li').each(function(){

                        if ($(this).hasClass('click')) {

                            chooseRes += '<li attr="'+$(this).attr('attr')+'" class="ListFiles" trans="' + $(this).attr('trans') + '"><a class="res_li" href="javascript:void(0)"><img src="'+$(this).find('img:eq(0)').attr('src')+'" width="100" height="75"><a class="del_res" style="display: none;"></a></a><a class="res_title" href="javascript:void(0)" title="'+$(this).find('a.res_title').attr('title')+'">'+$(this).find('a.res_title').attr('title')+'</a></li>';

                        }
                    });

                    if (chooseRes) {
                        $('.lessonList').append(chooseRes);
                    }

                    $(this).dialog('close');
                    $('input[name=resFlag]').val(0);

                },
                取消: function() {

                    $(this).dialog('close');
                    $('input[name=resFlag]').val(0);
                }
            }
        });

        // 页面加载完毕，默认打开最下面的单元、课文、课时
        if ($('.tree li').size()) {
            $('.tree li').last().find('.tree_branch').click();
            // 页面加载时，此变量加1
            onloadNum++;
        }

    });

    // 课时分页
    function getPage(p, flag) {
        var p = p ? p : 1;
        var flag = flag ? flag : 'down';
        $.post('__APPURL__/Classhour/getPage', 'cl_id=' + $('input[name=cl_id]').val() + '&p=' + p, function (json) {
            var ht = ''
            if (json && json.list) {
                var res = json.list;
                for (var i=0, len=json.list.length; i<len; i++) {
                    ht += '<li rel="' + json.list[i]['ar_id'] + '">'
                       +'<div class="res_cover" trans="' + json.list[i]['ar_is_transform'] + '" rel="' + json.list[i]['ar_id'] + '">'
                       +'<img src="' + json.list[i]['ar_upload'] + '" width="100" height="75">'
                       +'<div class="tools_cover">'
                       +'<div class="fr">'
                       +'<a href="javascript:void(0)" class="res_dw"></a>'
                       +'<a href="javascript:void(0)" class="res_scan scan"></a>'
                       +'<a href="javascript:void(0)" class="res_del"></a>'
                       +'</div>'
                       +'</div>'
                       +'</div>'
                       +'<a class="res_title" href="javascript:void(0)" title="' + json.list[i]['ar_title'] + '">' + json.list[i]['ar_title'] + '</a>'
                       +'</li>'
                }

                if (p != parseInt(json.totalPage)) {
                    // 上一页
                    $('.pageUp').attr('page', p);

                    // 下一页
                    $('.pageDown').attr('page', parseInt(p)+1);
                    if (flag == 'up' && p > 1) {
                        // 上一页
                        $('.pageUp').attr('page', $('.pageUp').attr('page')-1);

                        // 下一页
                        $('.pageDown').attr('page', $('.pageDown').attr('page')-1);
                    }
                }
            }
            if (ht != '') {
                $('.res_box_ul').html(ht);
            }
        }, 'json');
    }

    // 点击网络或是列表的活动模块，进入相对应的iframe里
    function editIframe(obj) {

        // 如果有活动在添加或编辑时就禁止编辑
        if ($('.act_box[rel=1] iframe').css('display') && $('.act_box[rel=1] iframe').css('display') != 'none') {
            return;
        }

        // 记录当前的活动类型
        obj.parents('.tache').addClass('activityFlag').siblings().removeClass('activityFlag');

        // 如果有活动在添加或编辑时就禁止在编辑
        if ($('#actIframe').css('display') != 'none') {
            return;
        }

        // 标识当前的环节
        var rel = obj.parents('.tache').attr('rel');
        $('.act_box').each(function () {
            if (rel == $(this).prev().find('.node_name').attr('rel')) {
                $(this).attr('rel', 1);
            } else {
                $(this).attr('rel', 0);
            }
        })

        // 标识当前的活动
        var id = obj.parents('.liliObj').attr('rel');
        $('input[name=resourceFlag]').val(obj.attr('act_type'));
        $('.liliObj').each(function () {
            if (id == $(this).attr('rel')) {
                $(this).addClass('activityType');
            } else {
                $(this).removeClass('activityType');
            }
        })

        $('.act_box[rel=1]').children('iframe').show();

        var c_id = $('.tree .on').parent().parent().attr('c_id');
        var cro_id = $('.tree .on').parent().parent().attr('cro_id');
        var inputCid = $('input[name=c_id]').val();
        var inputCroid = $('input[name=cro_id]').val();

        // 如果讨论已经发布，则查看
        if (obj.parents('div').attr('name') != 0 && obj.parents('div').attr('name') != undefined){

            var moduleTag;

            switch(obj.attr('act_type')) {

                case '1':
                    window.open('__APPURL__/Homework/correct/ap_id/' + obj.parents('div').attr('name'));
                    $('.cancel_add').click();

                    break;
                case '2':
                    window.open('__APPURL__/Classwork/correct/ap_id/' + obj.parents('div').attr('name'));
                    $('.cancel_add').click();
                    break;

                case '3':
                    window.open('__APPURL__/ActivityData/activity/ap_id/'+obj.parents('div').attr('name')+'/type/3');
                    $('.cancel_add').click();

                    break;
                case '4':
                    window.open('__APPURL__/ActivityData/activity/ap_id/'+obj.parents('div').attr('name')+'/type/4');
                    $('.cancel_add').click();

                    break;
                case '5':
                    window.open('__APPURL__/ActivityData/activity/ap_id/'+obj.parents('div').attr('name')+'/type/5');
                    $('.cancel_add').click();

                    break;
                case '6':
                    moduleTag = '__APPURL__/ActivityData/talk?act_id=' + obj.parents('.liliObj').attr('rel');
                    $('.act_box[rel=1]').children('iframe').attr('src',moduleTag);
                    break;
            }

        } else {

            // 左右侧展开
            setTimeout("$('.l-shrink-open').click()", 100);
            setTimeout("$('.r-shrink-open').click()", 500);

            var moduleTag = '__APPURL__/Activity/edit?act_id=' + obj.parents('.liliObj').attr('rel');
            if (inputCid == 0 && inputCroid == 0) {
                $('.act_box[rel=1]').children('iframe').attr('src',moduleTag);
            } else if (c_id == '' && cro_id == '') {
                moduleTag = '__APPURL__/Activity/edit?act_id=' + obj.parents('.liliObj').attr('rel') + '&flag=1';
                $('.act_box[rel=1]').children('iframe').attr('src',moduleTag);
            } else if ((inputCid != 0 && c_id.indexOf(',' + inputCid + ',') == -1) || (inputCroid != 0 && cro_id.indexOf(',' + inputCroid + ',') == -1)) {
                moduleTag = '__APPURL__/Activity/edit?act_id=' + obj.parents('.liliObj').attr('rel') + '&flag=1';
                $('.act_box[rel=1]').children('iframe').attr('src',moduleTag);
            } else {
                $.post('__APPURL__/Classhour/validateInfo', 'cl_id=' + $('input[name=cl_id]').val() + '&c_id=' + $('input[name=c_id]').val() + '&cro_id=' + $('input[name=cro_id]').val(), function (json) {
                    if (json == 0) {
                        moduleTag = '__APPURL__/Activity/edit?act_id=' + obj.parents('.liliObj').attr('rel');
                    } else {
                        moduleTag = '__APPURL__/Activity/edit?act_id=' + obj.parents('.liliObj').attr('rel')+ '&c_id=' + inputCid + '&cro_id=' + inputCroid;
                    }
                    $('.act_box[rel=1]').children('iframe').attr('src',moduleTag);
                }, 'json')
            }

        }

        $('.activityFlag .cancel_add').show();
    }

    var syncCount = 1111;

    // 分页
    function getList(p) {
        getSearchResult(p);
    }

    // 单击搜索功能
    function getSearchResult(p) {

        // 作业、练习
        if ($('input[name=resourceFlag]').val() == 1 || $('input[name=resourceFlag]').val() == 2) {
            var obj = {};

            // 获取页码
            obj.p = p ? p : 1;

            // 获取搜索框的内容
            obj.content = $('input[name=a_search]').val();

            // 获取选择题型ID
            obj.to_type = $('#reslibrary').find('.topic_type .on').attr('rel')

            // 获取添加的学科领域
            obj.hotTag = '';
            $('#reslibrary').find('.subjectAdd .hand').each(function() {
                obj.hotTag += $(this).attr('rel') + ',';
            })
            obj.hotTag = obj.hotTag.slice(0, -1);

            // 异步传值
            $.post('__APPURL__/Topic/search', obj, function(json) {

                var html = '';
                // 在弹出框里显示搜索出来的数据
                if (json['list']) {

                    for (var i = 0; i < json['list'].length; i++) {
                        html += '<div class="topicOption fl" rel="' + json['list'][i]['to_id'] + '" attr="' + json['list'][i]['to_type'] + '" owner="' + json['list'][i]['a_id'] + '">'
                                + '<p>'
                                + '<span>' + (i + 1) + '</span>'
                                + '<a class="editResource">编辑</a>'
                                + '</p>'
                                + '<div class="topicObj fl">'
                                + '<div class="ttitle">' + getIdByTopicType(json['list'][i]['to_type']) + '</div>'
                                + '<div class="tcontent">'
                                + json['list'][i]['to_title']
                                + '</div>'
                                + '<div class="tanswer">';
                        if (json['list'][i]['to_type'] == 1) {
                            html += syncSingle(syncCount, json['list'][i]['to_option'], json['list'][i]['to_answer']);
                        } else if (json['list'][i]['to_type'] == 2) {
                            html += syncMulti(json['list'][i]['to_option'], json['list'][i]['to_answer']);
                        } else if (json['list'][i]['to_type'] == 3) {
                            html += syncFill(json['list'][i]['to_option'], json['list'][i]['to_answer']);
                        } else if (json['list'][i]['to_type'] == 4) {
                            html += syncJudge(syncCount, json['list'][i]['to_option'], json['list'][i]['to_answer']);
                        } else {
                            html += '<label style="cursor: pointer;">' + syncShort(json['list'][i]['to_answer']) + '</label>';
                        }
                        html += '</div>'
                             +  '</div>'
                             +  '</div>'
                        syncCount++;
                    }
                } else {
                    html += '<div class="topicOption fl">暂无相关题目</div>';
                    json['page'] = '';
                }

                // 显示内容
                $('#reslibrary').find('.resourceContent').html(html);

                // 显示分页
                $('#reslibrary').find('.page').html(json['page']);

            }, 'json')

        // 拓展阅读
        } else if ($('input[name=resourceFlag]').val() == 5) {

            var obj = '';
            if ($('input[name=resFlag]').val() == 5) {
                obj = $('#res_reslibrary');
            } else {
                obj = $('#read_reslibrary');
            }

            var p = p ? p : 1;

            // 模型
            var m_id = obj.find('.topic_type span.on').attr('rel');

            // 资源名称
            var ar_title_flag = $('input[name=resTitleFlag]').val();
            var ar_title = $('input[name=b_search]').eq(ar_title_flag).val();

            // 学科领域标签
            var rta_id = '';

            obj.find('.subjectAdd span:gt(0)').each(function(){
                rta_id += ',' + $(this).attr('rel');
            });

            rta_id = rta_id.slice(1);

            $.post('__APPURL__/AuthResource/lists', 'p='+p+'&m_id='+m_id+'&rta_id='+rta_id + '&ar_title=' + ar_title, function(json){

                var res = '';

                if (json.list) {

                    for (var i = 0; i < json.list.length; i ++) {
                        res += '<li attr="'+json.list[i]['ar_id']+'" trans="'+json.list[i]['ar_is_transform']+'"><a class="res_cover" href="javascript:void(0)"><img class="old_frame" src="'+json.list[i]['ar_upload']+'" width="100" height="75"><img class="res_scan hide" src="__APPURL__/Public/Images/Home/res_scan.png"><img class="res_click hide" src="__APPURL__/Public/Images/Home/res_click.png"></a><a class="res_title" target="_blank" href="__APPURL__/AuthResource/show/ar_id/'+json.list[i]['ar_id']+'" title="'+json.list[i]['ar_title']+'">'+json.list[i]['ar_title']+'</a></li>';
                    }

                } else {
                    res = '<li>暂无数据</li>';
                }

                obj.find('.resource').html(res);
                obj.find('.page').html(json.page);
            }, 'json')
        }
    }

    // 异步写入课时表
    function uploadResource(ar_id, obj) {

        // 等待转码
        Loading();

        $.post('__APPURL__/Classhour/insertResource', 'cl_id=' + $('input[name=cl_id]').val() + '&ar_id=' + ar_id, function (json) {

            // 取消等待
            close_Loading();
            obj.dialog('close');

            // 初始化上传窗口
            reloadFileUpload();
            $('.lessonList').html('');
            getPage();
        }, 'json');
    }

    // 搜索资源库的资源
    function getAuthResource1(p) {

        p = p ? p : 1;

        // 模型
        var m_id = $('.topic_type span.on').attr('rel');

        // 学科领域标签
        var rta_id = '';

        $('.subjectAdd span:gt(0)').each(function(){
            rta_id += ',' + $(this).attr('rel');
        });

        rta_id = rta_id.slice(1);

        $.post('__APPURL__/AuthResource/lists', 'p='+p+'&m_id='+m_id+'&rta_id='+rta_id, function(json){

            var res = '';

            if (json.list) {

                for (var i = 0; i < json.list.length; i ++) {
                    res += '<li attr="'+json.list[i]['ar_id']+'" trans="'+json.list[i]['ar_is_transform']+'"><a class="res_cover" href="javascript:void(0)"><img class="old_frame" src="'+json.list[i]['ar_upload']+'" width="100" height="75"><img class="res_scan hide" src="__APPURL__/Public/Images/Home/res_scan.png"><img class="res_click hide" src="__APPURL__/Public/Images/Home/res_click.png"></a><a class="res_title" target="_blank" href="__APPURL__/AuthResource/show/ar_id/'+json.list[i]['ar_id']+'" title="'+json.list[i]['ar_title']+'">'+json.list[i]['ar_title']+'</a></li>';
                }

            } else {
                res = '<li>暂无数据</li>';
            }

            $('.resource').html(res);
            $('.page').html(json.page);
        }, 'json')
    }


    // 异步查询该单元下的课文
    function syncLesson(_this) {
        $.post('__URL__/lists', 'co_id=' + $('input[name=co_id]').val() + '&l_id=' + _this.attr('rel') + '&l_title=' + $('input[name=l_title]').val(), function (json) {
            var htm = '';
            if (json) {
                for (var i=0, len=json.length; i<len; i++) {
                    htm += "<li rel='" + json[i]['l_id'] + "'>"
                        +"<span class='tree_switch tree_switch_plus kw_switch'></span>"
                        +"<a class='tree_unit_a child_node_a' rel='" + json[i]['l_id'] + "'>"
                        +"<span class='tree_branch child_node_span' rel='" + json[i]['l_id'] + "' title='" + json[i]['l_title'] + "'>" + json[i]['l_title'] + "</span>"
                        +"<span class='button add_c'></span>"
                        +"<span class='button edit_c'></span>"
                        +"<span class='button del_c'></span>"
                        +"</a>"
                        +"<ul class='stree stree_drag ui-sortable'></ul>"
                        +"</li>"
                }
            }
            $('input[name=l_title]').val('')
            _this.parent().next().html(htm);
            _this.parent().siblings('.ctree').slideToggle();

            // 这里需要做个判断，如果是页面一开始加载，同时此元素是最下方的单元
            if (onloadNum == 1) {
                if ($('.ctree li').size()) {
                    $('.ctree li').last().find('.kw_switch').click();
                    onloadNum++;
                }
            }
        }, 'json')
    }

    // 异步查询该课文下的课时
    function syncClasshour(_this) {
        $.post('__APPURL__/Classhour/lists', 'co_id=' + $('input[name=co_id]').val() + '&l_id=' + _this.attr('rel') + '&cl_title=' + $('input[name=cl_title]').val(), function (json) {
            var htm = '';
            if (json) {
                for (var i=0, len=json.length; i<len; i++) {
                    htm += '<li rel="' + json[i]['cl_id'] + '" c_id="' + json[i]['c_id'] + '" cro_id="' + json[i]['cro_id'] + '">'
                           +'<a class="tree_unit_a grandchild_node_a">'
                           +'<span class="tree_branch grandchild_node_span" rel="' + json[i]['cl_id'] + '" title="' + json[i]['cl_title'] + '" ar_id="' + json[i]['ar_id'] + '">' + json[i]['cl_title'] + '</span>'
                           +'<span class="button edit_ks" style="display: none;"></span>'
                           +'<span class="button del_ks" style="display: none;"></span>'
                           +'</a>'
                           +'</li>'
                }
            }
            $('input[name=cl_title]').val('');
            _this.parent().next().html(htm);
            _this.parent().siblings('.stree').slideToggle();
            ctree(_this.parent().siblings('.stree'));
        }, 'json')
    }

    // 课文拖拽排序
    function ctree(obj) {

        obj.sortable({
            opacity: 0.6 ,
            cursor: 'move',
            stop: function(event, ui) {

                // 样式名称
                var mould = obj.selector;

                // 存储排序后的id字段
                id = [];
                $($(this).children()).each(function (i) {
                    id[i] = $(this).attr('rel');
                })

                // 如果栏目下只有一个节点，便不排序
                if (id.length > 1) {
                    id = id.join(',');

                    // 单元，课文
                    if (mould == '.tree_drag' || mould == '.ctree_drag') {
                        $.post('__URL__/update', 'co_id=' + $('input[name=co_id]').val() + '&l_sort=' + id, function (json) {

                        }, 'json')

                    // 课时
                    } else {
                        $.post('__APPURL__/Classhour/updateSort', 'co_id=' + $('input[name=co_id]').val() + '&cl_sort=' + id, function (json) {

                        }, 'json')
                    }
                }
            }
        })
    }

    // 依据活动类型，返回活动样式
    function getTypeById(id) {
        var arr = ['', 'add_homework', 'add_execrise', 'add_text', 'add_link', 'add_read', 'add_talk'];
        return arr[id];
    }

    // 删除节点
    function delNode(_this, source) {
        if (source == 'lesson') {
            $.post('__URL__/delete', 'co_id=' + $('input[name=co_id]').val() + '&l_id=' + _this.parent().attr('rel'), function (json) {
                if (json.status == 1) {
                    _this.parent().parent().remove();
                } else {
                    showMessage(json.info);
                }
            }, 'json');
        } else {
            $.post('__APPURL__/Classhour/delete', 'co_id=' + $('input[name=co_id]').val() + '&cl_id=' + _this.prev().prev().attr('rel'), function (json) {
                if (json.status == 1) {
                    _this.parent().parent().remove();
                } else {
                    showMessage(json.info);
                }
            }, 'json');
        }
    }

    // 编辑节点
    function editNode(obj, source){

        // 取出当前的文本内容保存起来
        var text = obj.parent().find('.tree_branch').text();
        if (text != '') {
            $('input[name=inputName]').val(text);
        }
        obj.parent().find('.tree_branch').empty();

        var input = $("<input class='edit_node' onkeydown='keydown(event)'>");
        obj.parent().find('.tree_branch').append(input);
        var thisInput = obj.parent().find('.edit_node');
        thisInput.val($('input[name=inputName]').val());

        // 获取焦点
        input.focus();

        // 失去焦点 存储新值
        input.focusout(function(event) {
            var new_text = $.trim(input.val());
            if (new_text == '') {
                showMessage("名称不能为空");
                input.focus();
                return false;
            } else if (new_text.length > 15) {
                showMessage("输入的名称请不要超过15个字");
                input.focus();
                return false;
            } else {
                if ($.trim(text) != new_text) {

                    // 课文
                    if (source == 'lesson') {
                        $.post('__URL__/update', 'co_id=' + $('input[name=co_id]').val() + '&l_id=' + obj.prev().prev().attr('rel') + '&l_title=' + new_text, function (json) {
                            if (json.status == 0) {
                                showMessage(json.info);
                            }
                            obj.parent().children('.tree_branch').text(new_text);
                        }, 'json')

                    // 课时
                    } else if(source == 'classhour') {
                        $.post('__APPURL__/Classhour/update', 'co_id=' + $('input[name=co_id]').val() + '&l_id=' + obj.parents('.stree').parent('li').attr('rel') + '&cl_id=' + obj.prev().attr('rel') + '&cl_title=' + new_text, function (json) {
                            if (json.status == 0) {
                                showMessage(json.info);
                            }
                            obj.parent().children('.tree_branch').text(new_text);
                        }, 'json')
                    }
                }
                obj.parent().children('.tree_branch').text(new_text);
            }
        })
    }

    // 判断回车
    function keydown(e){
        var e = e || event;
        if (e.keyCode==13) {
            //ajaxSubmit($('.edit_node').val(), $('.edit_node').parent());
        }
    }

    function choose(obj) {
        if (obj.parent().hasClass('click')) {
            obj.parent().removeClass('click');
        } else {
            obj.parent().addClass('click');
        }
    }

    // 负责弹出子窗口的信息
    function showInfo(info, status) {
        showMessage(info, status);
    }

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
        popWidth = $("#add_module").width();
        popHeight = $("#add_module").height();
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

        // 计算弹出窗口的左上角Y的偏移量
        var popY = (windowHeight - popHeight) / 2;
        var popX = (windowWidth - popWidth) / 2;

        // 设定窗口的位置
        $("#Win_cover").show();
        $("#add_module").css("top",popY).css("left",popX).slideToggle("slow");
        closeWindow();
   }

    // 处理题型为3的json字符串
    function procJsonCode(jsonCode) {

        var str = eval('(' + jsonCode + ')');
        return str.join(':');
    }

    // 单选题
    function syncSingle(i, num, answer) {
        var num = num.split(',');
        var html = '';
        var answer = eval('(' + answer + ')');
        for (var j = 0; j < num.length; j++) {
            html += '<input type="radio" value="' + j + '" name="single' + i + '"';
            if (j == parseInt(answer)) {
                html += 'checked="checked"';
            }
            html += '><label class="option" style="cursor: pointer;">' + infor(j) + '</label>';
        }

        return html;
    }

    // 多选题
    function syncMulti(num, answer) {

        var num = num.split(',');
        var answer = eval('(' + answer + ')');
        var answer1 = answer[0].split(',');
        var html = '';
        for (var j = 0; j < num.length; j++) {
            tmp = syncArray(j, answer1);
            if (tmp) {
                html += tmp;
            } else {
                html += '<input type="checkbox" name="multiple" value="0"><label class="option" style="cursor: pointer;">' + infor(j) + '</label>';
            }
        }
        return html;
    }

    function syncArray(j, answer) {
        html = '';
        for (var jj = 0; jj < answer.length; jj++) {
            if (answer[jj] == j) {
                html += '<input type="checkbox" name="multiple" value="0" checked=""><label class="option" style="cursor: pointer;">' + infor(j) + '</label>';
            }
        }
        return html;
    }

    // 填空题
    function syncFill(num, answer) {
        var obj = eval('(' + answer + ')')
        var num = num.split(',');
        var html = '';
        for (var i = 0; i < num.length; i++) {
            html += '<li class="noline"><label style="cursor: pointer;">' + (i + 1) + '、' + obj[i] + '</label></li>';
        }
        return html;
    }


    // 判断题
    function syncJudge(i,num, answer) {
        var answer = eval('(' + answer + ')');
        answer = parseInt(answer);
        var html = '';
        html += '<input type="radio" name="judge' + i + '" ' + ((answer == 0) ? 'checked=""' : '') + '><label attr="1" style="cursor: pointer;" class="option"><img height="20" width="20" border="0" src="__APPURL__/Public/Images/Home/ok.png"></label>';
        html += '<input type="radio" name="judge' + i + '" ' + ((answer == 1) ? 'checked=""' : '') + '><label attr="2" style="cursor: pointer;" class="option"><img height="20" width="20" border="0" src="__APPURL__/Public/Images/Home/err.png"></label>'
        return html;
    }

    // 返回答案选项
    function infor(i) {
        var arr = new Array();
        arr[0] = 'A';
        arr[1] = 'B';
        arr[2] = 'C';
        arr[3] = 'D';
        arr[4] = 'E';
        arr[5] = 'F';
        return arr[i % 6];
    }

    // 已经题型id获取题型汉字信息
    function getIdByTopicType(id) {

        var word = '';

        switch (parseInt(id)) {
            case 1:
                word = '单项选择题';
                break;
            case 2:
                word = '多项选择题';
                break;
            case 3:
                word = '填空主观题';
                break;
            case 4:
                word = '判断题';
                break;
            case 5:
                word = '简答题';
                break;
        }

        return word;
    }

    // 简答
    function syncShort(answer) {
        var answer = eval('(' + answer + ')');
        return answer;
    }

    // 初始化多文件上传
    function reloadFileUpload() {
        var maxSize = <?php echo ($maxSize); ?>;
        $("#uploader").pluploadQueue({
            // General settings
            runtimes: 'html4,html5,flash,silverlight,gears,browserplus',
            url: '/Classhour/acceptFiles',
            max_file_size : maxSize+'mb',
            chunk_size : '10mb',
            unique_names: true,
            // Resize images on clientside if we can
            resize: {width: 320, height: 240, quality: 90},
            dragdrop: true,
            // Specify what files to browse for
            filters: [
                {title: "Image files", extensions: "png,jpg,gif,bmp,jpeg"},
                {title: "Zip files", extensions: "zip,rar"},
                {title: "Audio files", extensions: "mp3,m4a,m4v"},
                {title: "Mindmark files", extensions: "db"},
                {title: "Video files", extensions: "mpeg,mp4,avi,rmvb,rm,wmv,fla,3gp,flv"},
                {title: "Docs files", extensions: "txt,doc,xls,ppt,docx,xlsx,pptx,pdf"}
            ],
            // Flash settings
            flash_swf_url: '/Public/Js/Public/plupload/plupload.flash.swf',
            // Silverlight settings
            silverlight_xap_url: '/Public/Js/Public/plupload/plupload.silverlight.xap',
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
            },
        });
    }

    // 活动拖拽
    function dragAct(obj) {
        obj.sortable({
            opacity: 0.6 ,
            cursor: 'move',
            stop: function(event, ui) {

                // 存储排序后的id字段
                id = [];
                $($(this).children()).each(function (i) {
                    id[i] = $(this).attr('rel');
                })

                // 如果栏目下只有一个节点，便不排序
                if (id.length > 1) {
                    id = id.join(',');

                    $.post('__APPURL__/Activity/updateSort', 'ta_id=' + $(this).parents('.tache').attr('rel') + '&act_sort=' + id, function (json) {

                    }, 'json')
                }
            }
        });
        obj.disableSelection();
    }

</script>
<div class="warp">
        <!-- 存储上次单击的课时名称-->
        <input type="hidden" autocomplete="off" name="inputName" value=""/>
        <!-- 存储上次单击的环节名称-->
        <input type="hidden" autocomplete="off" name="tacheInput" value=""/>
        <!-- 存储上次单击的环节ID-->
        <input type="hidden" autocomplete="off" name="ta_id" value=""/>
        <!-- 课程ID -->
        <input type="hidden" autocomplete="off" name="co_id" value="<?php echo ($course["co_id"]); ?>">
        <!-- 课文所属科目名称-->
        <input type="hidden" autocomplete="off" name="subjectName" value="<?php echo ($course["co_subject"]); ?>"/>
        <!-- 课文名称，异步加载课文 -->
        <input type="hidden" autocomplete="off" name="l_title" value="">
        <!-- 课时名称，异步加载课时-->
        <input type="hidden" autocomplete="off" name="cl_title" value="">
        <!-- 当前课文ID，为环节、活动准备-->
        <input type="hidden" autocomplete="off" name="l_id" value="">
        <!-- 当前课时ID，为环节、活动准备-->
        <input type="hidden" autocomplete="off" name="cl_id" value="">
        <!-- 当前课时的资源ID，为课时添加资源准备-->
        <input type="hidden" autocomplete="off" name="ar_id" value="">
        <!-- 标明是题目资源检索窗口，还是图片资源检索窗口-->
        <input type="hidden" autocomplete="off" name="resourceFlag" value="0">
        <!--存储活动绑定的班级-->
        <input type="hidden" autocomplete="off" name="c_id" value="<?php echo ($c_id); ?>"/>
        <!--存储活动绑定的群组-->
        <input type="hidden" autocomplete="off" name="cro_id" value="<?php echo ($cro_id); ?>"/>
        <!--存储课时总资源页数-->
        <input type="hidden" autocomplete="off" name="pages" value="0"/>
        <!--存储是备课页面调用资源窗口-->
        <input type="hidden" autocomplete="off" name="resFlag" value="0"/>
        <input type="hidden" autocomplete="off" name="resTitleFlag" value="-1"/>
        <!-- 左侧树形结构开始 -->
        <div class="lesson_left fl">
            <div class="add_unit"><span>添加单元</span></div>
            <ul class="tree fl tree_drag" id="">
                <?php if(is_array($lesson)): $i = 0; $__LIST__ = $lesson;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li rel='<?php echo ($vo["l_id"]); ?>'>
                    <span class='tree_switch tree_switch_plus unit_switch'></span>
                    <a class='tree_unit_a parent_node_a' rel='<?php echo ($vo["l_id"]); ?>'>
                        <span class='tree_branch' rel='<?php echo ($vo["l_id"]); ?>' title="<?php echo ($vo["l_title"]); ?>"><?php echo ($vo["l_title"]); ?></span>
                        <span class='button add'></span>
                        <span class='button edit'></span>
                        <span class='button del'></span>
                    </a>
                    <ul class='ctree ctree_drag ui-sortable'></ul>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <!-- 收缩按钮 -->
            <a class="left-shrink l-shrink-open"></a>
        </div>
        <!-- 左侧树形结构结束 -->
        <!-- 中部开始 -->
        <div class="lesson_main fl">
            <div class="main_top">
                <p class="courseTitle"><?php echo ($title); ?></p>
                <a class="back" <?php if(($c_id) == "0"): ?>href="__APPURL__/Course"<?php else: ?>href="__APPURL__/Class"<?php endif; ?>>返回</a>
                <a href="javascript:void(0)" class="lesson_lead fl">导入课程目录</a>
            </div>
            <div class="main_top">
                <p class="title"></p>
                <p class="order_switch">
                    <a href="javascript:void(0)" class="list"></a>
                    <a href="javascript:void(0)" class="thumb on"></a>
                </p>
                <a class="publish fl">发布</a>
                <!-- <a href="javascript:void(0)" class="add_res fl">添加资源</a> -->
            </div>
            <div class="tache_box fl">
                <div class="tacheList">
                </div>
                <div class="add_tache">
                    <a class="add_node fl">+添加环节</a>
                    <a class="resourcePage"></a>
                </div>
            </div>
        </div>
        <!-- 中部结束 -->
        <!-- 右侧开始 -->
        <div class="lesson_right fl">
            <!-- <p class="title">最新资源</p> -->
            <ul class="res_tab">
                <li class="on">
                    <span>资源</span>
                    <a class="add_res" href="javascript:void(0)" title="添加资源"></a>
                </li>
                <!-- <li>推荐资源</li> -->
            </ul>
            <div class="resBox">
                <span class="arrowPage pageUp"></span>
                <span class="arrowPage pageDown"></span>
                <div class="scrollArea">
                    <div class="res_box">
                        <ul class="res_box_ul"></ul>
                    </div>
                </div>
            </div>
            <!-- 收缩按钮 -->
            <a class="right-shrink r-shrink-open"></a>
        </div>
        <!-- 右侧结束 -->
    </div>
</div>

<!-- 添加教学组件弹窗 -->
<div id="Win_cover">
    <div id="add_module" title="教学组件">
        <div class="closeWin"></div>
        <ul>
            <?php if(is_array($type)): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li attr="<?php echo ($key); ?>"><?php echo ($vo["name"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    <span class="clear"></span>
    </div>
</div>

<input type="hidden" name="p_actType" id="TmpValBox"></input>

    <div class="clear"></div>
    <div class="foot_bot"></div>
    <div class="foot_top"></div>
    <div id="footer">
        <div class="nav back1"></div>
        Copyright &copy; 2007-2011 北京金商祺移动互联 All Rights Reserved.
    </div>
</body>
</html>
<div id="reslibrary" title="资源库检索">
    <ul class="filter">
        <li class="topic_search">
            <input type="text" name="a_search" value="">
            <button class="searchTopic" type="" value="搜索"></button>
        </li>
        <li class="topic_type">
            <label>选择题型：</label>
            <div>
                <span rel="0">全部</span>
                <span rel="1">单选</span>
                <span rel="2">多选</span>
                <span rel="3">填空</span>
                <span rel="4">判断</span>
                <span rel="5">简答</span>
            </div>
        </li>
        <li class="topic_area">
            <label>学科领域：</label>
            <div class="subjectAdd">
                <span class="add_subjectArea">+添加</span>
            </div>
        </li>
        <li class="topic_point">
            <label>知识点：</label>
            <div>添加</div>
        </li>
    </ul>
    <div class="sTopicList">
        <div class="resourceContent">
        </div>
        <div class="page">
        </div>
    </div>
</div>
<div class="tagcustom" title="我的标签">
    <ul>
        <li class="taglist">
            <label class="la fl">已添加</label>
            <div class="list_append"></div>
        </li>
        <li class="taglist">
            <label class="la fl">添加标签：</label>
            <input type="text" value="" class="tag_input"/>
            <a class="tag_append">添加</a>
        </li>
        <li class="taglist">
            <label class="la fl">推荐：</label>
            <label class="fr list_lab">换一批</label>
        </li>
    </ul>
    <ul class="append_list list_hand">
        <?php if(is_array($topicTerm)): $i = 0; $__LIST__ = $topicTerm;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$topic): $mod = ($i % 2 );++$i;?><li class="taglist" style="display: none;">
                <label class="la fl">&nbsp;</label>
                <div class="fl tag_room">
                    <?php if(is_array($topic)): $i = 0; $__LIST__ = $topic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span class="hand" rel="<?php echo ($vo["tt_id"]); ?>"><?php echo ($vo["tt_title"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<div id="read_reslibrary" title="资源库检索" style="display:none">
    <ul class="filter">
        <li class="topic_search">
            <input type="text" name="b_search" value="">
            <button class="searchTopic" type="" value="搜索"></button>
        </li>
        <li class="topic_type">
            <label>资源类型：</label>
            <div>
                <span rel="0" class="on">全部</span>
                <?php if(is_array($model)): $i = 0; $__LIST__ = $model;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$model): $mod = ($i % 2 );++$i;?><span rel="<?php echo ($model["m_id"]); ?>"><?php echo ($model["m_title"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </li>
        <li class="topic_area">
            <label>学科领域：</label>
            <div class="subjectAdd">
                <span class="add_subjectArea" attr="1">+添加</span>
            </div>
        </li>
    </ul>
    <ul class="res_list resource">
    </ul>
    <div class="page">
    </div>
</div>
<div id="res_reslibrary" title="资源库检索" style="display:none">
    <ul class="filter">
        <li class="topic_search">
            <input type="text" name="b_search" value="">
            <button class="searchTopic" type="" value="搜索"></button>
        </li>
        <li class="topic_type">
            <label>资源类型：</label>
            <div>
                <span rel="0" class="on">全部</span>
                <span rel="1">图片</span>
                <span rel="2">视频</span>
                <span rel="3">音频</span>
                <span rel="4">文档</span>
            </div>
        </li>
        <li class="topic_area">
            <label>学科领域：</label>
            <div class="subjectAdd">
                <span class="add_subjectArea" attr="1">+添加</span>
            </div>
        </li>
    </ul>
    <ul class="res_list resource">
    </ul>
    <div class="page">
    </div>
</div>
<div class="xin_add" title="指定课时">
    <ul>
    </ul>
    <div class="clear"></div>
    <div class="xin_noe">
        <div class="xin_sain">
            <label class="fl">已指定的班级和群组:</label>
            <div class="fl sa_click allClassGroup"></div>
            <div class="clear"></div>
            <label class="fl">我的全部班级:</label>
            <div class="fl sa_click bindClass">
                <?php if(is_array($bindInfo["class"])): $i = 0; $__LIST__ = $bindInfo["class"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span rel="<?php echo ($vo["c_id"]); ?>"><?php echo ($vo["c_title"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="clear"></div>
            <label class="fl">我的全部群组:</label>
            <div class="fl sa_click bindGroup">
                <?php if(is_array($bindInfo["group"])): $i = 0; $__LIST__ = $bindInfo["group"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span rel="<?php echo ($vo["cro_id"]); ?>"><?php echo ($vo["cro_title"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<div id="addResBox" title="添加资源">
    <div class="uploadBox">
        <span class="win_fromLibrary">+从资源库中检索</span>
        <div id="uploader" class="act_li_r">
            <p>上传资源控件加载错误，可能是您的浏览器不支持 Flash, Silverlight, Gears, BrowserPlus 或 HTML5，请检查</p>
        </div>
        <ul class="lessonList">
        </ul>
    </div>
</div>
<style>
.warp {overflow:hidden;}
.current {background:#93E5BD;}
.uploadedList {float:left;line-height:20px;}
.uploadedList p {float: left;font-size: 16px;height: 38px;line-height: 38px;}
.uploadedList li{float: left;width: 110px;height: auto;position: relative;margin:0 2px 10px 10px;}
.uploadedList li a {color:#5f5f5f;}
.uploadedList li a.li_cover {float: left;display: inline;position: relative;margin-top: 10px;margin-left: 5px;}
.uploadedList li img.del {float:right;display:none;margin-top:10px;margin-right:10px;cursor:pointer;}
.uploadedList li a.file_name {float:left;width:100px;overflow:hidden;white-space:nowrap;text-align:center;margin-left:5px;}
.uploadedList li a.del_res {display:none;position: absolute;right: -3px;top: 2px;width: 16px;height: 16px;background: url("/Public/Images/Home/mould.png") no-repeat -367px -1px;cursor: pointer;}
.uploadedList li a.del_res:hover {background:url("__APPURL__/Public/Images/Home/mould.png") no-repeat -367px -19px;}
.editResource {cursor:pointer};
</style>