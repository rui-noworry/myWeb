$(function(){

	// 后台首页的响应式
	$("#onhover").hover(function(){
		$("#body-left").show();
		$("#body-left").css({
			position: 'absolute',
			left: 0
		});
	});

	// 当窗口大小发生变化时
	$(window).resize(function(){

		if($(document).width() > 1000) {

			$("#body-left").show();
			$("#onhover").hide();

			$("#body-left").css({
				position:"relative"
			});

		} else {
			$("#body-left").hide();
			$("#onhover").show();
		}
	});


	// ajax获取新闻列表
	$.get("/admin/news/",'', function(data){

		var arr = data;

		var html = "";
		for (var i = 0; i < arr.length; i++){
			
			var img = "";
			if (arr[i]["n_pic"]) {
				img = "<img src='../uploads/"+arr[i]["n_pic"]+"'>";
			} else {
				img = "无";
			}

			html += "<tr id='"+arr[i]["n_id"]+"'><td class='num'>"+ (i + 1) +"</td><td class='txt'>"+arr[i]["n_title"]+"</td><td  class='txt'>"+arr[i]["n_desc"]+"</td><td>"+img+"</td><td>"+arr[i]["n_date"]+"</td><td><a href='/admin/edit/"+arr[i]['n_id']+"'>编辑</a>&nbsp;&nbsp;<span id=‘del’>删除</span></td></tr>"
		}

		$("tbody").html(html);


	});

	// 点击删除
	$(document).on('click', 'table span', function(){
			
		var thisObj = $(this);

		// 获取当前新闻的id
		var thisTr = thisObj.parents("tr");
		var n_id = thisTr.attr("id");

		$.get("/admin/del/" + n_id, '', function(data) {

			if (data.affectedRows == 1) {
				thisTr.remove();
			} else {
				alert("删除失败");
			}

		});
	});

	// 点击编辑
	$(document).on('click', 'table a', function(){
		var n_id = $(this).parents("tr").attr("id");

		window.location.href="edit.html?n_id="+n_id;
	});
});