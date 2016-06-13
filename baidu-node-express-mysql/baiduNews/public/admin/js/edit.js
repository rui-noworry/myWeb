$(function(){

                     var nid = $('#n_id').val();
                     
                     // 获取编辑的内容
                    $.get('/admin/ajaxEdit/' + nid,'', function(data) {

                           var arr = data[0];
                           
                           $("#n_title").val(arr.n_title);
                           $("#n_desc").val(arr.n_desc);
                           $("#c_id").val(arr.c_id);

                    });
});