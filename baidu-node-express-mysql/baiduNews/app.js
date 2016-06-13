// 引入express模块
var express = require("express");
// 引入设置路径接口
var path = require('path');

// 引入数据库
var mysql = require('mysql');

// 初始化express
var app = express();

var bodyParser = require('body-parser');

// 设置模板引擎
app.set('view engine', 'ejs');
app.set('views', __dirname + '/views');

// 设置静态路径
app.use(express.static(path.join(__dirname, 'public')));

// 对数据进行格式化
// 通常 POST 内容的格式是 application/x-www-form-urlencoded, 因此要用下面的方式来使用：
app.use(bodyParser.urlencoded({extended: true}));

// 链接数据库方法
var connection;

function conn(){

	connection = mysql.createConnection({     
		  host : 'localhost',       
		  user : 'root',              
		  password : '',       
		  port: '3306',                   
		  database: 'news', 
	}); 

	connection.connect();
}

// 时间戳日期的格式化
function getLocalTime(nS) {     
   return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');     
}  

// 设置路由

// 新闻首页
app.get('/', function(req, res) {

	res.render('index', {
		name: '新闻首页'
	});
});

// 获取栏目下的新闻列表
app.get('/category/:id', function(req, res) {

	conn();

	var c_id = req.params.id;
	var  userGetSql = 'SELECT * FROM n_list where c_id =' + c_id + ' order by n_id DESC';

	// 查
	connection.query(userGetSql,function (err, result) {
	        if(err){
	          console.log('[SELECT ERROR] - ',err.message);
	          return;
	        }        

	       res.send(result);
	});

	connection.end();

});

// 后台首页
app.get('/admin/', function(req, res) {

	res.render('admin/index', {
		name: '后台首页'
	});
});


// 后台欢迎页面
app.get('/admin/welcome', function(req, res) {

	res.render('admin/welcome');
});


// 后台列表
app.get('/admin/list/', function(req, res) {

	res.render('admin/list', {
		name: '新闻列表'
	});
});

app.get('/admin/news/', function(req, res) {

	conn();

	var  userGetSql = 'SELECT * FROM n_list order by n_id DESC';

	// 查
	connection.query(userGetSql,function (err, result) {
	        if(err){
	          console.log('[SELECT ERROR] - ',err.message);
	          return;
	        }        

	       for (var i = 0; i < result.length; i++) {

	       	result[i]['n_date'] = getLocalTime(result[i]['n_date']);
	       }

	       res.send(result);
	});

});

// 添加新闻
app.get('/admin/add/', function(req, res) {

	res.render('admin/add');
});

// 执行添加新闻操作
app.get('/admin/addNews/', function(req, res) {

	conn();

	var c_id = req.query.c_id;
	var n_title = req.query.n_title;
	var n_desc = req.query.n_desc;
	var n_pic = '';

	// 转换为时间戳
	var n_date = parseInt(new Date().getTime()/1000);


	var newsAddSql = 'INSERT INTO `n_list`(`c_id`, `n_title`, `n_desc`, `n_pic`, `n_date`) VALUES (?,?,?,?,?)';
	var newsAddSql_Params = [c_id, n_title, n_desc, n_pic, n_date];

	connection.query(newsAddSql, newsAddSql_Params, function(err, result) {

		if (err) {
			console.log(err.message);
			return;
		}

		res.redirect('/admin/list');

	});


});

// 编辑新闻
app.get('/admin/edit/:n_id', function(req, res) {

	res.render('admin/edit', {n_id: req.params.n_id});
});

app.get('/admin/ajaxEdit/:n_id', function(req, res) {

	conn();

	var  userGetSql = 'SELECT * FROM n_list where n_id = ' + req.params.n_id;

	// 查
	connection.query(userGetSql,function (err, result) {
	        if(err){
	          console.log('[SELECT ERROR] - ',err.message);
	          return;
	        }       

	        res.send(result);

	});

});

// 更改新闻
app.get('/admin/update', function(req, res) {

	conn();

	var n_id = req.query.n_id;
	var c_id = req.query.c_id;
	var n_title = req.query.n_title;
	var n_desc = req.query.n_desc;
	var n_pic = '';

	// 转换为时间戳
	var n_date = parseInt(new Date().getTime()/1000);


	var newsEditSql = 'UPDATE `n_list` SET c_id= ?,n_title = ?, n_desc = ?, n_pic = ? WHERE  n_id = ?';
	var newsEditSql_Params = [c_id, n_title, n_desc, n_pic, n_id];

	connection.query(newsEditSql, newsEditSql_Params, function(err, result) {

		if (err) {
			console.log(err.message);
			return;
		}

		res.redirect('/admin/list');

	});
});

// 删除
app.get('/admin/del/:n_id', function(req, res) {

	conn();

	var newsDelSql = "DELETE FROM n_list WHERE n_id = ?";
	var newsDelSql_Params = [req.params.n_id];

	connection.query(newsDelSql, newsDelSql_Params, function(err, result) {

		if (err) {
			console.log(err.message);
			return;
		}

		res.send(result);

	});
});


// 设置监听端口
app.listen(3000);