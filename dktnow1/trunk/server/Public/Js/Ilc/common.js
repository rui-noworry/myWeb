// 删除
function del(id) {
    var keyValue;
    if (id) {
        keyValue = id;
    } else {
        keyValue = getSelectCheckboxValues();
    }

    if (!keyValue) {
        showMessage('请选择删除项！');
        return false;
    }

    if (window.confirm('确定要删除选择项吗？')) {
        $.post(URL+'/delete', 'id='+keyValue, function(json) {
            if (json.status == 1) {
                if (!id) {
                    $("input[name=key]:checked").each(function(){
                        $(this).parents('.plist').remove();
                    })
                } else {
                    $('.plist').each(function() {
                        if ($(this).attr('attr') == id) {
                            $(this).remove();
                        }
                    })
                }
            } else {
                if (json.info) {
                    showMessage(json.info);
                } else {
                    showMessage('操作失败');
                }
            }
        }, 'json')
    }
}


// 通过
function pass(id) {
    var keyValue;
    if (id) {
        keyValue = id;
    } else {
        keyValue = getSelectCheckboxValues();
    }

    if (!keyValue) {
        showMessage('请选择操作项！');
        return false;
    }

    if (window.confirm('确定要执行吗？')) {
        $.post(URL+'/pass', 'id='+keyValue, function(json) {

            if (json == 1) {
                if (!id) {
                    $("input[name=key]:checked").each(function(){
                        $(this).parents('.plist').remove();
                    })
                } else {
                    $('.plist').each(function() {
                        if ($(this).attr('attr') == id) {
                            $(this).remove();
                        }
                    })
                }
            } else {
                if (json.info) {
                    showMessage(json.info);
                } else {
                    showMessage('操作失败');
                }
            }
        }, 'json')
    }
}

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

    // 排揎
    $('.tools .sort').click(function() {
        sort();
    })

    // 禁用
    $('.tools .forbidden').click(function() {
        forbid('', 1);
    })

    // 启用
    $('.tools .resume').click(function() {
        forbid();
    })

    // 编辑
    $('.tools .edit').click(function() {

        var keyValue = getSelectCheckboxValue();

        if (!keyValue) {
            showMessage('请选择编辑项！');
            return false;
        }

        location.href = URL + "/edit/id/" + keyValue;
    });
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

// 启用禁用
function forbid(id, order){
    var keyValue;
    if (id) {
        keyValue = id;
    } else {
        keyValue = getSelectCheckboxValues();
    }

    order = order ? order : 0;
    var action = new Array('resume', 'forbid');
    var text = new Array('禁用','启用');
    var num = new Array(1,9);
    var img = new Array('qy_status.png', 'jy_status.gif');

    if (!keyValue) {
        showMessage('请选择'+text[1-order]+'项！');
        return false;
    }

    if (window.confirm('确定要'+text[1-order]+'选择项吗？')) {
        $.post(URL+'/'+action[order], 'id='+keyValue, function(json) {
            if (json.status == 1) {
                if (!id) {
                    $("input[name=key]:checked").each(function(){
                        $(this).parents('.plist').find('._status').attr('attr', num[order]);
                        $(this).parents('.plist').find('._status').html('<img src="../Public/Images/Ilc/'+img[order]+'">');
                        $(this).parents('.plist').find('._statusText').html(text[order]);
                    })
                } else {
                    $('.plist').each(function() {
                        if ($(this).attr('attr') == id) {
                            $(this).find('._status').attr('attr', num[order]);
                            $(this).find('._status').html('<img src="../Public/Images/Ilc/'+img[order]+'">');
                            $(this).find('._statusText').html(text[order]);
                        }
                    })
                }
            } else {
                if (json.info) {
                    showMessage(json.info);
                } else {
                    showMessage('操作失败');
                }
            }
        }, 'json')
    }
}

// 排序
function sort() {

    var keyValue = getSelectCheckboxValues();
    location.href = URL+"/sort/sortId/"+keyValue;
}

// 角色用户列表
function user(id){
    location.href = URL+"/user/id/"+id;
}

// 列表排序
function sortBy(field, sort, action){
    location.href = URL+"/"+action+"/field/"+field+"/sortby/"+sort;
}

// 表格宽度
function setWidth(obj) {
    for(var p in obj){
        $('.' + p).css('width', obj[p]);
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
function listClass(choose_type, choose_grade, objName, choose_class, ma_id) {

    var str = '<option value="0">请选择</option>';

    if (choose_type && choose_grade) {

        $.post(APPURL + '/Ilc/Class/lists', 'c_type=' + choose_type + '&c_grade=' + choose_grade + '&is_ajax=1&ma_id='+ma_id, function(json) {

            json = json.list;
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
