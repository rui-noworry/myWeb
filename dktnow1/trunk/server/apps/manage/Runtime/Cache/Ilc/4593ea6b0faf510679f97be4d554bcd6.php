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
 var listObj = {"data_check": "2%","data_id": "8%" ,"data_name": "20%","data_explain": "14%","data_default": "14%","data_sort": "12%","data_status": "12%","data_hand": "10%","data_edit": "4%","data_child": "8%"};
 setWidth(listObj);
})

    //生成缓存表
    function createCache(id) {
        var keyValue;
        if (id) {
            keyValue = id;
        } else {
            keyValue = getSelectCheckboxValues();
        }
        if (!keyValue) {
            alert('请选择生成项');
            return false;
        }

        if (window.confirm('确定生成缓存表吗？')) {
            location.href =  URL+"/createCache/id/"+keyValue;
        }
    }

    // 同步数据
    function sync(id) {
        var keyValue;
        if (id) {
            keyValue = id;
        } else {
            keyValue = getSelectCheckboxValues();
        }
        if (!keyValue) {
            alert('请选择要同步数据的类型');
            return false;
        }

        if (window.confirm('确定要同步数据吗？')) {
            location.href =  URL+"/sync/id/"+keyValue;
        }
    }
</script>
    <form name="form" method="post" action="__URL__/index">
        <div class="model_search">
            <a>名称：</a>
            <input TYPE="text" title="名称查询" name="m_name" >
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
        <a class="forbidden">
            <i></i>
            禁用
        </a>
        <a class="resume">
            <i></i>
            启用
        </a>
        <a onclick="sync();">
            <i></i>
            同步
        </a>
        <a onclick="createCache();">
            <i></i>
            缓存
        </a>

    </div>
    <div id="system_list">
        <div>
            <ul class="list_hea">
                <li>
                    <input class="data_check" type="checkbox" name="allcheck" onclick="CheckAll('system_list')"/>
                    <span class="data_id">编号</span>
                    <span class="data_name">名称</span>
                    <span class="data_explain">说明</span>
                    <span class="data_default">属性列表</span>
                    <span class="data_status">状态</span>
                    <span class="data_hand">操作</span>
                </li>
            </ul>
        </div>
        <div>
            <ul class="list_list list_hea" id="list_main">

                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li>
                        <input class="data_check" type="checkbox" value="<?php echo ($list["m_id"]); ?>" name="key"/>
                        <span class="data_id"><?php echo ($list["m_id"]); ?></span>
                        <span class="data_name"><?php echo ($list["m_name"]); ?></span>
                        <span class="data_explain"><?php echo ($list["m_title"]); ?></span>
                        <span class="data_default"><?php echo ($list["m_list"]); ?></span>
                        <span class="data_status"><?php echo (getstatus($list["m_status"])); ?></span>
                        <span class="data_hand"><?php echo (showstatus($list["m_status"],$list['m_id'])); ?></span>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>

        </div>
        <div class="page">
            <?php echo ($page); ?>
        </div>
    </div>