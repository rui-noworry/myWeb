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
<script type="text/javascript" src="/Public/Js/Public/provincesCity.js"></script><script type="text/javascript" src="/Public/Js/Public/provincesdata.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/system_add.css" />
<script type="text/javascript">

    // 验证
    function check() {

        if ($("input[name=s_name]").val() == '') {
            alert("学校名称必须填写");
            $("input[name=s_name]").focus();
            return false;
        }

        if ($("input[name=s_address]").val() == '') {
            alert("学校的详细地址必须填写");
            $("input[name=s_address]").focus();
            return false;
        }

        if (!$("input[type=checkbox]").is(':checked')) {
            alert("请选择学校类型");
            return false;
        }
    }
    $(function(){
        // 籍贯
        setProvince("province", "s_region", '');
    })
</script>
    <div class="title_hea">
        <i class="fl">学校添加</i>
        <a href="__URL__/">返回列表</a>
    </div>
    <form method="post" action="__URL__/insert/" onsubmit="return check();">
        <ul class="add_option">
            <li>
                <label>学校名称：</label>
                <input class="fl" type="text" name="s_name" value=""/>
            </li>
            <li>
                <label>学校简介：</label>
                <textarea placeholder="内容不能超过300字符" name="s_note"></textarea>
            </li>
            <li>
                <label>地区：</label>
                <span id="province"></span>
                <input type="hidden" name="s_region" value="<?php echo ($vo["s_region"]); ?>"/>
            </li>
            <li>
                <label>详细地址：</label>
                <input class="fl" type="text" name="s_address"/>
            </li>
            <li>
                <label>学校类型：</label>
                <?php if(is_array($vo)): $tk = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ty): $mod = ($tk % 2 );++$tk;?><div class="examine_edit"><input type="checkbox" name="s_type[]" value="<?php echo ($tk); ?>" style="width:auto;" /><span><?php echo ($ty); ?><span></div><?php endforeach; endif; else: echo "" ;endif; ?>
            </li>
            <li>
                <label>开通应用：</label>
                <?php if(is_array($appList)): $ak = 0; $__LIST__ = $appList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$app): $mod = ($ak % 2 );++$ak;?><div class="examine_edit"><input type="checkbox" name="s_apps[]" value="<?php echo ($ak); ?>"  style="width:auto;" /><span><?php echo ($app); ?></span></div><?php endforeach; endif; else: echo "" ;endif; ?>
            </li>
            <li>
                <label>学校logo：</label>
                <input type="file" name="s_logo">
            </li>
            <li class="coBtn">
                <button class="save fin" value="" type="submit">保存</button>
                <button class="reset fin" value="" type="reset">清除</button>
            </li>
        </ul>
    </form>