// 删除/添加资源引用
function deleteRelation(url, re_id){

    if(!re_id){
        showMessage('请求参数有误');
    }

    cl_id = $('input[name=cl_id]').val();

    if(!cl_id){
        showMessage('请求参数有误');
    }

    if(url == 'relationDelete'){
        if (window.confirm('确定要删除选择项吗？')) {
            $.post('__APPURL__/Resource/'+url, 'cl_id='+cl_id+'&re_id='+re_id, function(json) {
                if (json.status == 1) {
                    $('#del' + re_id).remove();
                } else {
                    showMessage(json.info);
                }
            }, 'json')
        }
    }else{
        location.href = '__APPURL__/Resource/'+url+'/cl_id/'+cl_id+'/re_id/'+re_id;
    }

}