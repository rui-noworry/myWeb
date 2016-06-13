$(function() {

    // 新增
    $('.tools .add').click(function() {
        if (!$(this).attr('attr')) {
            location.href = URL + '/add';
        }
    })

    // 删除
    $('.tools .del').click(function() {
        del();
    })

    // 禁用
    $('.tools .forbidden').click(function() {
        forbid();
    })

    // 启用
    $('.tools .resume').click(function() {
        resume();
    })

    // 编辑
    $('.tools .edit').click(function() {

        var keyValue = getSelectCheckboxValue();

        if (!keyValue) {
            alert('请选择编辑项！');
            return false;
        }

        location.href = URL + "/edit/id/" + keyValue;
    });

    // 列表页面搜索
    $('.model_search .search').click(function(){
        $('form').submit();
    });

})
// 错误提示
function showError(message) {
    $("#result").html('<span style="color:red">' + message + '</span>');
    $("#result").show();
}

//重载验证码
function fleshVerify(){
    $("#verifyImg").attr('src', '/apps/manage/Ilc/Public/verify/'+ Math.random());
}

rowIndex = 0;

// 全选
function allSelect(name) {
    $("input[name=" + name + "]").each(function(){
        $(this).attr('checked', true);
    })
}

// 全不选
function allUnSelect(name) {
    $("input[name=" + name + "]").each(function(){
        $(this).attr('checked', false);
    })
}

// 反选
function InverSelect(name) {
    $("input[name=" + name + "]").each(function(){
        $(this).attr('checked', !Boolean($(this).attr('checked')));
    })
}

// 新增
function add() {
    location.href = URL + '/add';
}

// 编辑
function edit(id){
    var keyValue;
    if (id) {
        keyValue = id;
    } else {
        keyValue = getSelectCheckboxValue();
    }
    if (!keyValue) {
        alert('请选择编辑项！');
        return false;
    }
    location.href = URL + "/edit/id/" + keyValue;
}

// 删除
function del(id) {
    var keyValue;
    if (id) {
        keyValue = id;
    } else {
        keyValue = getSelectCheckboxValues();
    }

    if (!keyValue) {
        alert('请选择删除项！');
        return false;
    }

    if (window.confirm('确定要删除选择项吗？')) {
        location.href =  URL+"/delete/id/"+keyValue;
    }
}

// 排序
function sort() {

    var keyValue = getSelectCheckboxValues();
    location.href = URL+"/sort/sortId/"+keyValue;
}

// 默认加载完成后JS
$(function() {

    // 表格行随鼠标滚动变色
    $("#IlcTableList tr").mouseover(function() {
        $(this).addClass('over').siblings().removeClass('over');
    })
})

// 获取被选中的所有复选框的值
function getSelectCheckboxValues(){

    var str = '';
    $("input[name=key]:checked").each(function(){
        str += ',' + $(this).val();
    })
    return str.slice(1);
}

// 获取被选中的第一个复选框的值
function getSelectCheckboxValue(){

    return $("input[name=key]:checked").eq(0).val();
}

// 选中指定ID下的checkbox
function CheckAll(id) {
    var obj = $("#"+id+" :checkbox");

    var flag;
    obj.each(function(i){
        if (i == 0) {
            flag = Boolean($(this).is(':checked'));
        } else {
            $(this).prop("checked", flag);
        }
    })
}

// 禁用
function forbid(id){
    var keyValue;
    if (id) {
        keyValue = id;
    } else {
        keyValue = getSelectCheckboxValues();
    }
    if (!keyValue) {
        alert('请选择禁用项！');
        return false;
    }

    if (window.confirm('确定要禁用选择项吗？')) {
        location.href = URL+"/forbid/id/"+keyValue;
    }
}

// 启用
function resume(id){
    var keyValue;
    if (id) {
        keyValue = id;
    } else {
        keyValue = getSelectCheckboxValues();
    }
    if (!keyValue) {
        alert('请选择启用项！');
        return false;
    }

    if (window.confirm('确定要启用选择项吗？')) {
        location.href = URL+"/resume/id/"+keyValue;
    }
}

//定义弹出窗口
function popWin() {
    var windowHeight;    //获取窗口的高度
    var windowWidth;     //获取窗口的宽度
    var popWidth;        //获取弹出窗口的宽度
    var popHeight;       //获取弹出窗口高度

    windowHeight = $(window).height();
    windowWidth = $(window).width();
    popHeight = $(".hide").height();
    popWidth = $(".hide").width();
    //alert(windowHeight+'==='+windowWidth+'==='+popHeight+'==='+popWidth);

    //计算弹出窗口的左上角Y的偏移量
    var popY = (windowHeight-popHeight)/2-50;
    var popX = (windowWidth-popWidth)/2-100;
    //alert(popY+'==='+popX);

    //设定窗口的位置
    $(".hide").css("top",popY).css("left",popX).slideToggle("slow");

}

//关闭窗口
function closeWindow() {
    $(".closeWin").click(function() {
     $(this).parent().parent().hide("slow");
    });
}

// 应用授权
function app(id){
    location.href = URL+"/app/groupId/"+id;
}

// 模块授权
function module(id){
    location.href = URL+"/module/groupId/"+id;
}

// 操作授权
function action(id){
    location.href = URL+"/action/groupId/"+id;
}

// 角色用户列表
function user(id){
    location.href = URL+"/user/id/"+id;
}


/*
 * listGrade
 * 根据学制列出年级
 * $param int sid 学校ID
 * $param string choose_type 学制ID
 * $param string objName 传值select name
 * $param string choose_grade 默认选中
 *
 */
function listGrade(sid, choose_type, objName, choose_grade, className) {

    if (className) {
        //var str = '<label class="'+className+'">年级：</label><span class="hand on" attr="0" >不限</span>';
    } else {
        var str = '<label>年级：</label><div>';
    }

    if (choose_type) {

        $.post('/apps/manage/Ilc/Public/getGradeByType', 'id=' + choose_type + '&s_id=' + sid, function(json) {

            if (json) {

                for (var i = 0; i < json.length; i ++ ) {

                    str += '<span rel="' + json[i]['key'] + '" class="hand ';

                    if (!className) {

                        if (choose_grade > 0 && choose_grade == json[i]['key']) {

                            str += 'on';
                        }
                    }

                    str += '">' + json[i]['value'] + '</span>';
                }
            }
            str += '</div>';
            $("." + objName).html(str);
        }, 'json');

    } else {
        $("." + objName).html('');
    }
}

/*
 * listGradeOption
 * 根据学制列出年级
 * $param string choose_type 学制ID
 * $param string objName 传值select name
 * $param string choose_grade 默认选中
 * $param string major 专业
 *
 */
function listGradeOption(sid, choose_type, objName, choose_grade, major) {

    var str = '<option value="0">请选择</option>';

    if (choose_type) {

        $.post('/apps/manage/Ilc/Public/getGradeByType', 'id=' + choose_type + '&s_id=' + sid + '&type='+ major, function(json) {

            if (json) {

                for (var i = 0; i < json.length; i ++ ) {

                    str += '<option value="' + json[i]['key'] + '"';

                    if (choose_grade > 0 && choose_grade == json[i]['key']) {

                        str += 'selected';
                    }

                    str += '>' + json[i]['value'] + '</option>';
                }
            }

            $("select[name=" + objName + "]").html(str);

        }, 'json');

    } else {

        $("select[name=" + objName + "]").html(str);
    }

}

/*
 * listClass
 * 根据学制年级列出班级
 * $param string choose_type 学制ID
 * $param string choose_grade 年级ID
 * $param string objName 传值select name
 * $param string choose_class 默认选中
 * $param string ma_id 专业ID
 *
 */
function listClass(s_id, choose_type, choose_grade, objName, choose_class, ma_id) {

    var str = '<option value="0">请选择</option>';

    if (s_id && choose_type && choose_grade) {

        $.post('/apps/manage/Ilc/Class/listByTypeAndGrade', 's_id='+s_id+'&c_type=' + choose_type + '&c_grade=' + choose_grade + '&is_ajax=1&ma_id='+ma_id, function(json) {

            if (json) {

                for (var i = 0; i < json.length; i ++ ) {

                    str += '<option value="' + json[i]['c_id'] + '"';

                    if (choose_class > 0 && choose_class == json[i]['c_id']) {

                        str += 'selected';
                    }

                    str += '>(' + json[i]['c_title'] + ')班</option>';
                }
            }

            $("select[name=" + objName + "]").html(str);

        }, 'json');

    } else {

        $("select[name=" + objName + "]").html(str);
    }
}

// 列表排序
function sortBy(field, sort, action){
    location.href = URL+"/"+action+"/field/"+field+"/sortby/"+sort;
}