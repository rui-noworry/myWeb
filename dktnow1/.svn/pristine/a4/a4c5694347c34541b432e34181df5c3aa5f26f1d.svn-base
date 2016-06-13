/*
说明：
ckplayer6.0,有问题请访问http://www.ckplayer.com
请注意，该文件为UTF-8编码，不需要改变编码即可使用于各种编码形式的网站内
=======================================================================
第一部分，加载插件
以下为加载的插件部份
插件的设置参数说明：
	1、插件名称
	2、水平对齐方式（0左，1中，2右）
	3、垂直对齐方式（0上，1中，2下）
	4、水平方向位置偏移量
	5、垂直方向位置偏移量
	6、插件的等级+竖线
	插件名称尽量不要相同，对此的详细说明请到网站查看
*/
function ckcpt(){
    var cpt = "";
	cpt += 'sch_lf.png,0,2,15,-37,0|';//进度栏左边的做圆角的小图片
	cpt += 'sch_lr.png,2,2,-16,-37,0|';//进度栏右边做圆角的小图片
	cpt += 'right.swf,2,1,-75,-100,2|';//边开关灯调整的插件
	cpt += 'share.swf,1,1,-180,-100,3|';//分享插件
	cpt += 'adjustment.swf,1,1,-180,-100,3|';//调整大小和颜色的插件
    return cpt;
}
/*
插件的定义结束
以下是定义总的风格以及对播放器功能进行配置
*/
function ckstyle() { //定义总的风格
    var ck = new Object();
    ck.cpath='./Js/ckplayer/assets/';
	/*播放器文件的路径，默认的是ckplyaer/assets/，如果调用不出来可以试着设置成绝对路径试试，这里只需要设置一个路径即可，下面的图片和插件的路径会自动以这个路径为准，如果不知道路径并且使用的是默认配置，可以直接留空，播放器会自动寻找*/
	ck.mylogo='logo.swf';
	/*视频加载前显示的logo文件，不使用设置成null*/
	ck.logo='cklogo.png';
	/*一直显示的logo，不使用设置成null*/
	ck.buffer='buffer.swf';
	/*缓冲时显示的图标，不使用设置成null*/
	ck.controlbar='images_buttom_bg.png';
	/*控制栏背景图片*/
	ck.cplay='images_Play_out.png,images_Play_on.png';
	/*
	播放按钮的二个图片
		1、普通状态下的图片
		2、鼠标经过时的图片
	*/
	ck.cpause='images_Pause_out.png,images_Pause_on.png';
	/*
	暂停按钮的二个图片
		1、普通状态下的图片
		2、鼠标经过时的图片
	*/
	ck.pausec='images_Pause_Scgedyke.png,images_Pause_Scgedyke_on.png';
	/*
	播放器中间暂停按钮的二个图片
		1、普通状态下的图片
		2、鼠标经过时的图片
	*/
	ck.sound='images_Sound_out.png,images_Sound_on.png';
	/*
	静音按钮的二个图片
		1、普通状态下的图片
		2、鼠标经过时的图片
	*/
	ck.mute='images_Mute_out.png,images_Mute_on.png';
	/*
	取消静音按钮的二个图片
		1、普通状态下的图片
		2、鼠标经过时的图片
	*/
	ck.full='images_Full_out.png,images_Full_on.png';
	/*
	全屏按钮的二个图片
		1、普通状态下的图片
		2、鼠标经过时的图片
	*/
	ck.general='images_General_out.png,images_General_on.png';
	/*
	取消全屏按钮的二个图片
		1、普通状态下的图片
		2、鼠标经过时的图片
	*/
	ck.cvolume='images_Volume_back.png,images_Volume_on.png,images_Volume_Float.png,images_Volume_Float_on.png';
	/*
	音量调节框的四个图片
		1、调节框的背景图片(显示总体音量，1-100)
		2、调节框的前景图片(显示当前音量的)
		3、拖动按钮普通状态下的图片
		4、拖动按钮鼠标经过时的图片
	*/
	ck.schedule='images_Schedule_bg.png,images_Schedule_load.png,images_Schedule_play.png,images_Schedule.png,images_Schedule_on.png';
	/*
	进度栏的五个图片
		1、进度栏背景图片
		2、已加载进度图片
		3、已播放进度图片
		4、拖动按钮普通状态下的图片
		5、拖动按钮鼠标经过时的图片
	*/
	ck.fast='images_Fashf_out.png,images_Fashf_on.png,images_Fashr_out.png,images_Fashr_on.png';
	/*
	快进和快退按钮图片
		1、快进按钮普通状态下的图片
		2、快进按钮鼠标经过时的图片
		3、快退按钮普通状态下的图片
		4、快退按钮鼠标经过时的图片
	*/
	ck.advmute='images_v_off_out.png,images_v_off_on.png,images_v_on_out.png,images_v_on_on.png';
	/*
	前置广告时静音按钮的四个图片
		1、静音按钮普通状态下的图片
		2、静音按钮鼠标经过时的图片
		3、取消静音按钮普通状态下的图片
		4、取消静音按钮鼠标经过时的图
	*/
	ck.advjump='images_adv_skip_out.png,images_adv_skip_on.png';
	/*
	前置广告时路过按钮的二个图片
		1、普通状态下的图片
		2、鼠标经过时的图片
	*/
	ck.advclose='images_close_adv_out.png,images_close_adv_on.png';
	/*
	关闭滚动文字广告按钮的二个图片
		1、普通状态下的图片
		2、鼠标经过时的图片
	*/
	ck.padvclose='pause_adv_close_out.png,pause_adv_close_on.png';
	/*
	关闭暂停广告按钮的二个图片
		1、普通状态下的图片
		2、鼠标经过时的图片
	*/
	/*以上是播放器用到的按钮和控制栏的图片，可以自己更改图片样式和坐标，坐标在本文档里配置*/
	/*以下是播放器自带的二个插件*/
	ck.control_rel='related.swf,./Js/ckplayer/related.xml,0';
	/*视频播放结束后显示相关视频的插件文件（注意，视频结束动作设置成3时有效），xml文件是配置文件可以自定义文件类型（比如asp,php,jsp,.net只要输出的是xml格式就行），编码0是默认的utf-8,1是gbk2312*/
	ck.control_pv='Preview.swf,105,2000';
	/*
	视频预览插件
		1、插件文件名称
		2、离进度栏的高(指的是插件的顶部离进度栏的位置)
		3、延迟时间(该处设置鼠标经过进度栏停顿多少毫秒后才显示插件)
	*/
	ck.setup='1,1,1,0,1,1,0,1,0,0,1,1,200,0,1,1,0,1,1,1,2,10,3';
	/*
	总体设置：
		1、鼠标经过按钮是否使用手型，0普通鼠标，1手型鼠标
			2、是否支持单击暂停，0不支持，1是支持
			3、是否支持双击全屏，0不支持，1是支持
			4、在播放前置广告时是否同时加载视频，0不加载，1加载
			5、广告显示的参考对象，0是参考视频区域，1是参考播放器区域
			6、广告大小的调整方式,只针对swf和图片有效,视频是自动缩放的
				=0是自动调整大小，意思是说大的话就变小，小的话就变大
				=1是大的化变小，小的话不变
				=2是什么也不变，就这么大
				=3是跟参考对像(第5个控制)参数设置的一样宽高
			7、前置广告播放顺序，0是顺序播放，1是随机播放
			8、对于视频广告是否采用修正，0是不使用，1是使用，如果是1，则用户在网速慢的情况下会按设定的倒计时进行播放广告，计时结束则放正片（比较人性化），设置成0的话，则强制播放完广告才能播放正片
			9、是否开启滚动文字广告，0是不开启，1是开启且不使用关闭按钮，2是开启并且使用关闭按钮，开启后将在加载视频的时候加载滚动文字广告
			10、视频的调整方式
				=0是自动调整大小，意思是说大的话就变小，小的话就变大，同时保持长宽比例不变
				=1是大的化变小，小的话不变
				=2是什么也不变，就这么大
				=3是跟参考对像(pm_video的设置)参数设置的一样宽高
			11、是否在多视频时分段加载，0不是，1是
			12、缩放视频时是否进行平滑处理，0不是，1是
			13、视频缓冲时间,单位：毫秒,建议不超过300
			14、初始图片调整方式(
				=0是自动调整大小，意思是说大的话就变小，小的话就变大，同时保持长宽比例不变
				=1是大的化变小，小的话不变
				=2是什么也不变，就这么大
				=3是跟pm_video参数设置的一样宽高
			15、暂停广告调整方式(
				=0是自动调整大小，意思是说大的话就变小，小的话就变大，同时保持长宽比例不变
				=1是大的化变小，小的话不变
				=2是什么也不变，就这么大
				=3是跟pm_video参数设置的一样宽
			16、暂停广告是否使用关闭广告设置，0不使用，1使用
			17、缓冲时是否播放广告，0是不显示，1是显示并同时隐藏掉缓冲图标和进度，2是显示并不隐藏缓冲图标
			18、是否支持键盘空格键控制播放和暂停0不支持，1支持
			19、是否支持键盘左右方向键控制快进快退0不支持，1支持
			20、是否支持键盘上下方向键控制音量0不支持，1支持
			21、播放器返回js交互函数的等级，0-2,等级越高，返回的参数越多
				0是不返回任何参数
				1返回播放器在播放的时候的参数，不返回广告之类的参数
				2返回全部参数
				3返回全部参数，并且在参数前加上"播放器ID->"，用于多播放器的监听
			22、快进和快退的秒数
			23、界面上图片元素加载失败重新加载次数
	*/
	ck.pm_repc='';
	/*视频地址替换符，该功能主要是用来做简单加密的功能，使用方法很简单，请到网站查看*/
	ck.pm_spac='|';
	/*视频地址间隔符，这里主要是播放多段视频时使用普通调用方式或网址调用方式时使用的。默认使用|，如果视频地址里本身存在|的话需要另外设置一个间隔符，注意，即使只有一个视频也需要设置*/
	ck.pm_logo='2,0,-100,20';
	/*
	一直显示在播放器上的logo的坐标
	本软件所有的四个参数控制坐标的方式全部都是统一的意思，如下
		0、水平对齐方式，0是左，1是中，2是右，-1是隐藏
		1、垂直对齐方式，0是上，1是中，2是下，-1是隐藏
		3、水平偏移量，举例说明，如果第1个参数设置成0左对齐，第3个偏移量设置成10，就是离左边10个像素，设置成-10，按钮就会跑到播放器外面
		4、垂直偏移量
	*/
	ck.pm_mylogo='1,1,-100,-55';
	/*视频加载前显示的logo文件(mylogo参数的)的坐标*/
	ck.pm_advtime='2,0,-110,10,0,300,0';
	/*前置广告倒计时文本坐标*/
	ck.pm_advstatus='1,2,2,-200,-40';
	/*前置广告静音按钮
		1、是否显示0不显示，1显示
		2,3,4,5坐标
	*/
	ck.pm_advjp='1,1,2,2,-100,-40';
	/*前置广告跳过广告按钮,
		1、是否显示0不显示，1是显示
		2、跳过按钮触发对象(值0/1,0是直接跳转,1是触发js:function ckadjump(){})
		3,4,5,6、坐标控制
	*/
	ck.pm_load='1,1,-50,10,1,100,0';
	/*
	提示加载视频百分比的文本框
		1，2，3，4是坐标
		5：文本对齐方式：0是左对齐，1是中间对齐，2是右对齐，3是默认对齐（相当于左对齐）
		6：文本框的宽，只有在左/右对齐时有效
		7：文本框的高
		特别说明：在所有关于文本的控制中，只有设置了左右对齐，后面的宽度才会有效果，如果设置的中间对齐，则宽度无效果
	*/
	ck.pm_buffer='1,1,-20,-35';
	/*缓冲时显示的图标(buffer图标)的坐标*/
	ck.pm_buffertext='1,1,-13,-25,0,40,0';
	/*缓冲文本框(提示加载百分比)的坐标*/
	ck.pm_ctbar='1,2,0,-30,0,30,0,1,5000';
	/*
	控制栏的参数(进度栏和滚动文字广告栏的坐标也是7个参数，都参考这里)
	这里分二种情况,前六个参数是控制第7个参数是设置定位方式(0：相对定位，1：绝对定位)
	第一种情况：第7个参数是0的时候，相对定位，就是播放器长宽变化的时候，控制栏也跟着变
		1、默认1:中间对齐
		2、上中下对齐（0是上，1是中，2是下）
		3、离左边的距离
		4、Y轴偏移量
		5、离右边的距离
		6、高度
		7、定位方式
		8、隐藏方式(0不隐藏，1全屏时隐藏，2都隐藏)
		9、隐藏延时时间，指鼠标离开控制栏后间隔多少时间隐藏
	第二种情况：第7个参数是1的时候，绝对定位，就是播放器长宽变化的时候，控制栏不跟着变，这种方式一般使用在控制栏大小不变的时候
		1、左中右对齐方式（0是左，1是中间，2是右）
		2、上中下对齐（0是上，1是中，2是下）
		3、x偏移量
		4、y偏移量
		5、宽度
		6、高度
		7、定位方式
		8、隐藏方式(0不隐藏，1全屏时隐藏，2都隐藏)
		9、隐藏延时时间*/
	ck.pm_sch='1,2,15,-37,15,5,0,14,9';
	/*进度条的参数，这里分二种情况
		1-7、前面7个参数对照控制栏的，
		8、拖动按钮的宽
		9、拖动按钮的高*/
	ck.pm_play='0,2,0,-30,35,30';
	/*
		1,2,3,4、播放和暂停按钮的坐标
		5、按钮宽
		6、按钮高
	对于按钮的控制基本上是前四个是坐标，后二个是宽高(宽度在swf播放器里并不会起作用，主要是html5里需要)
	*/
	ck.pm_clock='0,2,100,-25,2,0,0';
	/*
		1,2,3,4、播放时间和总时间文本框的坐标
		5、文本对齐方式(0左，1中，2右)
		6、宽度
		7、高度
	*/
	ck.pm_clock2='0,2,40,-25,0,0,0';
	/*同上，这里也是播放时间和总时间的文本框的控制，主要是为需要分不同地方显示已播放时间和总时间的设置*/
	ck.pm_full='2,2,-35,-30,35,30';
	/*
		1,2,3,4、全屏和取消全屏按钮的坐标
		5、宽度
		6、高度
	*/
	ck.pm_vol='2,2,-95,-18,53,4,6,12';
	/*
	音量调节框的坐标
		1,2,3,4、坐标控制
		5、音量控制区域的宽度
		6、音量控制区域的高度
		7、拖动按钮的宽度
		8、拖动按钮的高度
	*/
	ck.pm_sound='2,2,-130,-30,35,30';
	/*
		1,2,3,4、静音和取消静音的坐标
		5、宽度
		6、高度
	*/
	ck.pm_fastf='2,2,-13,-39,13,9';
	/*
		1,2,3,4快进按钮的坐标
		5、宽度
		6、高度
	*/
	ck.pm_fastr='0,2,0,-39,13,9';
	/*
		1,2,3,4、快退按钮的坐标
		5、宽度
		6、高度
		*/
	ck.pm_pa='1,1,-30,-46,60,60,0,2,10,-120';
	/*
	中间暂停按钮的坐标控制，四个一组
		1,2,3,4、没有暂停广告时的坐标
		5、宽度
		6、高度
		7,8,9,10、有暂停广告时的坐标
	*/
	ck.pm_bg='0x000000,100,230,180';
	/*播放器整体的背景配置
		1、整体背景颜色
		2、背景透明度
		3、播放器最小宽度
		4、播放器最小高度
	*/
	ck.pm_video='0,0,0,35,0x000000,0,0,0,0,0';
	/*视频固定区域
		1、控制栏未隐藏时左边预留宽
		2、控制栏未隐藏时上面预留高度
		3、控制栏未隐藏时右边预留宽度
		4、控制栏未隐藏时下面预留高度
		5、该区域背景颜色
		6、该区域背景透明度
		7、控制栏隐藏时左边预留宽
		8、控制栏隐藏时上面预留高度
		9、控制栏隐藏时右边预留宽度
		10、控制栏隐藏时下面预留高度
	*/
	ck.pm_pr='0x000000,0303030,0xffffff,0,30,80,10';
	/*
	鼠标经过按钮或进度栏显示一个文字提示框
		1、提示框背景颜色
		2、边框颜色
		3、文字的颜色
		4、边框的弧度
		5、提示框透明度
		6、文字透明度
		7、离按钮的距离，这里指提示框的底部离所需提示的按钮上方的距离
	*/
	ck.pm_advbg='0x000,100';
	/*
	播放前置广告时底部颜色和透明度，这是一个层，主要用来遮住播放器上的所有元件和元素，不让用户点击
		1、底部颜色
		2、透明度
	*/
	ck.pm_padvc='1,1,172,-160';
	/*暂停广告的关闭按钮的坐标*/
	ck.pm_start='8,5,0xFFFFFF,100';
	/*进度栏提示点宽，高，颜色,透明度*/
	ck.pm_advms='2,2,-46,-56';
	/*滚动广告关闭按钮坐标*/
	ck.pm_advmarquee='1,2,50,-60,50,18,0,0x000000,50,0,20,1,15,2000';
	/*
	滚动广告的控制，要使用的话需要在setup里的第9个参数设置成1
		前7个参数的设置对照控制栏（pm_ctbar）的进行设置
		8、是文字广告的背景色
		9、置背景色的透明度
		10、控制滚动方向，0是水平滚动（包括左右），1是上下滚动（包括向上和向下）
		11、移动的单位时长，即移动单位像素所需要的时长，毫秒
		12、移动的单位像素,正数同左/上，负数向右/下
		13、是行高，这个在设置向上或向下滚动的时候有用处
		14、控制向上或向下滚动时每次停止的时间
	*/
	ck.advmarquee='{a href="http://www.ckplayer.com"}{font color="#FFFFFF" size="12"}这里可以放文字广告，为什么要在这里放呢，{/font}{/a}{a href="http://www.ckplayer.com"}{font color="#FFFFFF" size="12"}是因为如果你想在站外调用视频并且有文字广告的话，{/a}{br}就得在这里才能被加载，在js里是不能补加载的，{br}默认使用这里的广告，如果不想在这里使用可以清空这里的内容';
	/*该处是滚动文字广告的内容，如果不想在这里设置，就把这里清空并且在页面中使用js的函数定义function ckmarqueeadv(){return '广告内容'}*/
	ck.pr_load='{font color="#FFFFFF"}已加载[$prtage]%{/font}';
	ck.pr_noload='{font color="#FFFFFF"}加载失败{/font}';
	/*加载视频百分比，字符[$prtage]将被自动替换成百分比的数字,加载失败的提示*/
	ck.pr_buffer='{font color="#FFFFFF"}[$buffer]%{/font}';
	/*视频缓冲百分比的位置默认距中，参数说明：宽度,高度,右偏移（正/负），下偏移(正/负),只显示百分比数据*/
	ck.pr_play='点击播放';
	ck.pr_pause='点击暂停';
	ck.pr_mute='点击静音';
	ck.pr_nomute='取消静音';
	ck.pr_full='点击全屏';
	ck.pr_nofull='退出全屏';
	ck.pr_fastf='快进';
	ck.pr_fastr='快退';
	ck.pr_time='[$Time]';
	/*[$Time]会自动替换目前进度提示*/
	ck.pr_volume='音量[$Volume]%';
	/*[$Volume]会自动替换音量*/
	ck.pr_clock='';
	/*这里定义进度时间的显示，并且同时会替换二个参数，[$Time]会被替换成已播放时间，[$TimeAll]会被替换成总时间*/
	ck.pr_clock2='{font color="#FFFFFF" size="12"}[$Time] | [$TimeAll][$Timewait]{/font}';
	/*同pr_clock,这二个是相等的，主要是为了方便在不同的地方调用已播放时间和总时间*/
	ck.pr_clockwait='点击播放';
	ck.pr_adv='{font color="#FFFFFF" size="12"}广告剩余：{font color="#FF0000" size="15"}{b}[$Second]{/b}{/font} 秒{/font}';//pr_adv='
	ck.myweb='';//myweb='
	/*
	------------------------------------------------------------------------------------------------------------------
	以下内容部份是和插件相关的配置，请注意，自定义插件以及其配置的命名方式要注意，不要和系统的相重复，不然就会替换掉系统的相关设置，删除相关插件的话也可以同时删除相关的配置
	------------------------------------------------------------------------------------------------------------------
	以下内容定义自定义插件的相关配置，这里也可以自定义任何自己的插件需要配置的内容，当然，如果你某个插件不使用的话，也可以删除相关的配置
	------------------------------------------------------------------------------------------------------------------
	*/
	ck.cpt_lights='1';
	/*该处定义是否使用开关灯，和right.swf插件配合作用,使用开灯效果时调用页面的js函数function closelights(){};*/
	ck.cpt_share='./Js/ckplayer/share.xml';
	/*分享插件调用的配置文件地址*/
	//调用插件开始
    ck.cpt_list = ckcpt();
    return ck;
}
/*
html5部分开始
以下代码是支持html5的，如果你不需要，可以删除。
html5代码块的代码可以随意更改以适合你的应用，欢迎到论坛交流更改心得
*/
(function() {
	var html5object= {
		getParameter:function (obj,index){
			var _obj=this._S_[obj];
			var _arr=_obj.split(',');
			if (_arr.length>index){
				return _arr[index];
			}
		},
		getVideo:function(str){
			var source='';
			if(str){
				for(var key in str){
					source+='<source src="'+key+'"';
					if(str[key]){
						source+=' type="'+str[key]+'"';
					}
					source+='>';
				}
			}
			return source;
		},
		getVars:function(vars,key){
			if(vars[key]){
				return vars[key];
			}
		},
		getParams:function(vars){
			var params='';
			if(vars){
				if(this.getVars(vars,'p')==1 && this.getVars(vars,'m')!=1){
					params+=' autoplay="autoplay"'
				}
				if(this.getVars(vars,'e')==1){
					params+=' loop="loop"'
				}
				if(this.getVars(vars,'m')==1){
					params+=' preload="meta"'
				}
				if(this.getVars(vars,'i')){
					params+=' poster="'+this.getVars(vars,'i')+'"'
				}
			}
			return params;
		},
		getXY:function(Con,Com,Img,Iid,Text,OnImg){//Con指div的id名如play_,Com指的是ckstyle对象属性名称
			this._Z_+=1;
			var cpath=this.getParameter('cpath',0);
			var _x=parseInt(this.getParameter(Com,0));
			var _y=parseInt(this.getParameter(Com,1));
			var _zx=parseInt(this.getParameter(Com,2));
			var _zy=parseInt(this.getParameter(Com,3));
			var _cw=parseInt(this.getParameter(Com,4));
			var _ch=parseInt(this.getParameter(Com,5));
			var _w=parseInt(this._K_('ck_'+this._I_).style.width);
			var _h=parseInt(this._K_('ck_'+this._I_).style.height);
			var _xz=0;
			var _yz=0;
			var _Pid=this._K_(Con+this._I_);
			var _Oimg='',_Mimg='';
			switch(Com){//定义一些特殊的位置
				case 'pm_clock':
				case 'pm_clock2':
					_cw=100;
					_ch=parseInt(this.getParameter(Com,6));
					break;
				default:
					break;
			}
			switch(_x){
				case 0:
					_xz=_zx;
					break;
				case 1:
					_xz=(_w*0.5)+_zx;
					break;
				case 2:
					_xz=_w+_zx;
					break;
				default:
					break;
			}
			switch(_y){
				case 0:
					_yz=-(_h-_zy);
					break;
				case 1:
					_yz=-((_h*0.5)-_zx);
					break;
				case 2:
					_yz=_zy;
					break;
				default:
					break;
			}
			_Pid.style.marginTop=_yz+'px';
			_Pid.style.marginLeft=_xz+'px';
			_Pid.style.position='absolute';
			_Pid.style.cursor='pointer';
			if(!Text){
				_Pid.style.width=_cw+'px';
				_Pid.style.height=_ch+'px';
			}

			if(Img){
				if(Iid>0){
					_Pid.style.backgroundImage='url('+cpath+this.getParameter(Img,Iid)+')';
				}
				else{
					_Pid.style.backgroundImage='url('+cpath+this.getParameter(Img,0)+')';
				}
				if(OnImg){
					_Oimg=cpath+this.getParameter(Img,Iid);
					_Mimg=cpath+this.getParameter(Img,(Iid+1));
					_Pid.onmouseover=function (){
						this.style.backgroundImage='url('+_Mimg+')';
					}
					_Pid.onmouseout=function (){
						this.style.backgroundImage='url('+_Oimg+')';
					}
				}
			}
			this._K_(Con+this._I_).style.zIndex=this._Z_;
		},
		getBar:function(Con,Com){//Con指div的id名如play_,Com指的是ckstyle对象属性名称
			this._Z_+=1
			var _xa=parseInt(this.getParameter(Com,0));
			var _ya=parseInt(this.getParameter(Com,1));
			var _x=parseInt(this.getParameter(Com,2));
			var _y=parseInt(this.getParameter(Com,3));
			var _zx=parseInt(this.getParameter(Com,4));
			var _zy=parseInt(this.getParameter(Com,5));
			var _d=parseInt(this.getParameter(Com,6));
			var _w=parseInt(this._K_('ck_'+this._I_).style.width);
			var _h=parseInt(this._K_('ck_'+this._I_).style.height);
			var _mw=0;
			var _mh=0;
			var _xz=0;
			var _yz=0;
			var _Pid=this._K_(Con+this._I_);
			if(_d==0){//相对定位
				switch(_ya){
					case 0:
						_yz=-(_w-_y);
						break;
					case 1:
						_yz=-((_w*0.5)-_y);
						break;
					case 2:
						_yz=-_y;
						break;
					default:
						break;
				}
				_mw=_w-_x-_zx;
				_mh=_zy;
				_xz=_x;
			}
			else{
				switch(_xa){
					case 0:
						_xz=_x;
						break;
					case 1:
						_xz=(_w*0.5)+_x;
						break;
					case 2:
						_xz=_w+_x;
						break;
					default:
						break;
				}
				switch(_ya){
					case '0':
						_yz=-(_h-_y);
						break;
					case '1':
						_yz=-((_h*0.5)-_y);
						break;
					case '2':
						_yz=-_y;
						break;
					default:
						break;
				}
				_mw=_zx;
				_mh=_zy;
			}
			_Pid.style.width=_mw+'px';
			_Pid.style.height=_mh+'px';
			_Pid.style.marginLeft=_xz+'px';
			_Pid.style.marginTop=-_yz+'px';
			_Pid.style.position='absolute';
			_Pid.style.zIndex=this._Z_;
		},
		addEventHandler:function(oTarget, sEventType, fnHandler) {//对象名称(id),事件类型,触发的函数
			if (oTarget.addEventListener) {
				oTarget.addEventListener(sEventType, fnHandler, false);
			}
			else if (oTarget.attachEvent) {
				oTarget.attachEvent("on" + sEventType, fnHandler);
			}
			else {
				oTarget["on" + sEventType] = fnHandler;
			}
		},
		removeEventHandler:function(oTarget, sEventType, fnHandler) {//对象名称(id),事件类型,触发的函数
			if (oTarget.removeEventListener) {
				oTarget.removeEventListener(sEventType, fnHandler, false);
			}
			else if (oTarget.detachEvent) {
				oTarget.detachEvent("on" + sEventType, fnHandler);
			}
			else {
				oTarget["on" + sEventType] = null;
			}
		},
		coordinate:function(){
			var cpath=this.getParameter('cpath',0);
			var _Oimg='',_Oimg2='',_Mimg='',_Mimg2='';
			this.getBar('controlbar_','pm_ctbar');
			this._K_('controlbar_'+this._I_).style.backgroundImage='url('+cpath+this.getParameter('controlbar',0)+')';
			this.getBar('schedule_','pm_sch');
			this._K_('schedule_'+this._I_).style.backgroundImage='url('+cpath+this.getParameter('schedule',0)+')';
			this.getBar('schload_','pm_sch');
			this._K_('schload_'+this._I_).style.backgroundImage='url('+cpath+this.getParameter('schedule',1)+')';
			this.getBar('schplay_','pm_sch');
			this._K_('schplay_'+this._I_).style.backgroundImage='url('+cpath+this.getParameter('schedule',2)+')';
			this.getXY('play_','pm_play','cplay',0,false,true);
			this.getXY('pause_','pm_play','cpause',0,false,true);
			this.getXY('clock_','pm_clock','',0,false,false);
			this.getXY('clock2_','pm_clock2','',0,false,false);
			this.getXY('sound_','pm_sound','sound',0,false,true);
			this.getXY('mute_','pm_sound','mute',0,false,true);
			this.getXY('full_','pm_full','full',0,false,true);
			this.getXY('general_','pm_full','general',0,false,true);
			this.getXY('fastf_','pm_fastf','fast',0,false,true);
			this.getXY('fastr_','pm_fastr','fast',2,false,true);
			this.getXY('pausec_','pm_pa','pausec',0,false,true);
			this.getXY('vol_','pm_vol','cvolume',0,false,false);
			this.getXY('vol2_','pm_vol','cvolume',1,false,false);
			//定义进度滑块和音量滑块
			this._K_('slideplay_'+this._I_).style.backgroundImage='url('+cpath+this.getParameter('schedule',3)+')';
			this._K_('slideplay_'+this._I_).style.width=this.getParameter('pm_sch',7)+'px';
			this._K_('slideplay_'+this._I_).style.height=this.getParameter('pm_sch',8)+'px';
			this._K_('slideplay_'+this._I_).style.marginTop=parseInt(parseInt(this._K_('schplay_'+this._I_).style.marginTop)+(parseInt(this._K_('schplay_'+this._I_).style.height)-parseInt(this._K_('slideplay_'+this._I_).style.height))*0.5)+'px';
			this._K_('slideplay_'+this._I_).style.position='absolute';
			this._K_('slideplay_'+this._I_).style.zIndex=this._Z_+1;
			this._K_('slideplay_'+this._I_).style.cursor='pointer';
			this._K_('slidevolume_'+this._I_).style.backgroundImage='url('+cpath+this.getParameter('cvolume',2)+')';
			this._K_('slidevolume_'+this._I_).style.width=this.getParameter('pm_vol',6)+'px';
			this._K_('slidevolume_'+this._I_).style.height=this.getParameter('pm_vol',7)+'px';
			this._K_('slidevolume_'+this._I_).style.marginTop=parseInt(parseInt(this._K_('vol2_'+this._I_).style.marginTop)+(parseInt(this._K_('vol2_'+this._I_).style.height)-parseInt(this._K_('slidevolume_'+this._I_).style.height))*0.5)+'px';
			this._K_('slidevolume_'+this._I_).style.position='absolute';
			this._K_('slidevolume_'+this._I_).style.zIndex=this._Z_+1;
			this._K_('slidevolume_'+this._I_).style.cursor='pointer';
			//注册进度拖动按钮和音量调节按钮的样式
			_Oimg=cpath+this.getParameter('schedule',3);
			_Oimg2=cpath+this.getParameter('cvolume',2);

			_Mimg=cpath+this.getParameter('schedule',4);
			_Mimg2=cpath+this.getParameter('cvolume',3);
			this._K_('slideplay_'+this._I_).onmouseover=function (){
				this.style.backgroundImage='url('+_Mimg+')';
			}
			this._K_('slideplay_'+this._I_).onmouseout=function (){
				this.style.backgroundImage='url('+_Oimg+')';
			}
			this._K_('slidevolume_'+this._I_).onmouseover=function (){
				this.style.backgroundImage='url('+_Mimg2+')';
			}
			this._K_('slidevolume_'+this._I_).onmouseout=function (){
				this.style.backgroundImage='url('+_Oimg2+')';
			}
			//注册进度拖动按钮和音量调节按钮的样式结束
			this._K_('schplay_'+this._I_).style.width='1px';
			this._SPlay=true;
			this.getSslide();
			this.getVslide();
		},
		getSslide:function(){
			if(this._SPlay){
				var _px=0;//滑动块的x_C_.getParameter('pm_fasttime',0)
				var _pw=parseInt(this.getParameter('pm_sch',7));//滑动块的宽
				var _sx=parseInt(this._K_('schplay_'+this._I_).style.marginLeft);//进度条的x
				var _sw=parseInt(this._K_('schplay_'+this._I_).style.width);//进度条的宽
				var _sw2=parseInt(this._K_('schedule_'+this._I_).style.width);//进度条的总宽
				_px=parseInt(_sx+_sw-(_pw*0.5));
				if(_px<_sx) _px=_sx;
				if(_px>(_sx+_sw2-_pw)) _px=_sx+_sw2-_pw;
				this._K_('slideplay_'+this._I_).style.marginLeft=_px+'px';
			}
		},
		getVslide:function(){
			var _vx=0;//滑动块的x
			var _vw=parseInt(this.getParameter('pm_vol',6));//滑动块的宽
			var _sx=parseInt(this._K_('vol_'+this._I_).style.marginLeft);//音量的x
			var _sw=parseInt(this._K_('vol2_'+this._I_).style.width);//音量的宽
			var _sw2=parseInt(this._K_('vol_'+this._I_).style.width);//音量的宽
			_vx=parseInt(_sx+_sw-parseInt(_vw*0.5));
			if(_vx<_sx)_vx=_sx;
			if(_vx>(_sx+_sw2-_vw))_vx=_sx+_sw2-_vw;
			this._K_('slidevolume_'+this._I_).style.marginLeft=_vx+'px';
		},
		adddiv:function(arr){//根据数组建立div
			var div='';
			if(arr){
				var id=arr.split(',');
				for(var i=0;i<id.length;i++){
					div+='<div id="'+id[i]+'_'+this._I_+'" oncontextmenu="return false;"></div>';
				}
			}
			return div;
		},
		sh:function (Id){
			var show = arguments[1] ? arguments[1] : false;
			this._K_(Id).style.display='none';
			if (show){
				this._K_(Id).style.display='block';
			}
		},
		gethtml:function(str){
			var _str=str;
			if(_str){
				_str=_str.split('{').join('<');
				_str=_str.split('}').join('>');
				_str=_str.replace(/<font/,'<span style="');
				_str=_str.replace(/\/font>/,'/span>');
				_str=_str.replace(/size="([0-9]+)"/,'font-size:$1px;line-height: 20px;');
				_str=_str.replace(/color="#(\w*)"/,'color:#$1;');
				_str=_str.replace(/face="(\w*)"/,'font-family: "$1";');
				_str=_str.replace(/face="*([\u4e00-\u9fa5]+.*)"/,'font-family: "$1";');
				_str=_str.split(';>').join(';">');
			}
			return _str;
		},
		formatTime: function(seconds) {
			var showm = arguments[1] ? arguments[1] : false;
			var s = Math.floor(seconds % 60),
			m = Math.floor(seconds / 60 % 60),
			h = Math.floor(seconds / 3600);
			s = (s < 10) ? "0" + s: s;
			m = (m>0)?((m < 10) ? "0" + m+':': m+':'):'00:';
			h = (h>0)?((h < 10) ? "0" + h+':': h+':'):'';
			if(showm && !m) m='00:';
			return h + m + s
		},
		browser:function(){//获取浏览器类型和版本
			var Browser = (function(ua){
				var a=new Object();
				var b = {
					msie: /msie/.test(ua) && !/opera/.test(ua),
					opera: /opera/.test(ua),
					safari: /webkit/.test(ua) && !/chrome/.test(ua),
					firefox: /firefox/.test(ua),
					chrome: /chrome/.test(ua)
				};
				var vMark = "";
				for (var i in b) {
					if (b[i]) { vMark = "safari" == i ? "version" : i; break; }
				}
				b.version = vMark && RegExp("(?:" + vMark + ")[\\/: ]([\\d.]+)").test(ua) ? RegExp.$1 : "0";
				b.ie = b.msie;
				b.ie6 = b.msie && parseInt(b.version, 10) == 6;
				b.ie7 = b.msie && parseInt(b.version, 10) == 7;
				b.ie8 = b.msie && parseInt(b.version, 10) == 8;
				a.B=vMark;
				a.V=b.version
				return a;
			})(window.navigator.userAgent.toLowerCase());
			return Browser;
		},
		Platform:function(){//平台名称
			var w='';
			var u = navigator.userAgent, app = navigator.appVersion;
			var b={
				iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器
				iPad: u.indexOf('iPad') > -1, //是否iPad
				ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
				android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
				trident: u.indexOf('Trident') > -1, //IE内核
				presto: u.indexOf('Presto') > -1, //opera内核
				webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
				gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
				mobile: !!u.match(/AppleWebKit.*Mobile.*/)||!!u.match(/AppleWebKit/), //是否为移动终端
				webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
			};
			for (var key in b){
				if(b[key]){
					w=key;
					break;
				}
			}
			return w;
		},
		getidPoint:function(e,xy){
			var x = e.offsetLeft,y = e.offsetTop;
			while(e=e.offsetParent){
			   x += e.offsetLeft;
			   y += e.offsetTop;
			}
			if(xy){
				return x;
			}
			else{
				return y;
			}
		},
		formatClock:function(Time,TimeAll){
			this._K_('clock_'+this._I_).innerHTML=this.gethtml(this._S_['pr_clock']).split('[$Time]').join(Time).split('[$TimeAll]').join(TimeAll).split('[$Timewait]').join('');
			this._K_('clock2_'+this._I_).innerHTML=this.gethtml(this._S_['pr_clock2']).split('[$Time]').join(Time).split('[$TimeAll]').join(TimeAll).split('[$Timewait]').join('');
		},
		embedHTML5:function(Container,PlayerId,Width,Height,Video,Vars,support){//容器,视频播放器ID,宽,高,视频地址对象,设置对象-相当于swf调用时的flashvars
			var show=false;
			var b=this.browser()['B'];
			var v=this.browser()['V'];
			var va=v.split('.');
			var v1=va[0];
			this.Plat=this.Platform();
			var br=b+v;
			var br2=b+v1;
			if(!support){
				support=['iPad','iPhone','ios'];
			}
			for(var i=0;i<support.length;i++){
				if(this.Plat==support[i] || br==support[i] || br2==support[i]){
					show=true;
					break;
				}
			}
			if(show){
				this.ck_HTML5(Container,PlayerId,Width,Height,Video,Vars,support);
			}
		},
		ck_HTML5:function(Container,PlayerId,Width,Height,Video,Vars,support){
			var _C_=html5object;
			this._I_=PlayerId;
			this._S_ = ckstyle();
			this._K_=function(str){return document.getElementById(str);};
			this._Z_=100;
			this._CW_=Width;
			this._CH_=Height;
			var V='<div id="ck_'+PlayerId+'" style="height:'+Height+'px;"><video oncontextmenu="return false;"  id="'+PlayerId+'" width="'+Width+'" height="'+(Height-this.getParameter('pm_video',3))+'"'+this.getParams(Vars)+'>'+this.getVideo(Video)+'</video></div>';
			var V2='<div id="ck_'+PlayerId+'" style="height:'+Height+'px;"><video controls id="'+PlayerId+'" width="'+Width+'" height="'+Height+'"'+this.getParams(Vars)+'>'+this.getVideo(Video)+'</video></div>';
			var D=this.adddiv('controlbar,schedule,schload,schplay,slideplay,slidevolume,play,pause,sound,mute,vol,vol2,full,general,fastf,fastr,pausec,clock,clock2');
			if(this.Plat=='iPad' || this.Plat=='iPhone' || this.Plat=='ios'){
				this._K_(Container).innerHTML=V2;
			}
			else{
				this._K_(Container).innerHTML=V+D;
			}
			this._V_=this._K_(PlayerId);
			this._D_=this._K_(Container);
			this._K_(Container).style.width=Width+'px';
			this._K_(Container).style.height=Height+'px';
			this._K_(Container).style.overflow='hidden';
			if(this.getVars(Vars,'b')) this._K_(Container).style.backgroundColor=this.getVars(Vars,'b').replace('0x','#');
			this._K_('ck_'+this._I_).style.width=Width+'px';
			this._K_('ck_'+this._I_).style.height=Height+'px';
			this.coordinate();
			if(this.getVars(Vars,'p')==1){
				this.sh('play_'+PlayerId,true);
				this.sh('pausec_'+PlayerId,true);
			}
			else{
				this.sh('pause_'+PlayerId);
			}
			this.sh('mute_'+PlayerId);
			this.sh('general_'+PlayerId);
			this.formatClock('00:00','00:00');
			if (this.getVars(Vars,'v')){this._V_.volume=this.getVars(Vars,'v')*0.01;}else{this._V_.volume=0.8;}
			//各按钮的动作
			this.addEventHandler(this._V_,'click',function(){_C_.playorpause();},false);
			this.addEventHandler(this._K_('play_'+PlayerId),'click',function(){_C_.playorpause();},false);
			this.addEventHandler(this._K_('pause_'+PlayerId),'click',function(){_C_.playorpause();},false);
			this.addEventHandler(this._K_('pausec_'+PlayerId),'click',function(){_C_.playorpause();},false);
			this.addEventHandler(this._K_('sound_'+PlayerId),'click',function(){_C_.vmuted();},false);
			this.addEventHandler(this._K_('mute_'+PlayerId),'click',function(){_C_.vmuted();},false);
			this.addEventHandler(this._K_('fastf_'+PlayerId),'click',function(){_C_.fastforward('next');},false);
			this.addEventHandler(this._K_('fastr_'+PlayerId),'click',function(){_C_.fastforward('last');},false);
			this.addEventHandler(this._K_('full_'+PlayerId),'click',function(){_C_.fullscreen();},false);
			this.addEventHandler(this._K_('general_'+PlayerId),'click',function(){_C_.fullscreen();},false);
			this.addEventHandler(this._V_,'play',function(){_C_.Status_play();},false);
			this.addEventHandler(this._V_,'pause',function(){_C_.Status_play();},false);
			this.addEventHandler(this._V_,'error',function(){_C_.Status_error();},false);
			this.addEventHandler(this._V_,'loadstart',function(){_C_.Status_loadstart();},false);
			this.addEventHandler(this._V_,'loadedmetadata',function(){_C_.Status_loadedmetadata();},false);
			this.addEventHandler(this._V_,'ended',function(){_C_.Status_ended();},false);
			this.addEventHandler(this._V_,'timeupdate',function(){_C_.Status_timeupdate();},false);
			this.addEventHandler(this._V_,'volumechange',function(){_C_.Status_volumechange();},false);
			//监听拖动块
			this._SVDown=function(){_C_.slidevolume_mousedown();};
			this._SVMove=function(){_C_.slidevolume_mousemove();};
			this._SVUp=function(){_C_.slidevolume_mouseup();};
			//进度拖动块
			this._SPDown=function(){_C_.slideplay_mousedown();};
			this._SPMove=function(){_C_.slideplay_mousemove();};
			this._SPUp=function(){_C_.slideplay_mouseup();};
			this.addEventHandler(this._K_('slidevolume_'+PlayerId),'mousedown',this._SVDown);//音量拖动块
			this.addEventHandler(this._K_('vol_'+PlayerId),'mousedown',this._SVDown);
			this.addEventHandler(this._K_('vol2_'+PlayerId),'mousedown',this._SVDown);
			this.addEventHandler(this._K_('vol_'+PlayerId),'click',function(){_C_.slidevolume_click();});
			this.addEventHandler(this._K_('vol2_'+PlayerId),'click',function(){_C_.slidevolume_click();});
			this.addEventHandler(this._K_('slideplay_'+PlayerId),'mousedown',this._SPDown);//进度拖动块
			this.addEventHandler(this._K_('schedule_'+PlayerId),'mousedown',this._SPDown);
			this.addEventHandler(this._K_('schload_'+PlayerId),'mousedown',this._SPDown);
			this.addEventHandler(this._K_('schplay_'+PlayerId),'mousedown',this._SPDown);
			this.addEventHandler(this._K_('schedule_'+PlayerId),'click',function(){_C_.slideplay_click();});
			this.addEventHandler(this._K_('schload_'+PlayerId),'click',function(){_C_.slideplay_click();});
			this.addEventHandler(this._K_('schplay_'+PlayerId),'click',function(){_C_.slideplay_click();});
			this.addEventHandler(this._D_,_FullApi.fullScreenEventName,function(){if (_FullApi.isFullScreen()) {_C_.resetcoor();} else {_C_.reduction();}},true);
		},
		fullscreen:function(){
			if (!_FullApi.isFullScreen()) {
				window._FullApi.requestFullScreen(this._D_);
				this.resetcoor();//其实这里执行了二次，是针对部分浏览器兼容的
			}
			else {
				window._FullApi.cancelFullScreen(document);
				this.reduction();
			}
		},
		resetcoor:function(){//全屏后重新定位控件
			//alert(window.screen.width);
			this._D_.style.width=window.screen.width+'px';
			this._D_.style.height=window.screen.height+'px';
			this._K_('ck_'+this._I_).style.width=this._D_.offsetWidth+'px';
			this._K_('ck_'+this._I_).style.height=this._D_.offsetHeight+'px';
			this._V_.width=this._D_.offsetWidth;
			this._V_.height=(this._D_.offsetHeight-this.getParameter('pm_video',3))
			this.coordinate();
			this.sh('full_'+this._I_);
			this.sh('general_'+this._I_,true);
		},
		reduction:function(){//退出全屏后重新定位控件
			this._D_.style.width=this._CW_+'px';
			this._D_.style.height=this._CH_+'px';
			this._K_('ck_'+this._I_).style.width=this._D_.offsetWidth+'px';
			this._K_('ck_'+this._I_).style.height=this._D_.offsetHeight+'px';
			this._V_.width=this._CW_;
			this._V_.height=(this._CH_-this.getParameter('pm_video',3))
			this.coordinate();
			this.sh('full_'+this._I_,true);
			this.sh('general_'+this._I_);
		},
		slidevolume_mousedown:function(){//在音量控制栏按下鼠标
			this._x=parseInt(this._K_('slidevolume_'+this._I_).style.marginLeft);//滑块离播放器左边的距离
			this._svx=this.getidPoint(this._K_(this._I_),true);
			this.addEventHandler(document, "mousemove",this._SVMove);
			this.addEventHandler(document, "mouseup",this._SVUp);
		},
		slidevolume_mousemove:function(){//在音量控制栏按下鼠标后的拖动动作
			var _SV_=this._K_('slidevolume_'+this._I_);
			var _nx=event.clientX-this._svx-parseInt(parseInt(_SV_.style.width)*0.5);
			var _left=parseInt(this._K_('vol_'+this._I_).style.marginLeft);
			var _right=parseInt(parseInt(_left + parseInt(this._K_('vol_'+this._I_).style.width))-parseInt(_SV_.style.width));
			if (_nx<_left) _nx=_left;
			if (_nx>_right) _nx=_right;
			_SV_.style.marginLeft=_nx+'px';
			this._K_('vol2_'+this._I_).style.width=_nx-_left+parseInt(parseInt(_SV_.style.width)*0.5)+'px';
			this.getVslide();
		},
		slidevolume_mouseup:function(){//音量控制栏上鼠标弹起的动作
			this.removeEventHandler(document, "mousemove",this._SVMove);
			this.removeEventHandler(document, "mouseup", this._SVUp);
		},
		slidevolume_click:function(){//音量控制栏上点击的动作
			this._svx=this.getidPoint(this._K_(this._I_),true);
			var _SV_=this._K_('slidevolume_'+this._I_);
			var _nx=event.clientX-this._svx-parseInt(parseInt(_SV_.style.width)*0.5);
			var _left=parseInt(this._K_('vol_'+this._I_).style.marginLeft);
			var _right=parseInt(parseInt(_left + parseInt(this._K_('vol_'+this._I_).style.width))-parseInt(_SV_.style.width));
			if (_nx<_left) _nx=_left;
			if (_nx>_right) _nx=_right;
			_SV_.style.marginLeft=_nx+'px';
			this._K_('vol2_'+this._I_).style.width=_nx-_left+parseInt(parseInt(_SV_.style.width)*0.5)+'px';
			this.getVslide();
		},
		slideplay_mousedown:function(){//进度栏上按下鼠标的动作
			this._SPlay=false;
			this._x=parseInt(this._K_('slideplay_'+this._I_).style.marginLeft);//滑块离播放器左边的距离
			this._svx=this.getidPoint(this._K_(this._I_),true);
			this.addEventHandler(document, "mousemove",this._SPMove);
			this.addEventHandler(document, "mouseup",this._SPUp);
		},
		slideplay_mousemove:function(){//进度栏鼠标按下后拖动
			var _SV_=this._K_('slideplay_'+this._I_);
			var _nx=event.clientX-this._svx-parseInt(parseInt(_SV_.style.width)*0.5);
			var _left=parseInt(this._K_('schload_'+this._I_).style.marginLeft);
			var _right=parseInt(parseInt(_left + parseInt(this._K_('schedule_'+this._I_).style.width))-parseInt(_SV_.style.width));
			if (_nx<_left) _nx=_left;
			if (_nx>_right) _nx=_right;
			_SV_.style.marginLeft=_nx+'px';
			this._K_('schplay_'+this._I_).style.width=_nx-_left+parseInt(parseInt(_SV_.style.width)*0.5)+'px';
		},
		slideplay_mouseup:function(){//进度栏鼠标弹起的动作
			this.removeEventHandler(document, "mousemove",this._SPMove);
			this.removeEventHandler(document, "mouseup", this._SPUp);
			this.slideplay_play();
		},
		slideplay_play:function(){//根据播放条所指位置得到要播放的时间
			var _tw=parseInt(this._V_.duration);//获取总时间
			var _sw=parseInt(this._K_('schplay_'+this._I_).style.width);//进度条的宽
			var _sw2=parseInt(this._K_('schedule_'+this._I_).style.width);//进度条的总宽
			var _nt=parseInt(_tw*_sw/_sw2);
			var _seekend=this._V_.seekable.end(0);
			var _seekstart=this._V_.seekable.start(0);
			if(_nt<_seekstart){
				_nt=_seekstart
			}
			if(_nt>_seekend){
				_nt=_seekend
			}
			this._V_.currentTime=_nt;
			this.getSslide();
			this._SPlay=true;
			this._V_.play()
		},
		slideplay_click:function(){//进度栏鼠标点击的动作
			this._SPlay=false;
			this._svx=this.getidPoint(this._K_(this._I_),true);
			var _SV_=this._K_('slideplay_'+this._I_);
			var _nx=event.clientX-this._svx-parseInt(parseInt(_SV_.style.width)*0.5);
			var _left=parseInt(this._K_('schload_'+this._I_).style.marginLeft);
			var _right=parseInt(parseInt(_left + parseInt(this._K_('schedule_'+this._I_).style.width))-parseInt(_SV_.style.width));
			if (_nx<_left) _nx=_left;
			if (_nx>_right) _nx=_right;
			_SV_.style.marginLeft=_nx+'px';
			this._K_('schplay_'+this._I_).style.width=_nx-_left+parseInt(parseInt(_SV_.style.width)*0.5)+'px';
			this.slideplay_play();
		},
		fastforward:function(str){//快进快退的动作
			var _nt=parseInt(this._V_.currentTime);
			var _ft=parseInt(this.getParameter('setup',21));
			var _seekend=this._V_.seekable.end(0);
			var _seekstart=this._V_.seekable.start(0);
			if(str=='next'){
				_nt+=_ft;
			}
			else{
				_nt-=_ft;
			}
			if(_nt<_seekstart){
				_nt=_seekstart
			}
			if(_nt>_seekend){
				_nt=_seekend
			}
			this._V_.currentTime=_nt;
			this.getSslide();
			this._SPlay=true;
			this._V_.play()
		},
		Status_ended:function(){//视频播放结束
			//alert(obj.duration);
		},
		Status_loadedmetadata:function(){//当视频读取到元数据时
			this.formatClock('00:00',this.formatTime(this._V_.duration));
			this.Status_buffered();
		},
		Status_buffered:function(){//当视频缓冲时
			var buffered = this._V_.buffered,
			start = 0,
			end = this._V_.duration;
			if (buffered && buffered.length > 0 && buffered.end(0) !== end) {
				end = buffered.end(0);
				setTimeout(function(){this.Status_buffered()},500);
			}
			this.Status_loadend(buffered.end(0));
		},
		Status_loadstart:function(obj){//加载开始
			//alert(obj.duration);
		},
		Status_error:function(obj){//播放出错
			//alert(obj.error);
		},
		Status_timeupdate:function(){//计算时间
			var _nt=parseInt(this._V_.currentTime);//当前时间
			var _at=parseInt(this._V_.duration);
			var _sw=parseInt(this.getParameter('pm_sch',7));//滑块宽
			var _jw=parseInt(this._K_('schedule_'+this._I_).style.width)-parseInt(_sw*0.5);//背景宽
			var _nw=parseInt(_nt*_jw/_at);
			if(this._SPlay){
				this._K_('schplay_'+this._I_).style.width=_nw+'px';
				this.getSslide();
			}
			this.formatClock(this.formatTime(this._V_.currentTime,true),this.formatTime(this._V_.duration));
		},
		Status_loadend:function(nowbuffer){//加载结束
			var _nt=nowbuffer;//当前已加载
			var _at=this._V_.duration;//总需加载
			var _jw=parseInt(this._K_('schedule_'+this._I_).style.width);//背景宽
			var _nw=Math.ceil(_nt*_jw/_at);
			this._K_('schload_'+this._I_).style.width=_nw+'px';
		},
		Status_play:function(){//监听播放
			if(this._V_.paused){
				this.sh('play_'+this._I_,true);
				this.sh('pausec_'+this._I_,true);
				this.sh('pause_'+this._I_);
			}
			else{
				this.sh('play_'+this._I_);
				this.sh('pausec_'+this._I_);
				this.sh('pause_'+this._I_,true);
			}
		},
		Status_volumechange:function(){//改变音量
			var _nv=parseInt(this._V_.volume*100);//当前音量
			var _sw=parseInt(this.getParameter('pm_vol',6));//滑块宽
			var _vw=parseInt(this._K_('vol_'+this._I_).style.width)-parseInt(_sw*0.5);//音量背景宽
			var _nw=parseInt(_vw*_nv*0.01);
			this._K_('vol2_'+this._I_).style.width=_nw+'px';
			this.getVslide();
			if(this._V_.muted){
				this.sh('sound_'+this._I_);
				this.sh('mute_'+this._I_,true);
			}
			else{
				this.sh('sound_'+this._I_,true);
				this.sh('mute_'+this._I_);
			}
		},
		playorpause:function(){
			this._V_.paused ? this._V_.play() : this._V_.pause();
		},
		vmuted:function(obj){
			this._V_.muted ? this._V_.muted=false : this._V_.muted=true;
		}
	},
	_FullApi = {//全屏控制
		supportsFullScreen: false,
		isFullScreen: function() { return false; },
		requestFullScreen: function() {},
		cancelFullScreen: function() {},
		fullScreenEventName: '',
		prefix: ''
	},
	browserPrefixes = 'webkit moz o ms khtml'.split(' ');
	if (typeof document.cancelFullScreen != 'undefined') {
		_FullApi.supportsFullScreen = true;
	}
	else {
		for (var i = 0, il = browserPrefixes.length; i < il; i++ ) {
			_FullApi.prefix = browserPrefixes[i];
			if (typeof document[_FullApi.prefix + 'CancelFullScreen' ] != 'undefined' ) {
				_FullApi.supportsFullScreen = true;
				break;
			}
		}
	}
	if (_FullApi.supportsFullScreen) {
		_FullApi.fullScreenEventName = _FullApi.prefix + 'fullscreenchange';
		_FullApi.isFullScreen = function() {
			switch (this.prefix) {
				case '':
					return document.fullScreen;
				case 'webkit':
					return document.webkitIsFullScreen;
				default:
					return document[this.prefix + 'FullScreen'];
			}
		}
		_FullApi.requestFullScreen = function(el) {
			return (this.prefix === '') ? el.requestFullScreen() : el[this.prefix + 'RequestFullScreen']();
		}
		_FullApi.cancelFullScreen = function(el) {
			return (this.prefix === '') ? document.cancelFullScreen() : document[this.prefix + 'CancelFullScreen']();
		}
	}
	window._FullApi = _FullApi;
	window.html5object = html5object;
})();
/*
html5 部分结束
======================================================
SWFObject v2.2
如果你的网站里已经有swfobject类，可以删除下面的
*/
var swfobject=function(){var D="undefined",r="object",S="Shockwave Flash",W="ShockwaveFlash.ShockwaveFlash",q="application/x-shockwave-flash",R="SWFObjectExprInst",x="onreadystatechange",O=window,j=document,t=navigator,T=false,U=[h],o=[],N=[],I=[],l,Q,E,B,J=false,a=false,n,G,m=true,M=function(){var aa=typeof j.getElementById!=D&&typeof j.getElementsByTagName!=D&&typeof j.createElement!=D,ah=t.userAgent.toLowerCase(),Y=t.platform.toLowerCase(),ae=Y?/win/.test(Y):/win/.test(ah),ac=Y?/mac/.test(Y):/mac/.test(ah),af=/webkit/.test(ah)?parseFloat(ah.replace(/^.*webkit\/(\d+(\.\d+)?).*$/,"$1")):false,X=!+"\v1",ag=[0,0,0],ab=null;if(typeof t.plugins!=D&&typeof t.plugins[S]==r){ab=t.plugins[S].description;if(ab&&!(typeof t.mimeTypes!=D&&t.mimeTypes[q]&&!t.mimeTypes[q].enabledPlugin)){T=true;X=false;ab=ab.replace(/^.*\s+(\S+\s+\S+$)/,"$1");ag[0]=parseInt(ab.replace(/^(.*)\..*$/,"$1"),10);ag[1]=parseInt(ab.replace(/^.*\.(.*)\s.*$/,"$1"),10);ag[2]=/[a-zA-Z]/.test(ab)?parseInt(ab.replace(/^.*[a-zA-Z]+(.*)$/,"$1"),10):0}}else{if(typeof O.ActiveXObject!=D){try{var ad=new ActiveXObject(W);if(ad){ab=ad.GetVariable("$version");if(ab){X=true;ab=ab.split(" ")[1].split(",");ag=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)]}}}catch(Z){}}}return{w3:aa,pv:ag,wk:af,ie:X,win:ae,mac:ac}}(),k=function(){if(!M.w3){return}if((typeof j.readyState!=D&&j.readyState=="complete")||(typeof j.readyState==D&&(j.getElementsByTagName("body")[0]||j.body))){f()}if(!J){if(typeof j.addEventListener!=D){j.addEventListener("DOMContentLoaded",f,false)}if(M.ie&&M.win){j.attachEvent(x,function(){if(j.readyState=="complete"){j.detachEvent(x,arguments.callee);f()}});if(O==top){(function(){if(J){return}try{j.documentElement.doScroll("left")}catch(X){setTimeout(arguments.callee,0);return}f()})()}}if(M.wk){(function(){if(J){return}if(!/loaded|complete/.test(j.readyState)){setTimeout(arguments.callee,0);return}f()})()}s(f)}}();function f(){if(J){return}try{var Z=j.getElementsByTagName("body")[0].appendChild(C("span"));Z.parentNode.removeChild(Z)}catch(aa){return}J=true;var X=U.length;for(var Y=0;Y<X;Y++){U[Y]()}}function K(X){if(J){X()}else{U[U.length]=X}}function s(Y){if(typeof O.addEventListener!=D){O.addEventListener("load",Y,false)}else{if(typeof j.addEventListener!=D){j.addEventListener("load",Y,false)}else{if(typeof O.attachEvent!=D){i(O,"onload",Y)}else{if(typeof O.onload=="function"){var X=O.onload;O.onload=function(){X();Y()}}else{O.onload=Y}}}}}function h(){if(T){V()}else{H()}}function V(){var X=j.getElementsByTagName("body")[0];var aa=C(r);aa.setAttribute("type",q);var Z=X.appendChild(aa);if(Z){var Y=0;(function(){if(typeof Z.GetVariable!=D){var ab=Z.GetVariable("$version");if(ab){ab=ab.split(" ")[1].split(",");M.pv=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)]}}else{if(Y<10){Y++;setTimeout(arguments.callee,10);return}}X.removeChild(aa);Z=null;H()})()}else{H()}}function H(){var ag=o.length;if(ag>0){for(var af=0;af<ag;af++){var Y=o[af].id;var ab=o[af].callbackFn;var aa={success:false,id:Y};if(M.pv[0]>0){var ae=c(Y);if(ae){if(F(o[af].swfVersion)&&!(M.wk&&M.wk<312)){w(Y,true);if(ab){aa.success=true;aa.ref=z(Y);ab(aa)}}else{if(o[af].expressInstall&&A()){var ai={};ai.data=o[af].expressInstall;ai.width=ae.getAttribute("width")||"0";ai.height=ae.getAttribute("height")||"0";if(ae.getAttribute("class")){ai.styleclass=ae.getAttribute("class")}if(ae.getAttribute("align")){ai.align=ae.getAttribute("align")}var ah={};var X=ae.getElementsByTagName("param");var ac=X.length;for(var ad=0;ad<ac;ad++){if(X[ad].getAttribute("name").toLowerCase()!="movie"){ah[X[ad].getAttribute("name")]=X[ad].getAttribute("value")}}P(ai,ah,Y,ab)}else{p(ae);if(ab){ab(aa)}}}}}else{w(Y,true);if(ab){var Z=z(Y);if(Z&&typeof Z.SetVariable!=D){aa.success=true;aa.ref=Z}ab(aa)}}}}}function z(aa){var X=null;var Y=c(aa);if(Y&&Y.nodeName=="OBJECT"){if(typeof Y.SetVariable!=D){X=Y}else{var Z=Y.getElementsByTagName(r)[0];if(Z){X=Z}}}return X}function A(){return !a&&F("6.0.65")&&(M.win||M.mac)&&!(M.wk&&M.wk<312)}function P(aa,ab,X,Z){a=true;E=Z||null;B={success:false,id:X};var ae=c(X);if(ae){if(ae.nodeName=="OBJECT"){l=g(ae);Q=null}else{l=ae;Q=X}aa.id=R;if(typeof aa.width==D||(!/%$/.test(aa.width)&&parseInt(aa.width,10)<310)){aa.width="310"}if(typeof aa.height==D||(!/%$/.test(aa.height)&&parseInt(aa.height,10)<137)){aa.height="137"}j.title=j.title.slice(0,47)+" - Flash Player Installation";var ad=M.ie&&M.win?"ActiveX":"PlugIn",ac="MMredirectURL="+O.location.toString().replace(/&/g,"%26")+"&MMplayerType="+ad+"&MMdoctitle="+j.title;if(typeof ab.flashvars!=D){ab.flashvars+="&"+ac}else{ab.flashvars=ac}if(M.ie&&M.win&&ae.readyState!=4){var Y=C("div");X+="SWFObjectNew";Y.setAttribute("id",X);ae.parentNode.insertBefore(Y,ae);ae.style.display="none";(function(){if(ae.readyState==4){ae.parentNode.removeChild(ae)}else{setTimeout(arguments.callee,10)}})()}u(aa,ab,X)}}function p(Y){if(M.ie&&M.win&&Y.readyState!=4){var X=C("div");Y.parentNode.insertBefore(X,Y);X.parentNode.replaceChild(g(Y),X);Y.style.display="none";(function(){if(Y.readyState==4){Y.parentNode.removeChild(Y)}else{setTimeout(arguments.callee,10)}})()}else{Y.parentNode.replaceChild(g(Y),Y)}}function g(ab){var aa=C("div");if(M.win&&M.ie){aa.innerHTML=ab.innerHTML}else{var Y=ab.getElementsByTagName(r)[0];if(Y){var ad=Y.childNodes;if(ad){var X=ad.length;for(var Z=0;Z<X;Z++){if(!(ad[Z].nodeType==1&&ad[Z].nodeName=="PARAM")&&!(ad[Z].nodeType==8)){aa.appendChild(ad[Z].cloneNode(true))}}}}}return aa}function u(ai,ag,Y){var X,aa=c(Y);if(M.wk&&M.wk<312){return X}if(aa){if(typeof ai.id==D){ai.id=Y}if(M.ie&&M.win){var ah="";for(var ae in ai){if(ai[ae]!=Object.prototype[ae]){if(ae.toLowerCase()=="data"){ag.movie=ai[ae]}else{if(ae.toLowerCase()=="styleclass"){ah+=' class="'+ai[ae]+'"'}else{if(ae.toLowerCase()!="classid"){ah+=" "+ae+'="'+ai[ae]+'"'}}}}}var af="";for(var ad in ag){if(ag[ad]!=Object.prototype[ad]){af+='<param name="'+ad+'" value="'+ag[ad]+'" />'}}aa.outerHTML='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'+ah+">"+af+"</object>";N[N.length]=ai.id;X=c(ai.id)}else{var Z=C(r);Z.setAttribute("type",q);for(var ac in ai){if(ai[ac]!=Object.prototype[ac]){if(ac.toLowerCase()=="styleclass"){Z.setAttribute("class",ai[ac])}else{if(ac.toLowerCase()!="classid"){Z.setAttribute(ac,ai[ac])}}}}for(var ab in ag){if(ag[ab]!=Object.prototype[ab]&&ab.toLowerCase()!="movie"){e(Z,ab,ag[ab])}}aa.parentNode.replaceChild(Z,aa);X=Z}}return X}function e(Z,X,Y){var aa=C("param");aa.setAttribute("name",X);aa.setAttribute("value",Y);Z.appendChild(aa)}function y(Y){var X=c(Y);if(X&&X.nodeName=="OBJECT"){if(M.ie&&M.win){X.style.display="none";(function(){if(X.readyState==4){b(Y)}else{setTimeout(arguments.callee,10)}})()}else{X.parentNode.removeChild(X)}}}function b(Z){var Y=c(Z);if(Y){for(var X in Y){if(typeof Y[X]=="function"){Y[X]=null}}Y.parentNode.removeChild(Y)}}function c(Z){var X=null;try{X=j.getElementById(Z)}catch(Y){}return X}function C(X){return j.createElement(X)}function i(Z,X,Y){Z.attachEvent(X,Y);I[I.length]=[Z,X,Y]}function F(Z){var Y=M.pv,X=Z.split(".");X[0]=parseInt(X[0],10);X[1]=parseInt(X[1],10)||0;X[2]=parseInt(X[2],10)||0;return(Y[0]>X[0]||(Y[0]==X[0]&&Y[1]>X[1])||(Y[0]==X[0]&&Y[1]==X[1]&&Y[2]>=X[2]))?true:false}function v(ac,Y,ad,ab){if(M.ie&&M.mac){return}var aa=j.getElementsByTagName("head")[0];if(!aa){return}var X=(ad&&typeof ad=="string")?ad:"screen";if(ab){n=null;G=null}if(!n||G!=X){var Z=C("style");Z.setAttribute("type","text/css");Z.setAttribute("media",X);n=aa.appendChild(Z);if(M.ie&&M.win&&typeof j.styleSheets!=D&&j.styleSheets.length>0){n=j.styleSheets[j.styleSheets.length-1]}G=X}if(M.ie&&M.win){if(n&&typeof n.addRule==r){n.addRule(ac,Y)}}else{if(n&&typeof j.createTextNode!=D){n.appendChild(j.createTextNode(ac+" {"+Y+"}"))}}}function w(Z,X){if(!m){return}var Y=X?"visible":"hidden";if(J&&c(Z)){c(Z).style.visibility=Y}else{v("#"+Z,"visibility:"+Y)}}function L(Y){var Z=/[\\\"<>\.;]/;var X=Z.exec(Y)!=null;return X&&typeof encodeURIComponent!=D?encodeURIComponent(Y):Y}var d=function(){if(M.ie&&M.win){window.attachEvent("onunload",function(){var ac=I.length;for(var ab=0;ab<ac;ab++){I[ab][0].detachEvent(I[ab][1],I[ab][2])}var Z=N.length;for(var aa=0;aa<Z;aa++){y(N[aa])}for(var Y in M){M[Y]=null}M=null;for(var X in swfobject){swfobject[X]=null}swfobject=null})}}();return{registerObject:function(ab,X,aa,Z){if(M.w3&&ab&&X){var Y={};Y.id=ab;Y.swfVersion=X;Y.expressInstall=aa;Y.callbackFn=Z;o[o.length]=Y;w(ab,false)}else{if(Z){Z({success:false,id:ab})}}},getObjectById:function(X){if(M.w3){return z(X)}},embedSWF:function(ab,ah,ae,ag,Y,aa,Z,ad,af,ac){var X={success:false,id:ah};if(M.w3&&!(M.wk&&M.wk<312)&&ab&&ah&&ae&&ag&&Y){w(ah,false);K(function(){ae+="";ag+="";var aj={};if(af&&typeof af===r){for(var al in af){aj[al]=af[al]}}aj.data=ab;aj.width=ae;aj.height=ag;var am={};if(ad&&typeof ad===r){for(var ak in ad){am[ak]=ad[ak]}}if(Z&&typeof Z===r){for(var ai in Z){if(typeof am.flashvars!=D){am.flashvars+="&"+ai+"="+Z[ai]}else{am.flashvars=ai+"="+Z[ai]}}}if(F(Y)){var an=u(aj,am,ah);if(aj.id==ah){w(ah,true)}X.success=true;X.ref=an}else{if(aa&&A()){aj.data=aa;P(aj,am,ah,ac);return}else{w(ah,true)}}if(ac){ac(X)}})}else{if(ac){ac(X)}}},switchOffAutoHideShow:function(){m=false},ua:M,getFlashPlayerVersion:function(){return{major:M.pv[0],minor:M.pv[1],release:M.pv[2]}},hasFlashPlayerVersion:F,createSWF:function(Z,Y,X){if(M.w3){return u(Z,Y,X)}else{return undefined}},showExpressInstall:function(Z,aa,X,Y){if(M.w3&&A()){P(Z,aa,X,Y)}},removeSWF:function(X){if(M.w3){y(X)}},createCSS:function(aa,Z,Y,X){if(M.w3){v(aa,Z,Y,X)}},addDomLoadEvent:K,addLoadEvent:s,getQueryParamValue:function(aa){var Z=j.location.search||j.location.hash;if(Z){if(/\?/.test(Z)){Z=Z.split("?")[1]}if(aa==null){return L(Z)}var Y=Z.split("&");for(var X=0;X<Y.length;X++){if(Y[X].substring(0,Y[X].indexOf("="))==aa){return L(Y[X].substring((Y[X].indexOf("=")+1)))}}}return""},expressInstallCallback:function(){if(a){var X=c(R);if(X&&l){X.parentNode.replaceChild(l,X);if(Q){w(Q,true);if(M.ie&&M.win){l.style.display="block"}}if(E){E(B)}}a=false}}}}();