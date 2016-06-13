package dkt.student;

public class MyContants {

	/**
	 * 保存文件信息
	 */
	public final static String PREFERENCE_NAME = "zhuper";
	public final static String APPURL = "/apps/manage/Api";
	
	/**
	 * 本地资源
	 * */
	public final static String MAIN_PATH = "/sdcard/Dkt";
	public final static String RESOURCE_PATH = "/sdcard/Dkt/Resource";
	public final static String HOMEWORK_PATH = "/sdcard/Dkt/homework";
	public final static String RESOURCE_HANDWRITING = "/sdcard/Dkt/note";
	public final static String RESOURCE_HANDWRITING_DEFAULT_NAME = "/sdcard/Dkt/note/myNote.db";

	public final static String RESOURCE_DB_NAME = "/sdcard/Dkt/Resource/myResource.db";
	public final static int RESOURCE_DOC = 0;  // 文档
	public final static int RESOURCE_IMG = 1;  // 图片
	public final static int RESOURCE_VIDEO = 2;  // 视频
	public final static int RESOURCE_AUDIO = 3;  // 音频
	
	public final static String NOTE_LIST_LISTVIE = "note_list_listview";
	public final static String NOTE_LIST_GRIDVIEW = "note_list_gridview";
	/**
	 * 网络连接错误 -100 ~ -50
	 */
	// http请求ip为空，设置ip
	public final static int HTTP_URL_NULL_WRONG = -100;
	// http请求连接超时
	public final static int HTTP_CONNECT_TIMOUT_WRONG = -99;
	// http请求网络其他异常
	public final static int HTTP_OTHRE_WRONG = -98;
	// http请求返回值为空
	public final static int HTTP_NULL_WRONG = -97;
	// http请求返回值为空
	public final static int HTTP_RETURE_CODE_WRONG = -96;
	
	public final static String DO_HTTP_TEACH_HOUR_RESOURCES = "do_http_teach_hour_resources";
	/**
	 * 上传下载 -49 ~ -1
	 */
	// 上传中
	public final static int HTTP_UOLOAD = -49;
	// 上传成功
	public final static int HTTP_UOLOAD_SUCESS = -48;
	// 上传成功
	public final static int HTTP_UPLOAD_SUCESS = 200;
	// 上传失败
	public final static int HTTP_UOLOAD_FAIL = -47;

	// 下载中
	public final static int HTTP_DOWNLOAD = -39;
	// 下载成功
	public final static int HTTP_DOWNLOAD_SUCESS = -38;
	// 下载失败
	public final static int HTTP_DOWNLOAD_FAIL = -37;
	// 本地已经存在，不用下载
	public final static int HTTP_NODOWNLOAD_LOCAL_EXISTS = 0;
	// 本地不存在，不用下载，网络有
	public final static int HTTP_NODOWNLOAD_NET_EXISTS = 1;

	// http前缀
	public final static String HTTP_PREFIX = "http://";
	// http斜杠
	public static String HTTP_XIE = "/";
	
	// 等待标志־
	public final static int HTTP_WAITING = 1000;
	
	public final static String DO_HTTP_TEACH_ACTIVITY_DISCUSS_DETAIL = "do_http_pub_activity_talklist";
	public final static String DO_HTTP_TEACH_ACTIVITY_TAIK = "do_http_pub_activity_talk";
	public final static String DO_HTTP_ISERT_PICTURE = "do_http_insert_picture";
	public final static String DO_HTTP_CLASSNOTE = "do_http_classnote";
	public final static String DO_HTTP_TEACH_ACTIVITY_DETAIL = "do_http_activity_detail";
	public final static String DO_HTTP_DYNAMIC_LIST = "do_http_dynamic_list";
	public final static String DO_HTTP_COURSE_LIST = "do_http_course_list";
	public final static String DO_HTTP_COURSE_LESSON = "do_http_course_lesson";
	public final static String DO_HTTP_CLASSHOUR_LIST = "do_http_classhour_lesson";
	public final static String DO_HTTP_TACHE_LIST = "do_http_tache_lesson";
	public final static String DO_HTTP_ACTIVITY_LIST = "do_http_activity_lesson";
	public final static String DO_HTTP_RESOUCE_LIST = "do_http_resouce_lesson";
	public final static String DO_HTTP_ISERT_PACKAGR = "do_http_insert_package";
	public final static String DO_HTTP_TEACH_PACKAGE_LIST = "do_http_insert_package_list";
	public final static String DO_HTTP_TEACH_UPDATE_PACKAGE = "do_http_update_package_list";
	public final static String DO_HTTP_TEACH_PACKAGE_DELETE = "do_http_update_package_delete";
	public final static String DO_HTTP_TEACH_PACKAGE_STEN = "do_http_update_package_sten";
}