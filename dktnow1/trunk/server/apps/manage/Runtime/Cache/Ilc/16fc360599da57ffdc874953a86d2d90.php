<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/resource_index.css" />
<script type="text/javascript" src="/Public/Js/Public/jquery-1.9.1.js"></script><script type="text/javascript" src="/Public/Js/Public/jquery-ui.js"></script><script type="text/javascript" src="/Public/Js/Public/public.js"></script>

<?php if(($data['re_is_transform']) == "1"): ?><!--文档-->
    <?php if(($data['m_id']) == "4"): ?><script type="text/javascript" src="/Public/Js/Public/flexPlugin/js/flexpaper.js"></script><script type="text/javascript" src=" /Public/Js/Public/flexPlugin/js/flexpaper_handlers.js"></script><?php endif; ?>

    <!--视频-->
    <?php if(($data['m_id']) == "2"): ?><script type="text/javascript" src="/Public/Js/Home/ckplayer/ckplayer.js"></script>
        <script>
            var flashvars={
                f:'<?php echo ($data["filePath"]); ?>',//视频地址
                c:'0',
                e:'1',//视频结束后的动作，0是调用js函数，1是循环播放，2是暂停播放，3是调用视频推荐列表的插件，4是清除视频流并调用js功能和1差不多
                p:'1',//视频默认0是暂停，1是播放
                g:'0',//视频直接g秒开始播放
                };
            var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always'};
            var attributes={id:'ckplayer_a1',name:'ckplayer_a1'};
            swfobject.embedSWF('/Public/Js/Home/ckplayer/ckplayer.swf', 'a1', '598', '408', '10.0.0','/Public/Js/Home/ckplayer/expressInstall.swf', flashvars, params, attributes); //播放器地址，容器id，宽，高，需要flash插件的版本，flashvars,params,attributes

            var watchtime;
            var wattime=-1;
            var watt=false;//默认没有计时
            var ptime=0;
            function aboutstr(str,f) {
                //查看str字符里是否有f
                var about=false;
                var strarray=new Array();
                var farray=new Array();
                farray=f.split(",");
                if(str) {
                    for(var i=0;i<farray.length;i++){
                        strarray=str.split(farray[i]);
                        if(strarray.length>1){
                            about=true;
                            break;
                        }
                    }
                }
                return about;
            }

            // 实时的监听播放器里各状态的值
            function ckplayer_status(str){

                if(str=='103' || str=='101'){
                    if(watt){
                        watt=false;
                        window.clearInterval(watchtime);
                    }
                }
                // 获取当前播放时间
                if(aboutstr(str,'nowtime:')){
                    ptime = parseInt(str.replace('nowtime:',''));
                    var twoStep  = ptime.toFixed(2);
                    var millisecond = parseFloat(twoStep)*1000;
                    var newTime = new Date(millisecond);
                    var hours = parseFloat(newTime.getHours() - 8);
                    var minutes = parseFloat(newTime.getMinutes());
                    var second = parseFloat(newTime.getSeconds());
                    if (hours < 10) {
                        hours = '0' + parseFloat(newTime.getHours() - 8);
                    }
                    if (minutes < 10) {
                        minutes = '0' + newTime.getMinutes();
                    }
                    if (second < 10) {
                        second = '0' + newTime.getSeconds();
                    }
                    var formatTime = hours + ":" + minutes + ':' + second;
                    document.getElementById('currentTime').innerHTML = formatTime;
                }
            }

            // 暂停视频并获取当前播放时间点
            function getTime(){
                swfobject.getObjectById('ckplayer_a1').ckplayer_pause();
            }
        </script><?php endif; ?>

    <!--音频-->
    <?php if(($data['m_id']) == "3"): ?><link rel="stylesheet" type="text/css" href="/Public/Js/Public/jplayer/skin/jplayer.blue.monday.css" />
        <script type="text/javascript" src="/Public/Js/Public/jplayer/js/jquery.jplayer.min.js"></script>
        <script>
            $(function () {
                $("#jquery_jplayer_1").jPlayer({
                    ready: function () {
                        $(this).jPlayer("setMedia", {
                            m4v: "<?php echo ($data['filePath']); ?>",
                            poster: "http://www.jplayer.org/video/poster/Big_Buck_Bunny_Trailer_480x270.png"
                        });
                    },
                    swfPath: "js",
                    supplied: "m4v",
                    size: {
                        width: "700px",
                        height: "360px",
                        cssClass: "jp-video-360p"
                    }
                });
            })
        </script><?php endif; endif; ?>
<script type="text/javascript">
<!--
    $(function(){

        // 属性点击
        $('.pub_res span').click(function(){
            if ($(this).hasClass('on')){
                $(this).removeClass('on');
                $(this).prev().val('');
                $(this).next().val('');
            } else {
                $(this).addClass('on');
                $(this).siblings('span').removeClass('on');
                $(this).parent().find('input:first').val($(this).text());
                $(this).parent().find('input:last').val($(this).attr('attr'));
            }
        })

        // 默认属性选中
        $('.pub_res span').each(function(){

            if ($(this).text() == $(this).parent().prev('input').val()) {
                $(this).addClass('on');
                $(this).parent().find('input:first').val($(this).text());
                $(this).parent().find('input:last').val($(this).attr('attr'));
            }
        });

        // 栏目选择
        $('.choose').click(function(){
            getChild(0, 1);
        });

        // 获取子栏目
        $(document).on('click', '.listCategory li', function(){
            var level = $(this).attr('level');
            $('.listCategory li').each(function() {
                if ($(this).attr('level') > level) {
                    $(this).remove();
                }
            })
            $(this).addClass('on').siblings().removeClass('on');
            getChild($(this).attr('attr'));
        });
    })

    function check() {

        if ($("input[name=re_title]").val() == '') {
                showMessage('资源名不能为空', 0);
                return false;
        }
    }

    function getChild(id, type) {

        var str = '';

        if (type == 1) {
            if ($('.listCategory li').size() == 0) {
                str += '<li attr="0" level="0" type="0">系统栏目</li>';
                $('.listCategory ul').html(str);return;

            }
        } else {

            if ($('.listCategory').css('display') == 'none') {
                $('.listCategory').show();
            } else {
                $.post('__URL__/findSub', 'id='+id, function(json) {
                    var str = '';

                    if (json) {
                        $.each(json, function(i, data){

                            if (data['rc_pid'] == id && data['s_id'] == $('.listCategory li.on').attr('type')) {
                                str += '<li attr="' + data['rc_id'] + '" level="' + data['rc_level'] + '" type="' + data['s_id'] + '">' + getLevel(data['rc_level']) + data['rc_title'] + '</li>';
                            }
                        })
                    }

                    if (!str) {
                        var t_val = $('.listCategory li.on').html();
                        var reg=/&nbsp;/g;
                        var new_val = t_val.replace(reg,'');
                        $('.choose').html(new_val);
                        $('input[name=rc_id]').val($('.listCategory li.on').attr('attr'));
                        $('.listCategory.on').removeClass('on');
                        $('.listCategory').hide();
                    } else {
                        $(str).insertAfter($('.listCategory li.on'));
                        $('.listCategory').show();
                    }

                }, 'json')
            }
        }
    }

    function getLevel(level) {

        var result = '';
        for (var i = 0; i < level; i ++) {

            result += '&nbsp;&nbsp;&nbsp;&nbsp;';
        }

        return result;
    }
//-->
</script>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
<h2 class="res study_add">编辑资源</h2>
<a href="__URL__/" class="stydy_return fr">返回列表</a>
<form method="post" action="__URL__/update/" onSubmit="return check();">
    <input name="rc_id" value="<?php echo ($data["rc_id"]); ?>" type="hidden" />
    <ul class="add_option">
        <li>
            <label><i></i>所属栏目：</label>
            <span class="choose_edit"><?php if($data["rc_title"] != ''): echo ($data["rc_title"]); else: ?>无<?php endif; ?></span>
            <span class="choose" style="padding-left:90px;">选择</span>
            <div class="listCategory list_add">
                <ul style="padding-left:50px;">

                </ul>
            </div>
        </li>
        <li>
            <label><i></i>资源名称：</label>
            <input class="fl" type="text" name="re_title" value="<?php echo ($data['re_title']); ?>"/>
        </li>
        <li>
            <label>资源预览：</label>
            <?php if($data['re_is_transform'] == 0): ?><tr>
                        <td class="tRight" >资源下载：</td>
                        <td class="tLeft">
                            <div>

                                <div class="r_play">
                                    <span style="color:#ff0000">未转码</span>
                                    <div><a href="__URL__/download/id/<?php echo ($data['re_id']); ?>"><?php echo ($data['re_title']); ?></a></div>
                                </div>

                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php if(($data['m_id']) == "1"): ?><tr>
                            <td class="tLeft">
                                <div><img src="<?php echo ($data['filePath']); ?>" /></div>
                            </td>
                        </tr><?php endif; ?>
                    <?php if(($data['m_id']) == "2"): ?><tr>
                            <td class="tLeft">
                                <div>
                                    <div class="r_play">
                                        <div id="video" style="position:relative;z-index: 100;width:658px;height:408px;float: left;margin-bottom:10px;"><div id="a1"></div></div>
                                    </div>
                                </div>
                            </td>
                        </tr><?php endif; ?>
                    <?php if(($data['m_id']) == "3"): ?><tr>
                            <td class="tLeft">
                                <div>
                                    <!-- 音频播放 -->
                                    <div id="jp_container_1" class="jp-video jp-video-360p"  style="width:700px;">
                                        <div class="jp-type-single">
                                            <div id="jquery_jplayer_1" class="jp-jplayer"></div>
                                            <div class="jp-gui" style="width:700px;">
                                                <div class="jp-video-play">
                                                    <a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
                                                </div>
                                                <div class="jp-interface">
                                                    <div class="jp-progress">
                                                        <div class="jp-seek-bar">
                                                            <div class="jp-play-bar"></div>
                                                        </div>
                                                    </div>
                                                    <div class="jp-current-time"></div>
                                                    <div class="jp-duration"></div>
                                                    <div class="jp-controls-holder">
                                                        <ul class="jp-controls">
                                                            <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
                                                            <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
                                                            <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
                                                            <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
                                                            <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
                                                            <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
                                                        </ul>
                                                        <div class="jp-volume-bar">
                                                            <div class="jp-volume-bar-value"></div>
                                                        </div>
                                                        <ul class="jp-toggles">
                                                            <li><a href="javascript:;" class="jp-full-screen" tabindex="1" title="full screen">full screen</a></li>
                                                            <li><a href="javascript:;" class="jp-restore-screen" tabindex="1" title="restore screen">restore screen</a></li>
                                                            <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
                                                            <li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr><?php endif; ?>
                    <?php if(($data['m_id']) == "4"): ?><tr>
                            <td class="tLeft">
                                <div class="fl">
                                    <div id="documentViewer" class="flexpaper_viewer" style="width:658px;height:500px;margin-bottom:10px;"></div>
                                    <script type="text/javascript">
                                        $('#documentViewer').FlexPaperViewer(
                                            { config : {
                                                SWFFile :'<?php echo ($data["filePath"]); ?>',
                                                Scale : 0.6,
                                                ZoomTransition : 'easeOut',
                                                ZoomTime : 0.5,
                                                ZoomInterval : 0.2,
                                                FitPageOnLoad : true,
                                                FitWidthOnLoad : false,
                                                FullScreenAsMaxWindow : false,
                                                ProgressiveLoading : false,
                                                MinZoomSize : 0.2,
                                                MaxZoomSize : 5,
                                                SearchMatchAll : false,
                                                InitViewMode : 'Portrait',
                                                RenderingOrder : 'flash,html',
                                                StartAtPage : '',
                                                ViewModeToolsVisible : true,
                                                ZoomToolsVisible : true,
                                                NavToolsVisible : true,
                                                CursorToolsVisible : true,
                                                SearchToolsVisible : true,
                                                WMode : 'window',
                                                localeChain: 'en_US'
                                            }}
                                        );
                                    </script>
                                </div>
                            </td>
                        </tr><?php endif; endif; ?>

                <?php if($attribute != ''): ?><li><label class="fl"><i></i>选择属性：</label>

                     <?php if(is_array($attribute)): $k = 0; $__LIST__ = $attribute;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$atr): $mod = ($k % 2 );++$k;?><input name="default" value="<?php echo ($atr["default"]); ?>" attr="<?php echo ($atr["at_name"]); ?>" type="hidden" class="fl" />
                        <div class="pub_name pub_res">

                            <?php if(($atr["at_type"]) == "1"): ?><input type='text' name="text[<?php echo ($k); ?>][are_value]" value='<?php echo ($atr["default"]); ?>' attr='<?php echo ($atr["at_is_required"]); ?>'/>
                                <input type='hidden' name="text[<?php echo ($k); ?>][are_name]" value='<?php echo ($atr["at_name"]); ?>'/><?php endif; ?>

                            <?php if(($atr["at_type"]) == "2"): ?><input type='hidden' name="text[<?php echo ($k); ?>][are_value]" value=''/>
                                <div class="clear"></div>
                                <?php if(is_array($atr["at_extra"])): $i = 0; $__LIST__ = $atr["at_extra"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$extra): $mod = ($i % 2 );++$i;?><span attr="<?php echo ($atr["at_name"]); ?>"><?php echo ($extra); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                                <input type='hidden' name="text[<?php echo ($k); ?>][are_name]" value=''/><?php endif; ?>
                        </div>
                        </li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
        </li>
        <li>
            <label><i></i>资源推荐：</label>
            <select name="re_recommend">
                <?php if(is_array($recommend)): $i = 0; $__LIST__ = $recommend;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$recommend): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if(($key) == $data['re_recommend']): ?>selected<?php endif; ?>><?php echo ($recommend); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </li>
        <li class="coBtn">
            <input type="hidden" name="re_id" value="<?php echo ($data['re_id']); ?>" >
            <button class="fin save " value="" type="submit">添加</button>
            <button class="fin reset" value="" type="reset">清除</button>
        </li>
    </ul>
</form>