<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/resource_index.css" />
<div class="filter">
    <div class="operate">
        <div class="xin_ope">
            <h3>第一步：获取excel文件模板，并填写内容</h3>
            <div id="opearte-one" class="fl">
                <a href="../Uploads/AuthImport/<?php echo ($template["template"]); ?>"><?php echo ($template["authType"]); ?>数据导入模板下载（Excel格式为：Excel 97—2003 工作簿)</a>
                <a href="../Uploads/AuthImport/<?php echo ($template["example"]); ?>"><?php echo ($template["authType"]); ?>参照表</a>
            </div>
            <div id="notice">
                <div id="notice-con">
                    <span class="fl"><div>注意事项：</div></span>
                    <ol>
                        <li>请将<?php echo ($template["authType"]); ?>信息填写至DataImport表中。</li>
                        <li>如不清楚如何填写，请点击下载查看<?php echo ($template["authType"]); ?>参照表。</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="list">
            <h3>第二步：导入文件</h3>
            <span>注意：只允许xls文件上传，不支持高版本xlsx</span>
            <form  METHOD="POST" action="__URL__/upload/" enctype="multipart/form-data" onsubmit='return check()'>
                教师数据来源&nbsp&nbsp&nbsp&nbsp<input type="file" name="file">
                <button class="submitBtn fin" type="submit" value="">确定</button>
            </form>
        </div>
    </div>
</div>