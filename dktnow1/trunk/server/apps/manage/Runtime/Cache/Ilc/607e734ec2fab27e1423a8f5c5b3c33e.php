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
 var listObj = {"data_check": "2%","data_id": "8%" ,"data_name": "20%","data_explain": "14%","data_default": "14%","data_sort": "12%","data_status": "12%","data_hand": "10%","data_edit": "4%","data_add": "8%","data_child": "8%"};
 setWidth(listObj);
})

function child(id){
    location.href = URL+"/index/t_pid/"+id;
}

function add(id) {
    location.href = URL+"/add/t_pid/"+id;
}
</script>
    <h2 class="res"><?php if(($t_level) == "1"): ?>一级<?php endif; if(($t_level) == "2"): ?>二级<?php endif; if(($t_level) == "3"): ?>三级<?php endif; if(($t_level) == "4"): ?>四级<?php endif; ?>标签列表<?php if(($t_level) != "1"): ?><a href="javascript:history.go(-1);" class="fr stydy_return">返回上一级</a><?php endif; ?></h2>
    <form name="form" method="post" action="__URL__">
        <div class="model_search">
            <a>名称：</a>
            <input TYPE="text" title="名称查询" name="t_name"/>
            <a class="search">搜索</a>
            <div class="clear"></div>
            <a>学科：</a>
            <select name="t_subject">
                <option value="">请选择</option>
                <?php if(is_array($t_subject)): $ck = 0; $__LIST__ = $t_subject;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$course): $mod = ($ck % 2 );++$ck;?><option value="<?php echo ($ck); ?>" <?php if(($choose_subject) == $ck): ?>selected<?php endif; ?>><?php echo ($course); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
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
        <a class="del">
            <i></i>
            删除
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
                    <span class="data_name">名称</span>
                    <span class="data_explain">所属学科</span>
                    <span class="data_status">状态</span>
                    <span class="data_hand">操作</span>
                </li>
            </ul>
        </div>
        <div>
            <ul class="list_list list_hea" id="list_main">

                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li>
                        <input class="data_check" type="checkbox" value="<?php echo ($list["t_id"]); ?>" name="key"/>
                        <span class="data_id"><?php echo ($list["t_id"]); ?></span>
                        <span class="data_name"><?php echo ($list["t_name"]); ?></span>
                        <span class="data_explain"><?php echo (gettypenamebyid($list["t_subject"],'COURSE_TYPE')); ?></span>
                        <span class="data_status"><?php echo (getstatus($list["t_status"])); ?></span>
                        <span class="data_hand"><?php echo (showstatus($list["t_status"],$list['t_id'])); ?></span>
                        <span class="data_edit"><a href="javascript:edit(<?php echo ($list["t_id"]); ?>)">编辑</a></span>
                        <span class="data_add"><a href="javascript:add(<?php echo ($list["t_id"]); ?>)">新增</a></span>
                        <span class="data_child"><a href="javascript:child(<?php echo ($list["t_id"]); ?>)">子节点</a></span>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>
        </div>
        <div class="page">
            <?php echo ($page); ?>
        </div>
    </div>