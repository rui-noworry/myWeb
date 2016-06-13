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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/resource_index.css" />
<script type="text/javascript">
$(function(){
 var listObj = {"data_check": "2%","data_id": "8%" ,"data_name": "10%","data_type": "10%","data_grade": "10%","data_sem": "10%","data_version": "10%","data_subject": "6%","data_status": "8%","data_hand": "4%","data_edit": "4%","data_add": "4%","data_child": "8%"};
 setWidth(listObj);

})

</script>
    <form name="form" method="post" action="__URL__">
        <div class="model_search">
            <a>姓名：</a>
            <input TYPE="text" title="名称查询" name="a_nickname" >
            <a class="search">搜索</a>
            <div class="clear"></div>
            <a>学校：</a>
            <select name="s_id">
                <option value="0">请选择</option>
                <?php if(is_array($school)): $i = 0; $__LIST__ = $school;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$school): $mod = ($i % 2 );++$i;?><option value="<?php echo ($school["s_id"]); ?>" <?php if(($s_id) == $school["s_id"]): ?>selected<?php endif; ?>><?php echo ($school["s_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </form>
    <div class="tools fl">
        <a class="add">
            <i></i>
            新增
        </a>
        <a class="edit">
            <i></i>
            编辑
        </a>
        <a class="forbidden">
            <i></i>
            禁用
        </a>
        <a class="resume">
            <i></i>
            启用
        </a>
    </div>
    <div id="system_list">
        <div>
            <ul class="list_hea">
                <li>
                    <input class="data_check" type="checkbox" name="allcheck" onclick="CheckAll('system_list')"/>
                    <span class="data_id">编号</span>
                    <span class="data_name">用户名</span>
                    <span class="data_type">姓名</span>
                    <span class="data_grade">教授课程</span>
                    <span class="data_sem">手机</span>
                    <span class="data_version">登陆次数</span>
                    <span class="data_subject">登陆IP</span>
                    <span class="data_status">状态</span>
                    <span class="data_hand">操作</span>
                </li>
            </ul>
        </div>
        <div>
            <ul class="list_list list_hea" id="list_main">

                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li>
                        <input class="data_check" type="checkbox" value="<?php echo ($list["a_id"]); ?>" name="key"/>
                        <span class="data_id"><?php echo ($list["a_id"]); ?></span>
                        <span class="data_name"><?php echo ($list["a_account"]); ?></span>
                        <span class="data_type"><?php echo ($list["a_nickname"]); ?></span>
                        <span class="data_grade"><?php echo ($list["t_subject"]); ?></span>
                        <span class="data_sem"><?php echo ($list["a_tel"]); ?></span>
                        <span class="data_version"><?php echo ($list["a_login_count"]); ?></span>
                        <span class="data_subject"><?php echo ($list["a_last_login_ip"]); ?></span>
                        <span class="data_status"><?php echo (getstatus($list["a_status"])); ?></span>
                        <span class="data_hand"><a href="javascript:edit(<?php echo ($list["a_id"]); ?>)">编辑</a></span>

                    </li><?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>
        </div>
        <div class="page">
            <?php echo ($page); ?>
        </div>
    </div>