<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/public.css" /><script type="text/javascript" src=" /Public/Js/Public/jquery-1.9.1.js"></script><script type="text/javascript" src=" /Public/Js/Public/public.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/hour.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/jquery-ui.css" />
<script type="text/javascript" src="/Public/Js/Home/jquery-ui.js"></script>
<script type="text/javascript" src="/Public/Js/Public/ueditor/editor_config.js"></script><script type="text/javascript" src=" /Public/Js/Public/ueditor/editor_all.js"></script><link rel="stylesheet" type="text/css" href=" /Public/Js/Public/ueditor/themes/default/ueditor.css" />
<script type="text/javascript" src="/Public/Js/Public/jquery-ui-timepicker-addon.js"></script>

<!--多文件上传plupload插件开始-->
<link rel="stylesheet" href="/Public/Js/Public/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css">
<script type="text/javascript" src="/Public/Js/Public/plupload/plupload.full.js"></script>
<script type="text/javascript" src="/Public/Js/Public/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="/Public/Js/Public/plupload/i18n/zh-cn.js"></script>
<!--多文件上传plupload插件结束-->

<script type="text/javascript" language="javascript">

    // 页面加载中
    function nLoading() {
        $(window.parent.document).find(".warp").after('<div class="loading_cover"><div class="loading_Win"><img src="__APPURL__/Public/Images/Home/loading.gif" /></div></div>');
        $(window.parent.document).find(".loading_Win").fadeIn(600);
        // 设置loading遮罩层的宽高
        $(window.parent.document).find(".loading_cover").css({
            height: function () {
                return $(window.parent.document).height();
            },
            width: function () {
                return $(window.parent.document).width();
            }
        });
    }

    // 取消页面加载层
    function close_nLoading() {
         $(window.parent.document).find(".loading_Win").fadeOut(600);
         $(window.parent.document).find('#body div').remove('.loading_cover');
    }

    // 完成题目添加,answerObject为存储选中的值
    var answerObject = {};

    // 资源检索框编辑时状态，值为1时从检索框中过来的，0不是，这是为了在编辑完成后能够写到页面DOM树上
    var resourceEditFlag = 0;

    $(function() {

        // 设置bar的高
        $(document).on('click','.switchOn',function(){
            $(this).addClass('switchOff');
            $(this).removeClass('switchOn');
            $(this).find('span').html('否');
            $(this).attr('attr',0);
            $('input[name=act_is_auto_publish]').val(0);
        })

        $(document).on('click','.switchOff',function(){
            $(this).addClass('switchOn');
            $(this).removeClass('switchOff');
            $(this).find('span').html('是');
            $(this).attr('attr',1);
            $('input[name=act_is_auto_publish]').val(1);
        })

        // 日历控件
        $("#datepicker" ).datetimepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            showSecond: false,    //显示秒
            dateFormat:'yy-mm-dd',    //格式化日期
            timeFormat: 'hh:mm',    //格式化时间
            stepHour: 1,    //设置小时步长
            stepMinute: 1,    //设置分钟步长
            stepSecond: 1,    //设置秒步长
            changeMonth: false,
            changeYear: false
        });
        $('#datepicker').timepicker({});

        // 点击发布，弹出发布框
        $(document).on('click', '.publish', function () {

            $('.xin_add').dialog("open");
        })

        // 班级弹窗
        $(".xin_add").dialog({
            draggable: true,        // 是否允许拖动,默认为 true
            resizable: true,        // 是否可以调整对话框的大小,默认为 true
            autoOpen: false,        // 初始化之后,是否立即显示对话框,默认为 true
            position :'center',       // 用来设置对话框的位置
            stack : true,       // 对话框是否叠在其他对话框之上。默认为 true
            modal: true,       // 是否模式对话框,默认为 false(模式窗口打开后，页面其他元素将不能点击，直到关闭模式窗口)
            bgiframe: true,         // 在IE6下,让后面遮罩层盖住select
            width: '480',
            height: 'auto',

            show: {     // 对话框打开效果
                effect: "blind",
                duration: 400
            },
            hide: {     // 对话框关闭效果
              effect: "explode",
              duration: 400
            },
            overlay: {
                backgroundColor: '#000',
                opacity: 0.5
            },
            buttons: {
                确定: function() {

                    // 单击确定时，查找bindClass和bindGroup两个里是否有被选中的值，有的话，就判断是班级还是群组
                    var c_id = '';
                    var cro_id = '';

                    if ($('.bindClass').children().length > 0) {
                        $('.bindClass span.xin_ds').each(function () {
                            c_id += ',' + $(this).attr('rel') ;
                        })
                        c_id = c_id.slice(1);
                    }

                    if ($('.bindGroup').children().length > 0) {
                        $('.bindGroup span.xin_ds').each(function () {
                            cro_id += ',' + $(this).attr('rel');
                        })
                        cro_id = cro_id.slice(1);
                    }

                    // 如果班级和群组的值都为空，则不让提交
                    if ($('input[name=c_idCode]').val() == 0 && $('input[name=cro_idCode]').val() == 0 && c_id == '' && cro_id == '') {
                        window.parent.showInfo('请指定班级或群组');
                        return;
                    }

                    // 浏览器地址上有c_id或cro_id则在隐藏域里存放它们，否则就存放选中的
                    if ($('input[name=c_idCode]').val() != 0) {
                        $('input[name=c_id]').val($('input[name=c_idCode]').val());
                    } else if ($('input[name=c_idCode]').val() != 0) {
                        $('input[name=cro_id]').val($('input[name=c_idCode]').val());
                    } else {
                        $('input[name=c_id]').val(c_id);
                        $('input[name=cro_id]').val(cro_id);
                    }

                    if ($('input[name=end_time]').val() == '') {
                        window.parent.showInfo('截止时间不能为空');
                        return false;
                    }

                    // 如果用户上传了附件，但忘了点击上传按钮，自动点击上传
                    if ($('.plupload_buttons').css('display') != 'none' && $('#uploader_filelist').children().size() > 0) {
                        $('.plupload_start').click();
                        var uploader = $('#uploader').pluploadQueue();
                        // Files in queue upload them first
                        if (uploader.files.length > 0) {
                            // When all files are uploaded submit form
                            uploader.bind('UploadComplete', function() {
                                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                                    checkInfo(1);
                                }
                            });
                            uploader.start();
                        }
                    } else {
                        // 在提交前，还需验证
                        checkInfo(1);
                    }
                },
                取消: function() {

                    $(this).dialog('close');
                }
            }
        });

        // 绑定群组添加
        $(document).on('click','.xin_sain .bindGroup span',function(){
            if ($(this).hasClass('xin_ds')){
                $(this).removeClass('xin_ds');
            } else {
                $(this).addClass('xin_ds');
            }
        })

        // 绑定班级添加
        $(document).on('click','.xin_sain .bindClass span',function(){

            if ($(this).hasClass('xin_ds')){
                $(this).removeClass('xin_ds');
            } else {
                $(this).addClass('xin_ds');
            }
        })

        // 作业多文件上传
        reloadFileUpload();

        // 选择小组模式
        $('input[name=groupMode]').click(function() {

            if ($(this).is(":checked")) {
                $('.hw_group div').show();
            } else {
                $('.hw_group div').hide();
            }
        })

        // 添加题目
        $('.addTopic').click(function() {

            // 限制题目数量为20个
            var num = $('.addTopic_list li').size();

            if (num >= <?php echo ($num); ?>) {
                window.parent.showInfo('对不起, 您最多可以添加<?php echo ($num); ?>个题目');
                return false;
            }

            $(this).parent().parent().siblings('.addtopic_box').show();

            if ($('.addtopic_box').css('height') == '0px') {

                // 把题型复位为0
                $('.questionType').val(0)

                // 清空上次答案内容
                editor.setContent('');

                // 清空题型对应的选项
                $('.addtopic_box .dalist').html('');
                $('.addtopic_box .ad').remove();

                $('.addtopic_box').css('height', 'auto');
            } else {
                $('.addtopic_box').css('height', '0px');
            }
        })

        // 选择题型
        $('.questionType').change(function() {

            // 题目类型
            var titleType = parseInt($(".questionType option:selected").val());

            if (titleType > 0) {
                $('.addtopic_box .dalist').html('');
                setTopic(titleType, '', 4);
            }
        })

        // 学科领域标签添加
        $(document).on('click', '.add_subjectArea', function() {
            $('.tagcustom').dialog('open');
            $('.addTag_list').html('');
        })

        // 删除已添加标签
        $(document).on('mouseover', '.addTag_list span', function() {

            $(this).children().show();
        }).on('mouseout', '.addTag_list span', function() {

            $(this).children().hide();
        })
        $(document).on('click', '.addTag_list cite', function() {

            if (confirm("确定要删除该标签吗？")) {

                $(this).parent().remove();
            }
        })

        // 编辑题目
        $(document).on('click', '.com', function () {

            // 非正常流程下关闭正在编辑的题目后补救方法
            var heights = parseInt($('.addtopic_box').css('height').slice(0, -2));
            if (!heights && !$(this).hasClass('editTopic')) {

                // 在编辑成功后便恢复编辑功能
                $('.com').each(function() {
                    $(this).addClass('editTopic');
                })

                // 去掉flag状态
                $('.addTopic_list li').removeClass('flag');

                $('.editTopic').click();

            }
        })

        // 编辑题目
        $(document).on('click', '.editTopic', function() {

            // 给添加的题目所在DOM树的位置添加个标志，以便在编辑完成时，能够覆盖
            $(this).parent().parent().addClass('flag').siblings().removeClass('flag');

            // 编辑题目的id
            var index = parseInt($(this).parent().parent().attr('rel'));

            // 编辑前需要查询下answerObject是否有该属性，没有的话，则加进来
            if (!(index in answerObject)) {
                answerObject[index] = {
                    dbStatus: 2,
                    to_type: $('.flag').attr('attr'),
                    to_answer: getAnswer($('.flag').find('.tanswer'), $('.flag').attr('attr')),
                    to_option: getOption($('.flag').find('.tanswer'), $('.flag').attr('attr')),
                    to_title: $('.flag').find('.tcontent').text()
                };
            }

            // 当某个题目触发编辑的时候，便把该编辑的功能都删除
            $('.com').each(function() {
                $(this).removeClass('editTopic');
            })

            $('.addTopic').click();
            $('.questionType').change();

            // 编辑式，editStatus的值为1，那么就update到数据表中
            $('input[name=editStatus]').val(1);
            $('input[name=to_id]').val(index);
            switch (parseInt($(this).parent().parent().attr('attr'))) {

                case 1:
                    $('.questionType').val(1);
                    setSingle(answerObject[index]['to_answer'], answerObject[index].to_option.replace(/,/g, '').length, answerObject[index].to_title, 'ueditor');
                    break;
                case 2:
                    $('.questionType').val(2);
                    setMultiple(answerObject[index]['to_answer'], answerObject[index].to_option.replace(/,/g, '').length, answerObject[index].to_title, 'ueditor');
                    break;
                case 3:
                    $('.questionType').val(3);
                    setFillSpace(answerObject[index]['to_answer'], answerObject[index].to_option.replace(/,/g, '').length, answerObject[index].to_title, 'ueditor');
                    break;
                case 4:
                    $('.questionType').val(4);
                    setJudge(answerObject[index]['to_answer'], answerObject[index].to_option.replace(/,/g, '').length, answerObject[index].to_title, 'ueditor');
                    break;
                case 5:
                    $('.questionType').val(5);
                    setShortAnswer(answerObject[index]['to_answer'], answerObject[index].to_option.replace(/,/g, '').length, answerObject[index].to_title, 'ueditor');
                    break;
            }
        })

        // 删除题目
        $(document).on('click', '.delTopic', function() {

            var heights = parseInt($('.addtopic_box').css('height').slice(0, -2));
            if (heights && $(this).parent().parent().hasClass('flag')) {
                window.parent.showInfo('编辑状态中，不允许删除题目');
                return;
            }

            // 异步删除
            if (confirm('确定要删除该题吗?')) {
                $(this).parent().parent().remove();
            }
        })

        // 单击题目添加框中的确定按钮时
        $('.addTopicBtn').click(function() {

            // 获取选择的题型
            var to_type = parseInt($(".questionType option:selected").val());

            if (isNaN(to_type) || to_type == 0 || to_type > 5) {
                window.parent.showInfo('请选择题型');
                return false;
            }

            // 题目内容不能为空
            var content = editor.getContent();

            if (content == '') {
                window.parent.showInfo('请输入题目内容');
                return false;
            }

            // 验证是否选择答案
            var choosed = [];

            // 存储填空题或简答题答案
            var rightAnswer = '';

            // 存储填空题或简答题答案的url传递形式
            var rightAnswerUrl = '';

            // 存储简答题答案
            var shortAnswerObject = '';

            if (to_type != 3 && to_type != 5) {    // 单选、多选、判断

                if (!$('.dalist input').is(":checked")) {
                    window.parent.showInfo('请选择答案');
                    return false;
                } else {

                    // 判断那个被选中了，选中了便放进数组中
                    i = 0;

                    // 记录有几个选项
                    var options = ''

                    $('.dalist input').each(function(j) {
                        if ($(this).is(':checked') == true) {
                            choosed.push(i);
                        }
                        options += j + ',';
                        i++;
                    })
                }

            } else if (to_type == 5) {    // 简答题
                shortAnswerObject = $('.jdt textarea').val();
                if (shortAnswerObject == '') {
                    window.parent.showInfo('请输入答案');
                    return false;
                }
            } else {    // 填空题
                var flag = false;
                // 循环验证所有答案(填空题)，把填空题的答案转为json形式
                rightAnswer = [];
                rightAnswerUrl = '';
                $('.spacelist input').each(function(i) {
                    if ($(this).val() == '') {
                        window.parent.showInfo('请输入答案');
                        flag = true;
                        return false;
                    }
                    rightAnswer.push($(this).val());
                    rightAnswerUrl += 'to_answer[] =' + $(this).val() + '&';
                })
                rightAnswerUrl = rightAnswerUrl.slice(0, -1);
                if (flag) {
                    return false;
                }
            }

            // 获取题型
            var tx = new Array('', '单项选择题', '多项选择题', '填空主观题', '判断题', '简答题');

            // 存储答案
            var titleObject = '';

            // 获取题型对应答案形式
            var operate = "<p class='operate'><label class='lc'>操作：</label><label style='cursor: pointer' class='com editTopic'>编辑</label><label style='cursor: pointer' class='delTopic'>删除</label></p>";

            // 单选、多选、判断题
            if (to_type !== 3 && to_type != 5) {
                var selectedOption = '';

                // 这里的$('.dalist').html()记录的是一开始展现的选项，并不会记录用户选中后的动作
                var answer = '<div class="dalist1">' + $('.dalist').html() + '</div>';
                var insertCont = "<li rel='' style='display:none'><div class='ttitle'>" + tx[to_type] + "</div><div class='tcontent'>" + content + "</div><div class='tanswer'>" + answer + "</div>" + operate + "</li>";
                $('.addedTopic_box .addTopic_list').append(insertCont);

                // 首先得把以前选中的checked给去掉
                $('.addedTopic_box .addTopic_list li:last input').removeAttr('checked');

                for (var i in choosed) {
                    $('.addedTopic_box .addTopic_list li:last input').eq(choosed[i]).prop('checked', true);
                    $('.addedTopic_box .addTopic_list li:last input').eq(choosed[i]).attr('checked', 'checked');
                    selectedOption += choosed[i] + ',';
                }
                titleObject = {to_type: to_type, to_title: content, to_option: options.slice(0, -1), to_answer: selectedOption.slice(0, -1), co_id: $('input[name=co_id]').val()};

             // 填空题和简答题
            } else {
                var answer = '<ul class="dalist1">';

                // 填空题,用i做个计数器，如果最后i为0，那么就应该是简答题
                i = 0;

                // 记录有几个选项，针对的是填空题，如果为空，那么就应该是简答题，同时，如果是填空题，应该以json的形式记录下答案
                var options = '';

                $('.spacelist input').each(function(p) {
                    answer += '<li class="noline"><label style="cursor: pointer;">' + (p + 1) + '、' + $(this).val() + '</label></li>';
                    options += p + ',';
                    i++;
                })

                // 简答题
                if (i == 0) {
                    answer += '<li class="noline"><label style="cursor: pointer;">' + $('.dalist .jdt textarea').val() + '</label></li>';
                }
                answer += '</ul>';
                var insertCont = "<li rel='' style='display:none'><div class='ttitle'>" + tx[to_type] + "</div><div class='tcontent'>" + content + "</div><div class='tanswer'>" + answer + "</div>" + operate + "</li>";
                $('.addedTopic_box .addTopic_list').append(insertCont);
                titleObject = {to_type: to_type, to_title: content, to_option: options.slice(0, -1), to_answer: shortAnswerObject ? shortAnswerObject : rightAnswer, co_id: $('input[name=co_id]').val(), type: options == '' ? 1 : 2};
            }

            // 更新操作
            if ($('input[name=editStatus]').val() == 1) {

                // 编辑时，需要去掉之前存入的内容
                answerObject[$('input[name=to_id]').val()] = '';

                // 更新时，需要传题目id
                titleObject.to_id = $('input[name=to_id]').val();

                // 答案要单独列出来写
                var ans = ''
                if (rightAnswerUrl.length > 0) {
                    ans = rightAnswerUrl;
                } else if (shortAnswerObject != '') {
                    ans = shortAnswerObject;
                }

                //titleObject.to_title = titleObject.to_title.replace(/&nbsp;/g, ' ');
                titleObject.to_title = encodeURIComponent(titleObject.to_title);

                $.post('__APPURL__/Topic/add', 'to_type=' + titleObject.to_type + '&to_title=' + titleObject.to_title + '&to_option=' + (titleObject.to_option ? titleObject.to_option : 0) + (rightAnswerUrl.length > 0 ? '&' +rightAnswerUrl :'&to_answer=' + (ans ? ans : titleObject.to_answer)) + '&type=' +titleObject.type + '&co_id=' + titleObject.co_id + '&to_id=' + titleObject.to_id + '&dbStatus=1', function(json) {
                    if (json.status == 1) {

                        // 收缩添加题目
                        $('.addtopic_box').css({'height':'0px','overflow':'hidden'});

                        // 左侧box高度自适应
                        $('.topicList cite').height($('.topicList').height());

                        $('.addedTopic_box .addTopic_list').children().last().show();
                        $('.addedTopic_box .addTopic_list').children().last().attr('rel', parseInt(json['info']));
                        answerObject[$('input[name=to_id]').val()] = titleObject;

                        // 这里要判断下是否是从资源检索窗口中编辑的，是的话，要做一个each查询
                        // 也就是检索出题目列表中是否有id和此题目id一致的DOM节点，有的话就替换
                        // 没有的话就插入DOM节点
                        if (resourceEditFlag == 1) {

                            if ($('.addTopic_list').children().size() > 1) {
                                var newTopicFlag = false;
                                var newCount = 0;
                                var htm = $('.addTopic_list').children().last().html();

                                $('.addTopic_list li').each(function () {

                                    if ($(this).attr('rel') == titleObject.to_id) {
                                        if (newCount == 0) {
                                            $(this).addClass('flag').siblings().removeClass('flag');

                                            // 把题目类型更新为编辑过后的
                                            $(this).attr('attr', to_type);

                                            // 把最后添加的li的子节点都添加到编辑节点位置上,并去掉flag状态
                                            if (($(this).index() + 1) != $('.addTopic_list').children().size()) {
                                                $(this).html('')
                                            }
                                            $(this).html(htm);
                                            $(this).removeClass('flag');

                                            // 如果该节点是最后一个节点最不删除
                                            if ($('.addTopic_list').children().size() > 2 && ($(this).index() + 1) == $('.addTopic_list').children().size()) {
                                                //$('.addTopic_list').children().last().remove()
                                            }
                                        } else {
                                            $('.addTopic_list').children().last().remove()
                                        }

                                        newTopicFlag = true;
                                        newCount++;
                                    }
                                })

                                if (!newTopicFlag) {
                                    $('.addTopic_list').children().last().attr('attr', to_type);
                                }
                            } else {
                                $('.addTopic_list').children().last().attr('attr', to_type);
                            }

                            resourceEditFlag = 0;
                        } else {
                            // 把题目类型更新为编辑过后的
                            $('.addTopic_list li.flag').attr('attr', to_type);

                            // 把最后添加的li的子节点都添加到编辑节点位置上,并去掉flag状态
                            $('.addTopic_list li.flag').html('')
                            $('.addTopic_list li.flag').html($('.addTopic_list').children().last().html());
                            $('.addTopic_list li').removeClass('flag');

                            // 移除最后一个节点，这里还得区分是从资源检索框里过来的数据
                            $('.addTopic_list').children().last().remove()
                        }

                        // 在编辑成功后便恢复编辑功能
                        $('.com').each(function() {
                            $(this).addClass('editTopic');
                        })

                        // 最后应该清除掉刚刚选择数据，以免再添加题目时出现上次一样的数据
                        $(".questionType option:first").attr('selected', true)
                        editor.setContent('');
                        // 把编辑状态的值置为0
                        $('input[name=editStatus]').val(0);
                    } else {

                        // 失败时，删除刚开始DOM添加的隐藏节点
                        $('.addedTopic_box .addTopic_list').children().last().remove();
                        window.parent.showInfo('添加题目失败');
                    }
                }, 'json');

            // 写入操作
            } else {

                // 答案要单独列出来写
                var ans = ''
                if (rightAnswerUrl.length > 0) {
                    ans = rightAnswerUrl;
                } else if (shortAnswerObject != '') {
                    ans = shortAnswerObject;
                }

                $.post('__APPURL__/Topic/add', 'to_type=' + titleObject.to_type + '&to_title=' + titleObject.to_title + '&to_option=' + (titleObject.to_option ? titleObject.to_option : 0) + (rightAnswerUrl.length > 0 ? '&' +rightAnswerUrl :'&to_answer=' +  (ans ? ans : titleObject.to_answer)) + '&type=' +titleObject. type + '&co_id=' + titleObject.co_id + '&dbStatus=2', function(json) {
                    if (json['status'] == 1) {

                        // 收缩添加题目
                        $('.addtopic_box').css({'height':'0px','overflow':'hidden'});

                        // 左侧box高度自适应
                        $('.topicList cite').height($('.topicList').height());

                        $('.addedTopic_box .addTopic_list').children().last().show();
                        $('.addedTopic_box .addTopic_list').children().last().attr('rel', json['info']);
                        $('.addedTopic_box .addTopic_list').children().last().attr('attr', to_type);
                        answerObject[json['info']] = titleObject;

                        // 最后应该清除掉刚刚选择数据，以免再添加题目时出现上次一样的数据
                        $(".questionType option:first").attr('selected', true)
                        editor.setContent('');
                        // 把编辑状态的值置为0
                        $('input[name=editStatus]').val(0);
                    } else {

                        // 失败时，删除刚开始DOM添加的隐藏节点
                        $('.addedTopic_box .addTopic_list').children().last().remove();
                        window.parent.showInfo('添加题目失败');
                    }
                }, 'json');
            }
        })

        // 单击完成
        $(document).on('click', '.finish', function () {

            // nLoading();

            // 如果用户上传了附件，但忘了点击上传按钮，自动点击上传
            if ($('.plupload_buttons').css('display') != 'none' && $('#uploader_filelist').children().size() > 0) {
                $('.plupload_start').click();
                var uploader = $('#uploader').pluploadQueue();
                // Files in queue upload them first
                if (uploader.files.length > 0) {
                    // When all files are uploaded submit form
                    uploader.bind('UploadComplete', function() {
                        if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                            checkInfo(0);
                        }
                    });
                    uploader.start();
                }
            } else {
                checkInfo(0);
            }
        });

        // 从题库中检索
        $('.fromLibrary').click(function() {

            // 加载资源
            window.parent.dialogNum = 1;
            $(window.parent.document).find('.title').click();
        })

        // 点击取消 关闭iframe子窗口
        $('.finishBtn .cancel').click(function(){

            $(window.parent.document).find('.act_box[rel=1] iframe').css('display','none');
        })
    })

    // 题目编辑
    function topicEdit(obj) {

        resourceEditFlag = 1;

        // 如果题目创建者和当前用户不一致，则把值置为1，以便在题目写好后是写入题库还是更新题库
        if (parseInt((obj.parent().parent().attr('owner'))) != parseInt($('input[name=a_id]').val())) {
            $('input[name=editStatus]').val(0);
        } else {
            $('input[name=editStatus]').val(1);
        }

        answerObject[obj.parent().parent().attr('rel')] = {
            dbStatus: $('input[name=editStatus]').val(),
            to_type: obj.parent().parent().attr('attr'),
            to_answer: getAnswer(obj.parent().next().find('.tanswer'),obj.parent().parent().attr('attr')),
            to_option: getOption(obj.parent().next().find('.tanswer'), obj.parent().parent().attr('attr')),
            to_title: obj.parent().next().find('.tcontent').text()
        };

        $('.addTopic').click();
        $('.questionType').change();

        // 编辑题目的id
        var index = parseInt(obj.parent().parent().attr('rel'));
        $('input[name=to_id]').val(index);

        switch (parseInt(obj.parent().parent().attr('attr'))) {

            case 1:
                $('.questionType').val(1);
                setSingle(answerObject[index]['to_answer'], answerObject[index].to_option.replace(/,/g, '').length, answerObject[index].to_title, 'ueditor');
                break;
            case 2:
                $('.questionType').val(2);
                setMultiple(answerObject[index]['to_answer'], answerObject[index].to_option.replace(/,/g, '').length, answerObject[index].to_title, 'ueditor');
                break;
            case 3:
                $('.questionType').val(3);
                setFillSpace(answerObject[index]['to_answer'], answerObject[index].to_option.replace(/,/g, '').length, answerObject[index].to_title, 'ueditor');
                break;
            case 4:
                $('.questionType').val(4);
                setJudge(answerObject[index]['to_answer'], answerObject[index].to_option.replace(/,/g, '').length, answerObject[index].to_title, 'ueditor');
                break;
            case 5:
                $('.questionType').val(5);
                setShortAnswer(answerObject[index]['to_answer'], answerObject[index].to_option.replace(/,/g, '').length, answerObject[index].to_title, 'ueditor');
                break;
        }
    }

    // 获取弹窗题目的答案
    function getAnswer(obj, type) {

        var arr = [];

        // 简答题
        if (type == 5) {
            arr[0] = obj.find('label').text();
            return arr[0];
        }

        // 填空题
        if (type == 3) {
            obj.find('li').each(function (i) {
                arr.push($(this).text().slice(2));
            })
            return arr;
        }

        // 单选、多选、判断
        if (type == 1 || type == 2 || type == 4) {
            obj.find('input').each(function (i) {
                if ($(this).attr('checked') == 'checked') {
                    arr.push(i);
                }
            })
            return arr;
        }
    }

    // 获取弹窗题目的选项
    function getOption(obj, type) {

        var str = '';

        // 简答题
        if (type == 5) {
            return '';
        }

        // 填空题
        if (type == 3) {
            obj.find('li').each(function (i) {
                str += ',' + i;
            })
            return str.slice(1);
        }

        // 单选、多选、判断
        if (type == 1 || type == 2 || type == 4) {
            obj.find('input').each(function (i) {
                str += ',' + i;
            })
            return str.slice(1);
        }
    }

    // 依据题型不同，出现相对应的选项
    function setTopic(titleType) {

        // 记录题型对应的操作方法名称
        var functionName = '';

        // 如果没有题目类型，则直接返回
        if (!titleType) {
            return;
        }

        // 清空上次答案内容
        $('.addtopic_box .dalist').html('');
        $('.addtopic_box .ad').remove();

        switch (titleType) {

            // 单选
            case 1:
                setSingle('9', 4, '', 'ueditor');
                break;

                // 多选
            case 2 :
                setMultiple('', 4, '', 'ueditor')
                break;

                // 填空
            case 3:
                setFillSpace('', 1, '', 'ueditor')
                break;

                // 判断
            case 4:
                setJudge('', '', '', 'ueditor');
                break;

                // 简答
            case 5:
                setShortAnswer('', '', '', 'ueditor');
                break;
        }
    }

    // counter计数器
    var counter = 0;

    // 单选
    function setSingle(answer, defaultAnswerNum, title, editType) {

        if (editType == 'ueditor') {
            editor.setContent(title);
        }

        $(".addtopic_box .dalist").before('<div class="ad"><a href="javascript:single(' + answer + ',' + counter + ')">添加答案</a><a href="javascript:delSingle();">删除答案</a></div>');
        for (var i = 0; i < defaultAnswerNum; i++) {
            single(answer, counter);
        }

        counter++;
    }

    // 多选
    function setMultiple(answer, defaultAnswerNum, title, editType) {

        if (editType == 'ueditor') {
            editor.setContent(title);
        }

        $(".addtopic_box .dalist").before('<div class="ad"><a href="javascript:multiple();">添加答案</a><a href="javascript:delSingle();">删除答案</a></div>');
        for (var i = 0; i < defaultAnswerNum; i++) {
            multiple(answer);
        }
    }

    // 填空
    function setFillSpace(answer, defaultAnswerNum, title, editType) {

        if (editType == 'ueditor') {
            editor.setContent(title);
        }

        $(".addtopic_box .dalist").before('<div class="ad"><a href="javascript:fillSpace();">添加答案</a><a href="javascript:delSpace();">删除答案</a></div>');
        for (var i = 0; i < defaultAnswerNum; i++) {
            fillSpace(answer);
        }
    }

    // 判断
    function setJudge(answer, defaultAnswerNum, title, editType) {

        if (editType == 'ueditor') {
            editor.setContent(title);
        }

        judge(answer, counter);
        counter++;
    }

    // 简答
    function setShortAnswer(answer, defaultAnswerNum, title, editType) {

        if (editType == 'ueditor') {
            editor.setContent(title);
        }

        shortAnswer(answer);
    }



    // 单选添加
    function single(answer, count) {

        // 单选或多选的答案个数不可大于6
        if ($('.addtopic_box .dalist label').size() > 5) {

            window.parent.showInfo('最多可以添加6个答案');
        } else {

            // 添加单选答案
            var index = $('.addtopic_box .dalist label').size();
            var str = '';
            if (answer == index) {
                str += "<input type='radio' name='single" + count + "' value=" + index + " checked='' />";
            } else {
                str += "<input type='radio' name='single" + count + "' value=" + index + " />";
            }
            str += "<label class='option' style='cursor: pointer;'>" + infor(index) + "</label>";
            $(".addtopic_box .dalist").append(str);
        }
    }

    // 多选添加
    function multiple(answer) {
        // 单选或多选的答案个数不可大于6
        if ($('.addtopic_box .dalist label').size() > 5) {

            window.parent.showInfo('最多可以添加6个答案');
        } else {

            // 添加多选答案
            var index = $('.addtopic_box .dalist label').size();

            var str = '';

            // 判断数组中是否有值
            if (answer && answer.length) {

                // 判断index是否在answer数组中
                for (var j in answer) {
                    if (answer[j] == index) {
                        str += "<input type='checkbox' name='multiple' value=" + index + " checked='' />";
                    }
                }
            }

            if (str == '') {
                str += "<input type='checkbox' name='multiple' value=" + index + " />";
            }

            str += "<label class='option' style='cursor: pointer;'>" + infor(index) + "</label>";
            $(".addtopic_box .dalist").append(str);
        }
    }

    // 填空添加
    function fillSpace(answer) {

        // 填空的答案个数不可大于6
        if ($('.addtopic_box .dalist label').size() > 5) {
            window.parent.showInfo('最多可以添加6个答案');
        } else {

            // 添加填空答案
            var index = $('.addtopic_box .dalist div.spacelist').size();
            var str = ''

            if (answer && answer != '') {
                str = '<div class="spacelist"><label class="option" style="cursor: pointer;">' + (index + 1) + '</label><input type="text" name="" value="' + answer[index] + '"></div>';
            } else {
                str = '<div class="spacelist"><label class="option" style="cursor: pointer;">' + (index + 1) + '</label><input type="text" name=""></div>';
            }
            $(".addtopic_box .dalist").append(str);
        }
    }

    // 判断添加
    function judge(answer, count) {

        // 添加判断答案
        var str = "<div class='pdt'>";
        if (answer == 0) {
            str += "<input  type='radio' name='judge" + count + "' checked='' />";
        } else {
            str += "<input  type='radio' name='judge" + count + "' />";
        }
        str += "<label  class='option' style='cursor: pointer;' attr='1'><img src='__APPURL__/Public/Images/Home/ok.jpg' width='20' height='20' border='0'></label>";
        if (answer == 1) {
            str += "<input  type='radio' name='judge" + count + "' checked='' />";
        } else {
            str += "<input  type='radio' name='judge" + count + "' />";
        }
        str += "<label class='option' style='cursor: pointer;' attr='2'><img src='__APPURL__/Public/Images/Home/err.jpg' width='20' height='20' border='0'></label></div>";
        $(".addtopic_box .dalist").append(str);
    }

    // 简答题添加
    function shortAnswer(answer) {
        var str = "<div class='jdt'><textarea rows=5>" + (answer ? answer : '') + "</textarea></div>";
        $(".addtopic_box .dalist").append(str);
    }

    // 单选删除
    function delSingle() {

        var size = $('.addtopic_box .dalist label').size() - 1;
        // 单选或多选的答案个数不可小于2
        if (size < 2) {
            window.parent.showInfo('至少保留2个答案');
        } else {
            $('.addtopic_box .dalist input').eq(size).remove();
            $('.addtopic_box .dalist label').eq(size).remove();
        }
    }

    // 填空删除
    function delSpace() {
        var size = $('.addtopic_box .dalist .spacelist').size() - 1;
        // 填空的答案个数不可小于1
        if (size < 1) {
            window.parent.showInfo('至少保留1个答案');
        } else {
            $('.addtopic_box .dalist .spacelist').eq(size).remove();
        }
    }

    function infor(i) {
        var arr = new Array();
        arr[0] = 'A';
        arr[1] = 'B';
        arr[2] = 'C';
        arr[3] = 'D';
        arr[4] = 'E';
        arr[5] = 'F';
        return arr[i % 6];
    }

    // 检测作业的状态
    function checkInfo(act_is_publish) {

        var act_title = $.trim($('input[name=act_title]').val());
        var act_note = $.trim($('.act_note').val());

        $('input[name=ap_complete_time]').val($('input[name=end_time]').val());

        if (act_title == '') {
            window.parent.showInfo('作业标题不能为空');
            return false;
        }

        // 作业标题限制50个字符
        var actTitleLen = act_title.replace(/\s+/g,"").length;
        if(actTitleLen > 50) {
            window.parent.showInfo('作业标题最多为50个字符');
            return false;
        }

        // 作业要求限制200个字符
        var actNoteLen = act_note.replace(/\s+/g,"").length;
        if(actNoteLen > 200) {
            window.parent.showInfo('作业要求最多为200个字符');
            return false;
        }

        if ($('.addTopic_list li').size() == 0 && $('input[name=uploader_count]').val() == 0) {
            window.parent.showInfo('请添加题目或上传附件作为题目');
            return false;
        }

        // 如果上传了附件，则在提交的时候需要弹出个等待层
        if ($('input[name=uploader_count]').val() != 0) {
            nLoading()
        }

        // 存储题目id
        var act_rel = '';
        $('.addTopic_list li').each(function() {
            if ($(this).attr('rel') != undefined) {
                act_rel += $(this).attr('rel') + ',';
            }
        });
        act_rel = act_rel.slice(0, -1);

        // 是否发布
        $('input[name=act_is_published]').val(act_is_publish);

        // 关联的题目
        $('input[name=act_rel]').val(act_rel);

        // 异步传值
        $.post('__APPURL__/Activity/insert', 'co_id=' + $('input[name=co_id]').val() + '&ta_id=' + $('input[name=ta_id]').val() + '&act_type=' + $('input[name=act_type]').val() + '&act_is_auto_publish=' + $('input[name=act_is_auto_publish]').val() + '&act_is_published=' + $('input[name=act_is_published]').val() + '&act_rel=' + $('input[name=act_rel]').val() + '&ap_complete_time=' + $('input[name=ap_complete_time]').val() + '&act_title=' + act_title + '&act_note=' + act_note + '&c_id=' + $('input[name=c_id]').val() + '&cro_id=' + $('input[name=cro_id]').val() + '&uploader_count=' + $('#uploader_count').val(), function (json) {

            // 如果上传了附件，关闭遮罩层
            if ($('input[name=uploader_count]').val() != 0) {
                close_nLoading()
            }

            if (json.status == 1) {

                // 隐藏取消活动按钮
                $(window.parent.document).find('.activityFlag .cancel_add').hide();

                // 关闭子窗口(让iframe隐藏)
                $(window.parent.document).find('.act_box[rel=1] iframe').css('display','none');
                $(window.parent.document).find('.act_box[rel=1]').prepend("<iframe id='actIframe' width='100%' height='627' frameborder='no' border='0' scrolling='yes' style='display:none;'></iframe>");

                // 每个活动的外容器
                var liliObj = "<div class='liliObj' rel='" + json.info + "'></div>";

                var TopicList = "<li class='lili act_option'><div class='listAct'><div class='act_title'><a class='topic_arrow topic_down'></a><span rel='" + json.info + "' title='" + act_title + "' act_type='1'>"+act_title+"</span></div><div class='topicBox' style='display: none;'><div class='resourceContent'></div></div></div></li>"

                // 若是从班级或群组页面过来备课的，还需加上unlink样式
                var unlink = '';
                if ($('input[name=unlink]').val() == 1) {
                    unlink = '';
                } else {
                    unlink = act_is_publish == 1 ? '' : 'unlink'
                }

                var TopicModule = "<li class='lili flex_add homework " + unlink + "'><div class='thumbAct' act_type='1'><div class='add_homework'></div><span title='"+act_title+"' rel='" + json.info + "'>"+act_title+"</span><a class='flex_del' rel='" + act_is_publish + "' style='display: none;'></a></div></li>";

                // 获取当前活动容器个数
                var current = $(window.parent.document).find('.act_box[rel=1] ul').find('.liliObj').size();

                // 添加li的外容器
                $(window.parent.document).find('.act_box[rel=1] ul.act_box_ul').append(liliObj);

                // 网格模式下添加活动
                $(window.parent.document).find(".act_box[rel=1] .liliObj:eq("+current+")").append(TopicModule);

                // 列表模式下添加活动
                $(window.parent.document).find(".act_box[rel=1] .liliObj:eq("+current+")").append(TopicList);

                // 判断当前的显示模式（列表/网格）
                var isList = $(window.parent.document).find('.order_switch').children('a');
                if(isList.first().hasClass('on')) {

                    // 列表模式下添加活动（网格显示元素隐藏）
                    $(window.parent.document).find('.thumbAct').parent().css('display','none');
                } else {

                    // 网格模式下添加活动（列表显示元素隐藏）
                    $(window.parent.document).find('.listAct').parent().css('display','none');
                }
                $(window.parent.document).find('.act_box[rel=1] iframe').eq(1).remove();

            } else {
                window.parent.showInfo(json.info);
            }
        }, 'json');
    }

    // 初始化多文件上传
    function reloadFileUpload() {
        var maxSize = <?php echo ($maxSize); ?>;
        $("#uploader").pluploadQueue({
            // General settings
            runtimes: 'html4,html5,flash,silverlight,gears,browserplus',
            url: '/Activity/acceptFiles',
            max_file_size : maxSize+'mb',
            chunk_size : '10mb',
            unique_names : true,
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
<div style="overflow-x:hidden;">
    <!--环节ID-->
    <input type="hidden" name="ta_id" value="<?php echo ($ta_id); ?>"/>
    <!--课程ID-->
    <input type="hidden" name="co_id" value="<?php echo ($co_id); ?>"/>
    <!--关联题目ID-->
    <input type="hidden" name="act_rel" value=""/>
    <!--编辑状态-->
    <input type="hidden" name="editStatus" value="0"/>
    <!--题目ID-->
    <input type="hidden" name="to_id" value=""/>
    <!--是否发布-->
    <input type="hidden" name="act_is_published" value=""/>
    <!--活动类型-->
    <input type="hidden" name="act_type" value="<?php echo ($type["id"]); ?>"/>
    <!--活动绑定的班级-->
    <input type="hidden" name="c_id" value="<?php echo ($c_id); ?>"/>
    <!--活动绑定的群组-->
    <input type="hidden" name="cro_id" value="<?php echo ($cro_id); ?>"/>
    <!--备课的班级-->
    <input type="hidden" name="c_idCode" value="<?php echo ($c_id); ?>"/>
    <!--备课的群组-->
    <input type="hidden" name="cro_idCode" value="<?php echo ($cro_id); ?>"/>
    <!--用户ID-->
    <input type="hidden" name="a_id" value="<?php echo ($authInfo["a_id"]); ?>"/>
    <!--截止时间ID-->
    <input type="hidden" name="ap_complete_time" value=""/>
    <!--是否自动发布-->
    <input type="hidden" name="act_is_auto_publish" value="1"/>
    <!--发布样式-->
    <input type="hidden" name="unlink" value="<?php echo ($unlink); ?>"/>
    <div class="create_act">
        <ul class="actul">
            <li>
                <label class="la">作业标题：</label>
                <div class="act_li_r">
                    <input type="text" name="act_title">
                </div>
            </li>
            <li>
                <label class="la">作业要求：</label>
                <div class="act_li_r">
                    <textarea class="act_note" name="act_note"></textarea>
                </div>
            </li>
            <li>
                <label class="la">附件作业：</label>
                <div id="uploader" class="act_li_r">
                    <p>上传资源控件加载错误，可能是您的浏览器不支持 Flash, Silverlight, Gears, BrowserPlus 或 HTML5，请检查</p>
                </div>
            </li>
            <li>
                <label class="la">自动发布：</label>
                <div class="act_li_r">
                    <div class="switchOn" attr="1">
                        <span>是</span>
                        <label></label>
                    </div>
                    <div class="ts"><img src="__APPURL__/Public/Images/Home/ts.png">您在发布课时的时候会同时发布本活动给学生</div>
                </div>
            </li>
            <li>
                <label class="la">题目列表：</label>
                <div class="act_li_r">
                    <span class="addTopic">+添加题目</span>
                    <span class="fromLibrary">+从题库中检索</span>
                </div>
            </li>
            <!-- 添加题目box 开始 -->
            <li class="topicBox addtopic_box" style="display:none;">
                <div class="topicList">
                    <ul class="topicContent fl">
                        <li class="libox" id="addT">
                            <label>题型：</label>
                            <select name="questionType" class="questionType">
                                <option value="0">选择题型</option>
                                <option value="1">单选</option>
                                <option value="2">多选</option>
                                <option value="3">填空</option>
                                <option value="4">判断</option>
                                <option value="5">简答题</option>
                            </select>
                        </li>
                        <li class="libox">
                            <label>题目：</label>
                            <script type="text/plain" id="content" name="to_note"></script>
                            <script type="text/javascript">
                                        <!--
                                            var editor = new baidu.editor.ui.Editor({
                                            UEDITOR_HOME_URL: '/Public/Js/Public/ueditor/'
                                        });
                                        editor.render("content");
                                        //-->
                            </script>
                        </li>
                        <li class="libox bom">
                            <label>答案：</label>
                            <div class="dalist">
                            </div>
                            <input type="hidden" name="choosed_answer">
                        </li>
                        <li class="libox">
                            <a class="addTopicBtn">完成</a>
                        </li>
                    </ul>
                </div>
            </li>
            <!-- 添加题目box 结束 -->
            <!-- 已添加的题目box 开始 -->
            <li class="addedTopic_box">
                <ul class="addTopic_list">

                </ul>
            </li>
            <!-- 已添加的题目box 结束 -->
            <li class="finishBtn">
                <?php if($bindInfo): ?><button type="button" name="publish" class="publish">发布</button><?php endif; ?>
                <?php if($c_id): ?><button type="button" name="publish" class="publish">发布</button><?php endif; ?>
                <?php if($cro_id): ?><button type="button" name="publish" class="publish">发布</button><?php endif; ?>
                <button type="button" name="finish" class="finish">完成</button>
                <!-- <button type="button" name="cancel" class="cancel">取消</button> -->
            </li>
        </ul>
    </div>
<input type="hidden" name="c_actType" value="homework">
</div>
<div class="xin_add" title="指定班级或群组">
    <ul>
        <!--li class="xin_uli">按班级</li>
        <li>按群组</li-->
    </ul>
    <div class="clear"></div>
    <div class="xin_noe">
        <div class="xin_sain">
            <?php if($bindInfo): ?><label class="fl">已发布的班级和群组:</label>
            <div class="fl sa_click allClassGroup">
            </div>
            <label class="fl">指定班级:</label>
            <div class="fl sa_click bindClass">
                <?php if(is_array($bindInfo["class"])): $i = 0; $__LIST__ = $bindInfo["class"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span rel="<?php echo ($vo["c_id"]); ?>"><?php echo ($vo["c_title"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="clear"></div>
            <label class="fl">指定群组:</label>
            <div class="fl sa_click bindGroup">
                <?php if(is_array($bindInfo["group"])): $i = 0; $__LIST__ = $bindInfo["group"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span rel="<?php echo ($vo["cro_id"]); ?>"><?php echo ($vo["cro_title"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="clear"></div><?php endif; ?>
            <label class="fl">截止时间:</label>
            <div class="fl sa_click endTime">
                <input type="text" name="end_time" class="endtime" id="datepicker"/>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('.addtopic_box').css({'height':'0px','overflow':'hidden'});
    })
</script>