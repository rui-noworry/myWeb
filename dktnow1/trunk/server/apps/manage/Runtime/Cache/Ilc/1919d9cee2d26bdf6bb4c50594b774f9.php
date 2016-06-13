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

<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/system_add.css" />
<script type="text/javascript">
function check() {

    var name_val = $.trim($('input[name=sn_name]').val());
    var title_val = $.trim($('input[name=sn_title]').val());

    if (name_val == "") {

        alert("名称不能为空")
        return false;
    }

    if (title_val == "") {

        alert("显示名不能为空")
        return false;
    }
}
</script>
    <div class="title_hea">
        <i class="fl">编辑</i>
        <a href="__URL__/">返回列表</a>
    </div>
    <form method="post" action="__URL__/update/" onsubmit="return check();">
        <ul class="add_option">
            <li>
                <label>名称：</label>
                <input class="fl" type="text" name="sn_name" value="<?php echo ($vo["sn_name"]); ?>"/>
            </li>
            <li>
                <label>显示名：</label>
                <input class="fl" type="text" name="sn_title" value="<?php echo ($vo["sn_title"]); ?>"/>
            </li>
            <li>
                <label>链接</label>
                <input class="fl" type="text" name="sn_url" value="<?php echo ($vo["sn_url"]); ?>"/>
            </li>
            <li>
                <label>归属</label>
                <select class="fl" name="sn_pid">
                    <option value="0">无</option>
                    <?php if(is_array($nodes)): $i = 0; $__LIST__ = $nodes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$no): $mod = ($i % 2 );++$i;?><option value="<?php echo ($no["sn_id"]); ?>" <?php if(($vo["sn_pid"]) == $no["sn_id"]): ?>checked<?php endif; ?>><?php echo ($no["sn_title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </li>
            <li>
                <label><i>*</i>状态：</label>
                <select class="fl" name="sn_status">
                    <option <?php if(($vo["sn_status"]) == "1"): ?>selected<?php endif; ?> value="1">启用</option>
                    <option <?php if(($vo["sn_status"]) == "9"): ?>selected<?php endif; ?> value="9">禁用</option>
                </select>
            </li>
            <li class="coBtn">
                <input type="hidden" name="sn_id" value="<?php echo ($vo["sn_id"]); ?>" >
                <button class="save fin" value="" type="submit">保存</button>
                <button class="reset fin" value="" type="reset">清除</button>
            </li>
        </ul>
    </form>