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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/system_add.css" />
    <div class="title_hea">
        <i class="fl">年级课程配置</i>
        <a href="__URL__/">返回列表</a>
    </div>
    <form method="post" action="__URL__/insert/" onsubmit="return check();">
        <ul class="add_option">
            <li>
                <label>学校：</label>
                <select name="s_id">
                    <option attr="" value="0">请选择</option>
                    <?php if(is_array($school)): $i = 0; $__LIST__ = $school;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$school): $mod = ($i % 2 );++$i;?><option value="<?php echo ($school["s_id"]); ?>" attr="<?php echo ($school["s_type"]); ?>"><?php echo ($school["s_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </li>
            <li>
                <label>学制：</label>
                 <select name="gc_type">
                    <option value="0">请选择</option>
                </select>
            </li>
            <li class="major">
                <label>专业：</label>
                 <select name="gc_major">
                    <option value="0">请选择</option>
                </select>
            </li>
            <li>
                <label>年级：</label>
                <select name="gc_grade">
                    <option  value="0" >请选择</option>
                </select>
            </li>
            <li>
                <label class="fl">学科：</label>
                <div id="subjects">

                </div>
            </li>
            <li>
                <label>状态：</label>
                 <select class="small bLeft"  name="gc_status">
                    <option value="1">启用</option>
                    <option value="9">禁用</option>
                </select>
            </li>
            <li class="coBtn">

                <button class="save fin" value="" type="submit">保存</button>
                <button class="reset fin" value="" type="reset">清除</button>
            </li>
        </ul>
    </form>
<div class="schoolType" style="display:none;">
    <?php if(is_array($schoolType)): $i = 0; $__LIST__ = $schoolType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$schoolType): $mod = ($i % 2 );++$i;?><span><?php echo ($schoolType); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
    <script type="text/javascript">
    <!--
        $(function () {

            // 隐藏专业ID
            $('.major').hide();

            // 根据学制, 年级选择学科
            var gc_type = $("select[name=gc_type] option:selected").val();
            var gc_grade = $("select[name=gc_grade] option:selected").val();
            var s_id = $('select[name=s_id] option:selected').val();

            // 页面加载
            listSubject(0, s_id, gc_type, gc_grade, 'subjects');

            // 根据年级选择学科
            $("select[name=gc_grade]").change(function() {
                var gc_type = $("select[name=gc_type] option:selected").val();
                var gc_grade = $("select[name=gc_grade] option:selected").val();
                var s_id = $('select[name=s_id] option:selected').val();
                var ma_id = $('.major').css('display') == 'none' ? 0 : $('select[name=gc_major]').val();
                listSubject(ma_id, s_id, gc_type, gc_grade, 'subjects');
            })

            // 通过学校获取学段
            $('select[name=s_id]').change(function() {

                // 复原
                $('select[name=gc_type]').html('<option value="0">请选择</option>');
                $('.major').hide();
                $('select[name=gc_grade]').html('<option value="0">请选择</option>');
                $('select[name=gc_course]').val(0);

                // 获取学段
                var sType = $('select[name=s_id] option:selected').attr('attr');
                var str = '<option value="0">请选择</option>';
                if (sType != '') {
                    var typeArr = sType.split(',');

                    for (var p in typeArr) {

                        str += '<option value="'+typeArr[p]+'">'+$('.schoolType span').eq(typeArr[p] - 1).html()+'</option>';
                    }
                }
                $('select[name=gc_type]').html(str);
            })

            // 通过学段获取年级
            $('select[name=gc_type]').change(function() {
                if ($(this).val() == 4) {
                    $('.major').show();
                    listGradeOption($('select[name=s_id]').val(), $(this).val(), 'gc_major');
                } else {
                    $('.major').hide();
                    listGradeOption($('select[name=s_id]').val(), $(this).val(), 'gc_grade');
                }
            })

            // 通过专业获取年级
            $(document).on('change', 'select[name=gc_major]', function () {
                listGradeOption($('select[name=s_id]').val(), $(this).val(), 'gc_grade', 0, 'major');
            })

        })

        function listSubject(ma_id, s_id, gc_type, gc_grade, objId){

            if (gc_type && gc_grade) {

                $.post('__URL__/getSubjects', 's_id='+s_id+'&gc_type='+gc_type+'&gc_grade='+gc_grade+'&ma_id='+ma_id, function(json){

                    if (json) {

                        var str = '';
                        $('#'+objId).html('');

                        for (var i = 0; i < json.length; i++) {

                            str += '<input type="checkbox" value="'+json[i]['id']+'" name="gc_course[]" class="radio_sex"';

                            if (json[i]['checked']) {
                                str += ' checked disabled';
                            }

                            str += '/><span class="fl">'+json[i][0]+'</span>';
                        }

                        $('#'+objId).append(str);
                    }
                }, 'json');

            }
        }

        function check() {

            if ($("select[name=gc_type]").val() == 0) {
                    alert('请选择学制');
                    return false;
            }

            if ($('.major').css('display') != 'none' && $("select[name=gc_major]").val() == 0) {
                alert('请选择专业');
                return false;
            }

            if ($("select[name=gc_grade]").val() == 0) {
                    alert('请选择年级');
                    return false;
            }

            if ($("input[name=gc_course[]]:checked").val() == undefined) {
                    alert('请选择课程');
                    return false;
            }
        }
    //-->
    </script>