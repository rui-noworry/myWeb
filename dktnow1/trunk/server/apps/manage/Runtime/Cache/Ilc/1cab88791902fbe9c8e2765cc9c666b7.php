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
    <form name="form" method="post" action="__URL__">
        <div class="model_search">
            <a>名称：</a>
            <input TYPE="text" title="名称查询" name="r_name" >
            <a class="search">搜索</a>
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
    </div>
    <div id="system_list">
        <div>
            <ul class="list_hea">
                <li>
                    <input class="data_check" type="checkbox" name="allcheck" onclick="CheckAll('system_list')"/>
                    <span class="data_id">编号</span>
                    <span class="data_name">组名</span>
                    <span class="data_explain">上级组</span>
                    <span class="data_status">状态</span>
                    <span class="data_hand">操作</span>
                </li>
            </ul>
        </div>
        <div>
            <ul class="list_list list_hea" id="list_main">

                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li>
                        <input class="data_check" type="checkbox" value="<?php echo ($list["r_id"]); ?>" name="key"/>
                        <span class="data_id"><?php echo ($list["r_id"]); ?></span>
                        <span class="data_name"><?php echo ($list["r_name"]); ?></span>
                        <span class="data_explain"><?php echo (getgroupname($list["r_pid"])); ?></span>
                        <span class="data_status"><?php echo (getstatus($list["r_status"])); ?></span>
                        <span class="data_hand"><?php echo (showstatus($list["r_status"],$list['r_id'])); ?></span>
                        <span class="data_hand"><a href="javascript:app(<?php echo ($list["r_id"]); ?>)">授权</a></span>
                        <span class="data_hand"><a href="javascript:user(<?php echo ($list["r_id"]); ?>)">用户列表</a></span>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>
        </div>
        <div class="page">
            <?php echo ($page); ?>
        </div>
    </div>