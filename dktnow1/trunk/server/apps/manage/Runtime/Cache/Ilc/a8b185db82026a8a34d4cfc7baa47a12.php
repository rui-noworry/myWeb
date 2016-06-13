<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <title>『<?php echo (C("web_name")); ?>管理平台』</title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/header.css" /><link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/public.css" />
    <script type="text/javascript" src="/Public/Js/Public/jquery-1.9.1.js"></script><script type="text/javascript" src="/Public/Js/Public/jquery-ui.js"></script><script type="text/javascript" src="/Public/Js/Public/public.js"></script><script type="text/javascript" src="/Public/Js/Ilc/commonManage.js"></script>
    <script language="JavaScript">
    <!--
        //指定当前组模块URL地址
        var URL = '__URL__';
        var APP = '__GROUP__';
        var PUBLIC = '__PUBLIC__';
    //-->
    </script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/jquery-ui.css" />

<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/resource_index.css" />

<script type="text/javascript">
$(function(){

    //设置课程老师弹窗
    $('.tea_main .cour').click(function(){
        $('.add_right').html('');
        $(".tea_add").dialog("open");
    })
    //设置课程老师弹窗
    $(".tea_add").dialog({
        draggable: true,        // 是否允许拖动,默认为 true
        resizable: true,        // 是否可以调整对话框的大小,默认为 true
        autoOpen: false,        // 初始化之后,是否立即显示对话框,默认为 true
        position :'center',       // 用来设置对话框的位置
        stack : true,       // 对话框是否叠在其他对话框之上。默认为 true
        modal: true,       // 是否模式对话框,默认为 false(模式窗口打开后，页面其他元素将不能点击，直到关闭模式窗口)
        bgiframe: true,         // 在IE6下,让后面遮罩层盖住select
        width: '500',
        height: '350',

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

                var obj = $(this);

                // 初始化选中的ID
                var chooseId = $('.add_right ul li.on').attr('attr');
                var oldId = $('.cour').attr('attr');

                // 如果有选中的
                if (chooseId && chooseId != oldId) {

                    $.post("__APPURL__/Ilc/School/setManager", 'a_id=' + chooseId + '&s_id=' + $("input[name=s_id]").val() + '&old_id=' + oldId, function(data) {

                        if (data != 0) {
                            var str="";
                            $('.add_right li').each(function(){
                                var n_text = $(this).text();
                                var id = $(this).attr('attr');

                                if ($(this).hasClass('on')) {
                                    str = n_text;
                                    teachId = id;
                                }
                            })
                            $('.tea_main li').children('span').eq(0).text(str);
                            $('.cour').attr('attr', teachId);

                            // 关闭窗口
                            $(obj).dialog('close');
                        } else {

                            // 未保存成功，恢复按钮状态
                            alert('操作失败');
                        }
                    });
                } else {

                    // 关闭窗口
                    $(this).dialog('close');
                }

            },
            取消: function() {
                $(this).dialog('close');
            }
        }
    })
    //设置管理员点击
    $(document).on('click', '.add_right li', function(){
        $(this).addClass('on').siblings().removeClass('on');
    })

    //根据条件筛选管理员
    $(".save").click(function(){

        // 点击一次后不许再点
        $(this).attr('disabled', true);

        var postStr = 'a_nickname=' + $('input[name=a_nickname]').val() + '&a_tel=' + $('input[name=a_tel]').val() + '&a_type=2&is_ajax=1';

        //提交
        $.post("__APPURL__/Ilc/Auth/lists", postStr, function(json) {

            var str = '';

            if (json) {

                str += '<ul>';
                for (var i = 0; i < json.length; i ++) {

                    str += '<li attr="' + json[i]['a_id'] + '">' + json[i]['a_nickname'] + '</li>';
                }
                str += '</ul>';
            } else {
                str += '<ul><li>没有符合条件的人员</li></ul>';
            }

            $('.add_right').html(str);
            $(".save").attr('disabled', false);
        }, 'json');

   });

})
</script>
<h2 class="res study_add"><?php echo ($school["s_name"]); ?></h2>
<a href="__URL__" class="stydy_return fr">返回列表</a>
<div class="clear"></div>
<h2 class="res">学校超管</h2>
<input type="hidden" name="s_id" value="<?php echo ($school["s_id"]); ?>">
<ul class="tea_main">
    <li>
        <span><?php echo (getauthnamebyid($school["a_id"])); ?></span>
        <span class="cour"  attr="<?php echo ($school["a_id"]); ?>">设置</span>
    </li>
</ul>
<div class="clear"></div>
<h2 class="res"><?php echo ($school["s_name"]); ?>管理组列表</h2>
<ul class="admin_title">
    <li>
        <span>管理组</span>
        <span>管理员</span>
    </li>

    <?php if(is_array($group)): foreach($group as $key=>$row): ?><li>
            <span><?php echo ($row["sr_name"]); ?></span>
            <span><?php echo ($row["user"]); ?></span>
        </li><?php endforeach; endif; ?>

</ul>
<div class="tea_add" title="设置管理员">
    <div class="add_left">
        <ul>
            <li class="fl">
                <label><i></i>姓名：</label>
                <input class="fl" type="text" name="a_nickname" value=""/>
            </li>
            <li class="fl">
                <label><i></i>手机：</label>
                <input class="fl" type="text" name="a_tel" value=""/>
            </li>
            <li class="fr">
                <button class="save finn" value="" type="submit">提交</button>
            </li>
        </ul>
    </div>
    <div class="add_right">

    </div>
</div>