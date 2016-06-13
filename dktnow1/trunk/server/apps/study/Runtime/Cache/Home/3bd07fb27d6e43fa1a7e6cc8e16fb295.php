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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/homework.css" />

<!--多文件上传plupload插件-->
<link rel="stylesheet" href="/Public/Js/Public/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css">
<script type="text/javascript" src="/Public/Js/Public/plupload/plupload.full.js"></script>
<script type="text/javascript" src="/Public/Js/Public/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="/Public/Js/Public/plupload/i18n/zh-cn.js"></script>
<!--结束-->

<script type="text/javascript">
    var SysSecond;
    var InterValObj;
    var sign;
    var maxTime;

    $(function(){

        // 删除简答题图片
        $('.del_shortAnswer').click(function(){

            var _this = $(this);
            if(confirm("确定要删除该答案吗?")) {
                $.post('__URL__/del_picAnswer', 'filename='+$(this).prev().attr('alt'), function(json){
                    if (json) {
                            _this.parent().remove();
                        }
                }, 'json');
            }
        });


        if ($('.download a').size() == 0) {
            $('.attachment').hide();
        }

        var width = parseInt($('.do_homework .bar i').html());

        // 题目正确率显示
        $('.do_homework .bar i').animate({width: width+'%'}, "slow");

        // 作业多文件上传
        reloadFileUpload();

        // 计算做作业用时
        if ($('input[name=ad_status]').val() != 4 && $('input[name=ad_storage]').val() != 1) {

            // 开始时间
            if (!$('input[name=ad_use_time]').val()) {
                SysSecond = 0;
            } else {
                SysSecond = parseInt($('input[name=ad_use_time]').val());
            }
            maxTime = <?php echo ($homework['ap_complete_time']); ?>;
            InterValObj = window.setInterval("Timer('+',"+maxTime+")", 1000);    //间隔函数，1秒执行

            // 关闭计时器
            $('.closeTimer').click(function(){

                var reset = "<span class='resetTimer'>开启计时</div>";
                $(this).hide();
                $(this).prev().hide();
                $(this).after(reset);
            })

            // 开启计时器
            $(document).on('click','.resetTimer',function(){

                $(this).hide();
                $(this).prev().show();
                $(this).prev().prev().show();
            })
        } else {
            $('.dohw_bottom').hide();
        }


        //显示正确率
        var per = $('.precision .bar').width();
        if(per == 0){
            $('.precision i').html('');
        }

        $('.publish').click(function(){

            $('input[name=ad_storage]').val(1);
        });

        $('.storage').click(function(){
            $('input[name=ad_storage]').val(0);
        });

        $(document).on('click', '.dohw_bottom .btn img', function () {

            // 如果用户上传了附件，但忘了点击上传按钮，自动点击上传
            if ($('.plupload_buttons').css('display') != 'none' && $('#uploader_filelist').children().size() > 0) {
                $('.plupload_start').click();
                var uploader = $('#uploader').pluploadQueue();
                // Files in queue upload them first
                if (uploader.files.length > 0) {
                    // When all files are uploaded submit form
                    uploader.bind('UploadComplete', function() {
                        if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                            check();
                        }
                    });
                    uploader.start();
                }
            } else {
                check();
            }
        });

        // 单机未转码的资源，自动下载
        $(document).on('click', '.ListFiles', function () {
            var rel = $(this).attr('rel');
            if ($(this).attr('trans') == 0) {
                if (confirm('该附件未转码，是否下载该附件？')) {
                    location.href = "/Activity/download/?id=" + rel;
                }
            } else {
                $(this).attr({'target':'_blank', 'href':'__APPURL__/AuthResource/show/ar_id/' + rel});
            }
        })

    })

    // 设置单选按钮
    function setRadio(num, rel) {
        $(".to_answer"+num).val(rel);
    }

    // 设置多选按钮
    function setCheckBox(num) {

        $(".topicObj").each(function() {
            if ($(this).attr('attr') == num) {
                var str = '';
                $(this).find('input[type=checkbox]:checked').each(function() {
                    str += ',' + $(this).attr('rel');
                })

                str = str.slice(1);
                $(".to_answer"+num).val(str);
            }
        })

    }

    // 检查表单
    function check() {

        var flag = true;

        // 循环验证每个题
        $('.topicObj').each(function() {

            var i = $(this).attr('attr');
            var n_type = $(".to_type"+i).val();

            // 验证是否选择答案
            if (n_type != 3 && n_type != 5) {
                if ($(".to_answer"+i).val() == "-1") {
                    flag = false;
                }
            }

        })

        $("input[type=text]").each(function() {

            if ($(this).val() == '') {
                flag = false;
            }
        })

        $("textarea").each(function() {

            if ($(this).val() == '') {
                flag = false;
            }
        })

        if (flag == false) {
            if (confirm('您有未回答的题目，确定要提交吗？')) {
                submitForm();
            } else {
                return false;
            }
        }

        submitForm();

    }

    function submitForm() {

        // 获取作业用时
        var hour = parseInt($('.hour').text()) * 60 * 60;
        var mintue = parseInt($('.mintue').text()) * 60;
        var second = parseInt($('.second').text());

        $('input[name=ad_use_time]').val(hour + mintue + second);

        // 获取练习超时
        if ($('input[name=ad_status]').val() != 4) {

            if ($('.oversecond').text() != '') {

                var overhour = parseInt($('.overhour').text()) * 60 * 60;
                var overmintue = parseInt($('.overmintue').text()) * 60;
                var oversecond = parseInt($('.oversecond').text());

                $('input[name=ad_out_time]').val(overhour + overmintue + oversecond);
                $('input[name=ad_use_time]').val(hour + mintue + second + overhour + overmintue + oversecond);

            }

        }

        // 等待转码
        Loading();
        $('form:first').attr({'action': '__URL__/insert', 'method': 'post'}).submit();
    }

    // 初始化多文件上传
    function reloadFileUpload() {
        var maxSize = <?php echo ($maxSize); ?>;
        $("#uploader").pluploadQueue({
            // General settings
            runtimes: 'html4,html5,flash,silverlight,gears,browserplus',
            url: '__URL__/acceptFiles',
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

</script>

<form method="post" class="qqx" action="">
<div class="warp">
<!-- 作业用时 -->
<input name="ad_use_time" value="<?php echo ($homeworkData['ad_use_time']); ?>" type="hidden" />
<!-- 练习超时 -->
<input name="ad_out_time" value="" type="hidden" />
<!-- 作业状态 -->
<input name="ad_status" value="<?php echo ($homeworkData['ad_status']); ?>" type="hidden" />

<!-- 是否存为草稿 -->
<input name="ad_storage" value="<?php echo ($homeworkData['ad_storage']); ?>" type="hidden" />

    <div class="do_homework">
        <div id="book_ring"></div>
        <div class="title">
            <?php echo ($homework["act_title"]); ?>
            <?php if(($homework["act_type"]) == "1"): ?><a href="__APPURL__/Homework">返回</a>
            <?php else: ?>
                <a href="__APPURL__/Classwork">返回</a><?php endif; ?>
        </div>
        <div class="precision">
            <label>正确率：</label>
            <span class="bar"><i><?php if($homeworkData["ad_persent"] != ''): echo ($homeworkData["ad_persent"]); ?>%<?php else: endif; ?></i></span>
        </div>
        <div class="attachment fl">
            <div class="download">
                <label>附件下载：</label>
                <?php if(is_array($uploadFiles)): $i = 0; $__LIST__ = $uploadFiles;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:void(0)" rel="<?php echo ($key); ?>" trans="<?php echo ($vo["ar_is_transform"]); ?>" class="ListFiles"><?php echo ($vo["ar_title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="upload">
                <label class="fl">附件上传：</label>
                <div id="uploader" style="background:#F3F2F0; margin-left: 10px; width:500px;" class="fl">
                    <p>上传资源控件加载错误，可能是您的浏览器不支持 Flash, Silverlight, Gears, BrowserPlus 或 HTML5，请检查</p>
                </div>
            </div>
        </div>

        <input type="hidden" value="<?php echo ($homework["ap_id"]); ?>" name="ap_id" />
        <div class="dohw_list fl">
        <?php $showKey = 0; ?>
            <?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i; if(($list["to_type"]) == "1"): ?><div class="topicObj fl" attr="<?php echo ($list["to_id"]); ?>" ty="<?php echo ($list["to_type"]); ?>">
                        <p class="tpoic_top <?php if(($list["ex_er"]) == "0"): ?>answer_error<?php endif; ?>" >第<?php echo ($showKey+1); ?>题：单选题</p>
                        <div class="tpoic_main fl">
                            <div class="tpoic_main_r">
                                <div class="content"><?php echo ($list["to_title"]); ?></div>

                                <?php if(($answerShow) == "1"): ?><div class="answer">
                                        <label>正确答案：</label><?php echo ($list["exact"]); ?>
                                    </div><?php endif; ?>


                                <div class="s_answer">
                                    <label class="la">答案：</label>
                                    <input type="hidden" name="to_answer[<?php echo ($list["to_id"]); ?>]" class="to_answer<?php echo ($list["to_id"]); ?>" value="<?php echo ($list["stu_answer"]); ?>">
                                    <input type="hidden" name="to_type[<?php echo ($list["to_id"]); ?>]" class="to_type<?php echo ($list["to_id"]); ?>" value="<?php echo ($list["to_type"]); ?>">
                                    <div class="student_answer">
                                            <?php $showOption = 1; ?>
                                            <?php if(is_array($list["to_option"])): $i = 0; $__LIST__ = $list["to_option"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;?><input type="radio" name="single to_answer<?php echo ($list["to_id"]); ?>" value="0" onclick="setRadio('<?php echo ($list["to_id"]); ?>', '<?php echo ($showOption-1); ?>')" <?php if((intval($list["stu_answer"])) > "-1"): if(($showOption-1) == $list["stu_answer"]): ?>checked="checked"<?php endif; endif; ?>><label class="option" style="cursor: pointer;"><?php echo ($tit[$showOption-1]); ?></label>

                                                <?php $showOption ++; endforeach; endif; else: echo "" ;endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><?php endif; ?>
                <?php if(($list["to_type"]) == "2"): ?><div class="topicObj fl" attr="<?php echo ($list["to_id"]); ?>" ty="<?php echo ($list["to_type"]); ?>">
                    <p class="tpoic_top <?php if(($list["ex_er"]) == "0"): ?>answer_error<?php endif; ?>">第<?php echo ($showKey+1); ?>题：多选题</p>
                        <div class="tpoic_main fl">
                            <div class="tpoic_main_r">
                                <div class="content"><?php echo ($list["to_title"]); ?></div>
                                <?php if(($answerShow) == "1"): ?><div class="answer">
                                        <label>正确答案：</label><?php echo ($list["exact"]); ?>
                                    </div><?php endif; ?>
                                <div class="s_answer">
                                    <label class="la">答案：</label>
                                    <input type="hidden" name="to_answer[<?php echo ($list["to_id"]); ?>]" class="to_answer<?php echo ($list["to_id"]); ?>" value="<?php echo ($list["stu_answer"]); ?>">
                                    <input type="hidden" name="to_type[<?php echo ($list["to_id"]); ?>]" class="to_type<?php echo ($list["to_id"]); ?>" value="<?php echo ($list["to_type"]); ?>">
                                    <div class="student_answer">
                                        <?php $showOption = 1; ?>
                                        <?php if(is_array($list["to_option"])): $i = 0; $__LIST__ = $list["to_option"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;?><input type="checkbox" name="multiple to_answer" value="0" onclick="setCheckBox(<?php echo ($list["to_id"]); ?>)" rel="<?php echo ($showOption-1); ?>" <?php if(($list["stu_answer"]) != ""): if(in_array(($showOption-1), is_array($list["stu_answer"])?$list["stu_answer"]:explode(',',$list["stu_answer"]))): ?>checked="checked"<?php endif; endif; ?>><label class="option" style="cursor: pointer;"><?php echo ($tit[$showOption-1]); ?></label>
                                            <?php $showOption ++; endforeach; endif; else: echo "" ;endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><?php endif; ?>
                <?php if(($list["to_type"]) == "3"): ?><div class="topicObj fl" attr="<?php echo ($list["to_id"]); ?>" ty="<?php echo ($list["to_type"]); ?>">
                    <p class="tpoic_top <?php if(($list["ex_er"]) == "0"): ?>answer_error<?php endif; ?>">第<?php echo ($showKey+1); ?>题：填空题</p>
                        <div class="tpoic_main fl">
                            <div class="tpoic_main_r">
                                <div class="content"><?php echo ($list["to_title"]); ?></div>
                                <?php if(($answerShow) == "1"): ?><div class="answer">
                                        <label>正确答案：</label><?php echo ($list["exact"]); ?>
                                    </div><?php endif; ?>
                                <div class="s_answer">
                                    <label class="la">答案：</label>
                                    <input type="hidden" name="to_type[<?php echo ($list["to_id"]); ?>]" class="to_type<?php echo ($list["to_id"]); ?>" value="<?php echo ($list["to_type"]); ?>">
                                    <div class="student_answer" type="text">
                                         <?php $showOption = 1; ?>
                                         <?php if(is_array($list["to_option"])): $kkk = 0; $__LIST__ = $list["to_option"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($kkk % 2 );++$kkk; echo ($showOption); ?>、<input type="text" name="to_answer[<?php echo ($list["to_id"]); ?>][]" value="<?php echo ($list['stu_answer'][$kkk-1]); ?>" />
                                            <?php $showOption ++; endforeach; endif; else: echo "" ;endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><?php endif; ?>
                <?php if(($list["to_type"]) == "4"): ?><div class="topicObj fl" attr="<?php echo ($list["to_id"]); ?>" ty="<?php echo ($list["to_type"]); ?>">
                    <p class="tpoic_top <?php if(($list["ex_er"]) == "0"): ?>answer_error<?php endif; ?>">第<?php echo ($showKey+1); ?>题：判断题</p>
                        <div class="tpoic_main fl">
                            <div class="tpoic_main_r">
                                <div class="content"><?php echo ($list["to_title"]); ?></div>
                                 <?php if(($answerShow) == "1"): ?><div class="answer">
                                        <label>正确答案：</label><?php echo ($list["exact"]); ?>
                                    </div><?php endif; ?>
                                <div class="s_answer">
                                    <label class="la">答案：</label>
                                    <input type="hidden" name="to_answer[<?php echo ($list["to_id"]); ?>]" class="to_answer<?php echo ($list["to_id"]); ?>" value="<?php echo ($list["stu_answer"]); ?>">
                                    <input type="hidden" name="to_type[<?php echo ($list["to_id"]); ?>]" class="to_type<?php echo ($list["to_id"]); ?>" value="<?php echo ($list["to_type"]); ?>">
                                    <div class="<?php echo ($list["stu_answer"]); ?>">
                                        <input type="radio" name="judge to_answer<?php echo ($list["to_id"]); ?>" onclick="setRadio(<?php echo ($list["to_id"]); ?>, 0)" <?php if(($list["stu_answer"]) == "0"): ?>checked="checked"<?php endif; ?>><label class="option" style="cursor: pointer;" attr="1"><img src="__APPURL__/Public/Images/Home/ok.png" width="20" height="20" border="0"></label>
                                        <input type="radio" name="judge to_answer<?php echo ($list["to_id"]); ?>" onclick="setRadio(<?php echo ($list["to_id"]); ?>, 1)" <?php if(($list["stu_answer"]) == "1"): ?>checked="checked"<?php endif; ?>><label class="option" style="cursor: pointer;" attr="0"><img src="__APPURL__/Public/Images/Home/err.png" width="20" height="20" border="0"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><?php endif; ?>
                <?php if(($list["to_type"]) == "5"): ?><div class="topicObj fl" attr="<?php echo ($list["to_id"]); ?>" ty="<?php echo ($list["to_type"]); ?>">
                    <p class="tpoic_top <?php if(($list["ex_er"]) == "0"): ?>answer_error<?php endif; ?>">第<?php echo ($showKey+1); ?>题：简答题</p>
                        <div class="tpoic_main fl">
                            <div class="tpoic_main_r">
                                <div class="content"><?php echo ($list["to_title"]); ?></div>
                                <?php if(($answerShow) == "1"): ?><div class="answer">
                                        <label>正确答案：</label><?php echo ($list["exact"]); ?>
                                    </div><?php endif; ?>
                                <div class="s_answer">
                                    <label class="la">答案：</label>
                                    <input type="hidden" name="to_type[<?php echo ($list["to_id"]); ?>]" class="to_type<?php echo ($list["to_id"]); ?>" value="<?php echo ($list["to_type"]); ?>">
                                    <div class="student_answer">
                                        <textarea name="to_answer[<?php echo ($list["to_id"]); ?>]" style="width:400px;height:80px" type="text"><?php if($list["stu_answer"] == -1): else: echo ($list["stu_answer"]); endif; ?></textarea>
                                    </div>
                                </div>
                                <div class="shorAnswer">
                                    <?php if(is_array($list["picture_answer"])): $i = 0; $__LIST__ = $list["picture_answer"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pic): $mod = ($i % 2 );++$i;?><div>
                                            <img src="<?php echo ($pic["url"]); ?>" alt="<?php echo ($pic["filename"]); ?>" style="width:600px;height:450px;"/>
                                            <a href="javascript:void(0);" class="del_shortAnswer"></a>
                                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
                            </div>
                        </div>
                    </div><?php endif; ?>
                <?php $showKey ++; endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <?php if($homeworkData["ad_status"] == 4): ?><div class="scolded">
                <label>老师评语：</label>
                <p><?php echo ($homeworkData["ad_remark"]); ?></p>
            </div><?php endif; ?>
    </div>


    <div class="clear"></div>
</div>

<div class="dohw_bottom">
    <div class="timer">
        <img src="__APPURL__/Public/Images/Home/use_time.png">
        <span class="useSeconds">
            <cite class="hour"></cite>时
            <cite class="mintue"></cite>分
            <cite class="second"></cite>秒
        </span>
        <span class="overTime hide">
            <cite>你已超出：</cite>
            <cite class="overhour"></cite>时
            <cite class="overmintue"></cite>分
            <cite class="oversecond"></cite>秒
        </span>
    </div>
    <span class="closeTimer">关闭计时</span>
    <div class="btn fr">
        <img class="storage"/>
        <img class="publish"/>
    </div>
</div>
</form>