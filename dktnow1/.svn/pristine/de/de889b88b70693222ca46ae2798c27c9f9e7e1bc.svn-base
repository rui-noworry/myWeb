package dkt.student.activity;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import org.apache.http.HttpStatus;
import org.json.JSONArray;
import org.json.JSONObject;

import dkt.student.DktForstudentActivity;
import dkt.student.MyApp;
import dkt.student.MyContants;
import dkt.student.R;
import dkt.student.database.ResourceServer;
import dkt.student.model.Resource;
import dkt.student.net.DownLoadFileModefy;
import dkt.student.net.DownLoadHandler;
import dkt.student.net.HttpApacheMapPostThread;
import dkt.student.net.HttpHandler;
import dkt.student.util.FinalFilUtil;
import dkt.student.util.Md5Util;
import dkt.student.util.ViewUtil;
import dkt.student.util.bitmap.FinalBitmap;
import dkt.student.view.popu.classhourActivityPopu;
import dkt.student.view.popu.DiscussPopu;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.drawable.BitmapDrawable;
import android.os.Bundle;
import android.os.Message;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.PopupWindow;
import android.widget.RadioButton;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.AdapterView.OnItemLongClickListener;

public class ClassHourActivity extends Activity{

	private RelativeLayout classHourRelativeLayout;
	private Context context;
	List<HashMap<String, Object>> tacheList = 
		new ArrayList<HashMap<String, Object>>();
	List<HashMap<String, Object>> activityList = 
		new ArrayList<HashMap<String, Object>>();
	List<HashMap<String, Object>> resourceList = 
		new ArrayList<HashMap<String, Object>>();
	private View focusView;
	private int tacheNum = 0;
	private int actionType = 0;
	private int selectedNum = 0;
	
	private int resourceNum = 0;
	private int resourceNums = 0;
	
	private FinalBitmap fb;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		// 设置无标题
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// 设置全屏
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.classhour);

		initView(); // 初始化页面全局控件
        addFun();  // 组装页面点击事件
        
	}
	@Override
	protected void onRestart() {
		// TODO Auto-generated method stub
		super.onRestart();
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.course_back_btn));
	}
	/**
     *  初始化页面全局控件
     * 
     * */
	private void initView() {
		// TODO Auto-generated method stub
		
		context = ClassHourActivity.this;
		focusView = findViewById(R.id.main_focus);
		doDynamicListHttp();
		doTacheListHttp();
		doActivityListHttp();
		doResourceListHttp();//course_title_text
		
		MyApp app = (MyApp) this.getApplication();
		app.setJiePView(findViewById(R.id.course_back_btn));
		
		TextView userNameTextView = (TextView) findViewById(R.id.home_username_text);
		userNameTextView.setText(app.getUserName()); 
		
		TextView titleTextView = (TextView) findViewById(R.id.course_title_text);
		titleTextView.setText(app.getlName()+ "\t" + app.getClName()); 
		
		
		ImageView userImg = (ImageView) findViewById(R.id.home_title_head);
		fb = new FinalBitmap(context).init();
		fb.display(userImg, app.getUserImgUrl());

		
		classHourRelativeLayout = (RelativeLayout) findViewById(R.id.classhour_change_layout);
		
	}
	
	/**
     *  页面点击事件
     * 
     * */
	private void addFun() {
		// TODO Auto-generated method stub
		
		// 返回
		findViewById(R.id.course_back_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				
//				Intent intent  = new Intent(ClassHourActivity.this, CourseActivity.class);
//				intent.setFlags(intent.FLAG_ACTIVITY_CLEAR_TOP);
//				startActivity(intent);
				finish();
			}
		});	
		
		// 环节
//		findViewById(R.id.classhour_choose_btn).setOnClickListener(new OnClickListener() {
//			
//			@Override
//			public void onClick(View v) {
//				// TODO Auto-generated method stub
//				showTachePopu(v);
//			}
//		});
		// 动态
		findViewById(R.id.classhour_dynamic_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				doDynamicListHttp();
			}
		});
		// 作业
		findViewById(R.id.classhour_work_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				addWorkLayout();
			}
		});
		// 资源
		findViewById(R.id.classhour_resource_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				addResourceLayout();
			}
		});
		// 活动
		findViewById(R.id.classhour_activity_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				addActivityLayout();
			}
		});
		// 开始上课
		findViewById(R.id.classhour_start_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				Intent intent  = new Intent(context, BlackboardActivity.class);
				startActivity(intent);
			}
		});
	}
	
	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		// TODO Auto-generated method stub
		return false;
	}
	
	/**
	 * 得到该课时下的动态
	 */
	private void doDynamicListHttp() {
		MyApp app = (MyApp) context.getApplicationContext();
		int userId = app.getUserId();
		String skey = app.getSkey();
		String cId = app.getcId();
		
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Trend.lists");
		map.put("args[a_id]", userId);
		map.put("args[page_size]", 100);
		map.put("args[c_id]", cId);
		map.put("args[page]", 1);
		map.put("skey", skey);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);

		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new ClassHourHandler(context,
				MyContants.DO_HTTP_DYNAMIC_LIST), map);
	}
	
	/**
	 * 得到该课时下的环节
	 */
	private void doTacheListHttp() {
		MyApp app = (MyApp) context.getApplicationContext();
		int userId = app.getUserId();
		String skey = app.getSkey();
		String clId = app.getClId();
		String cId = app.getcId();
		
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Tache.lists");
		map.put("args[a_id]", userId);
		map.put("args[cl_id]", clId);
		map.put("args[c_id]", cId);
		map.put("skey", skey);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);

		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new ClassHourHandler(context,
				MyContants.DO_HTTP_TACHE_LIST), map);
	}
	
	/**
	 * 得到该课时下的所有活动
	 */
	private void doActivityListHttp() {
		MyApp app = (MyApp) context.getApplicationContext();
		int userId = app.getUserId();
		String skey = app.getSkey();
		String clId = app.getClId();
		String cId = app.getcId();
		
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Activity.lists");
		map.put("args[a_id]", userId);
		map.put("args[cl_id]", clId);
		map.put("args[c_id]", cId);
		map.put("skey", skey);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);

		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new ClassHourHandler(context,
				MyContants.DO_HTTP_ACTIVITY_LIST), map);
	}
	
	/**
	 * 得到该课时下的所有资源
	 */
	private void doResourceListHttp() {
		MyApp app = (MyApp) context.getApplicationContext();
		int userId = app.getUserId();
		String skey = app.getSkey();
		String clId = app.getClId();
		String cId = app.getcId();
		
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Resource.listByClasshour");
		map.put("args[a_id]", userId);
		map.put("args[cl_id]", clId);
		map.put("args[c_id]", cId);
		map.put("skey", skey);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);

		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new ClassHourHandler(context,
				MyContants.DO_HTTP_RESOUCE_LIST), map);
	}
	
	/**
	 * 解析动态数据
	 * */
	private void doDynamicSucces(String result) {
		/**
		 * [{"a_nickname":"\u73ed\u4e3b\u4efb","tr_action":"\u6279\u6539",
		 * "tr_obj":"\u4f5c\u4e1a",
		 * "tr_title":"\u5168\u9762\u6d4b\u8bd5\u4f5c\u4e1a",
		 * "tr_created":"2013.07.08 00:07:06",
		 * "a_avatar":"http:\/\/192.168.7.53:81\/AuthAvatar\/48\/default.jpg"}
		 * 
		 * */
		
		try {
			 
			JSONArray jesonArry = new JSONArray(result);
			
			int count = jesonArry.length();
			
			if(0 == count) {
				ViewUtil.myToast(context, "无动态数据");
				return;
			}
			List<HashMap<String, Object>> list = 
				new ArrayList<HashMap<String, Object>>(); 
			for (int i = 0; i < count; i++) {
				JSONObject object = jesonArry.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("a_nickname", object.get("a_nickname").toString());
				map.put("tr_created", object.get("tr_created").toString());
				map.put("a_avatar", object.get("a_avatar").toString());
				map.put("tr_title", object.get("tr_title").toString());
				map.put("tr_obj", object.get("tr_obj").toString());
				map.put("tr_action", object.get("tr_action").toString());
				list.add(map);
			}
			addDynamicLayout(list);
//			System.out.println(resourceList.get(3).get("ar_title").toString()+resourceList.size()+"==================="+count);
		} catch (Exception e) {
			// TODO: handle exception
		}
	}
	
	/**
	 * 解析活动数据
	 * */
	private void doActivitySucces(String result) {
		/**
		 * {"status":1,"info":{"list":[
		 * {"act_id":"358","ta_id":"186","act_title":"sdfsdfsdfsdfsdfsdfdsf",
		 * "c_id":",4,","cro_id":"","act_rel":"229,230,231","act_type":"1",
		 * "act_sort":"0","act_is_published":1,"ap_id":"202",
		 * "ad_status":"2","ad_persent":"0"},
		 * 
		 * {"act_id":"360","ta_id":"186","act_title":"\u897f\u6e38\u8bb0",
		 * "c_id":",4,","cro_id":"","act_rel":"232,233,234,235,236",
		 * "act_type":"1","act_sort":"0","act_is_published":1,"ap_id":"203",
		 * "ad_status":"4","ad_persent":"90"},
		 * 
		 * {"act_id":"364","ta_id":"186",
		 * "act_title":"\u8bfe\u5802\u7ec3\u4e60","c_id":",4,","cro_id":"",
		 * "act_rel":"237","act_type":"2","act_sort":"0",
		 * "act_is_published":1,"ap_id":"204"},
		 * 
		 * {"act_id":"365","ta_id":"186","act_title":"\u8bfe\u5802\u7ec3\u4e60","c_id":",4,","cro_id":"","act_rel":"","act_type":"3","act_sort":"0","act_is_published":1,"ap_id":"205"},{"act_id":"366","ta_id":"186","act_title":"\u8bfe\u5802\u7ec3\u4e60\u8bfe\u5802\u7ec3\u4e60","c_id":",4,","cro_id":"","act_rel":"13","act_type":"4","act_sort":"0","act_is_published":1,"ap_id":"206"},{"act_id":"367","ta_id":"186","act_title":"\u8bfe\u5802\u7ec3\u4e60","c_id":",4,","cro_id":"","act_rel":"195","act_type":"5","act_sort":"0","act_is_published":1,"ap_id":"207"},{"act_id":"368","ta_id":"186","act_title":"dfsffsdf","c_id":",4,","cro_id":"","act_rel":"","act_type":"6","act_sort":"0","act_is_published":1,"ap_id":"208"},{"act_id":"369","ta_id":"186","act_title":"webbbb","c_id":",4,","cro_id":",","act_rel":"","act_type":"3","act_sort":"0","act_is_published":1,"ap_id":"209"},{"act_id":"372","ta_id":"186","act_title":"\u6d4b\u8bd5\u53d1\u5e03","c_id":",4,","cro_id":"","act_rel":"239","act_type":"1","act_sort":"0","act_is_published":1,"ap_id":"210","ad_status":"4","ad_persent":"85"},{"act_id":"373","ta_id":"186","act_title":"\u4f5c\u4e1a\u554a","c_id":",4,","cro_id":"","act_rel":"240","act_type":"1","act_sort":"0","act_is_published":1,"ap_id":"211","ad_status":0,"ad_persent":0},{"act_id":"383","ta_id":"186","act_title":"dddd","c_id":",4,","cro_id":"","act_rel":"245","act_type":"1","act_sort":"0","act_is_published":1,"ap_id":"217","ad_status":"4","ad_persent":"45"},{"act_id":"384","ta_id":"191","act_title":"\u4f5c\u4e1a\u6d4b\u8bd5\u7edf\u8ba1\u56fe","c_id":",4,","cro_id":"","act_rel":"246","act_type":"1","act_sort":"0","act_is_published":1,"ap_id":"218","ad_status":"1","ad_persent":"0"},{"act_id":"385","ta_id":"191","act_title":"\u4f5c\u4e1a\u4e3b\u89c2\u9898","c_id":",4,","cro_id":"","act_rel":"247","act_type":"1","act_sort":"0","act_is_published":1,"ap_id":"219","ad_status":"1","ad_persent":"0"},{"act_id":"386","ta_id":"191","act_title":"\u7b80\u7b54\u9898\u6d4b\u8bd5\u63d0\u4ea4","c_id":",4,","cro_id":"","act_rel":"248,249","act_type":"1","act_sort":"0","act_is_published":1,"ap_id":"220","ad_status":"1","ad_persent":"0"}]}}
		 * */
		try {
			 
			JSONObject jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				ViewUtil.myToast(context, "无活动数据");
				return;
			}
			String courseListString = jsonObject.get("info").toString();
			JSONObject jsonObject1 = new JSONObject(courseListString);
			JSONArray jesonArry = new JSONArray(jsonObject1.get("list").toString());
			
			int count = jesonArry.length();
			System.out.println("==================="+count);
			
			for (int i = 0; i < count; i++) {
				JSONObject object = jesonArry.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("biaoti", object.get("act_title").toString());
				map.put("act_id", object.get("act_id").toString());
				map.put("ta_id", object.get("ta_id").toString());
				map.put("act_type", object.get("act_type").toString());
				map.put("ap_id", object.get("ap_id").toString());
				if(!object.isNull("ad_status")) {
					map.put("ad_status", object.get("ad_status").toString());
				}
				if(!object.isNull("ad_persent")) {
					map.put("ad_persent", object.get("ad_persent").toString());
				}
				map.put("act_is_published", object.get("act_is_published").toString());
				activityList.add(map);
			}
			MyApp app = (MyApp) context.getApplicationContext();
			app.setActivityList(activityList);
		} catch (Exception e) {
			// TODO: handle exception
		}
	}
	
	/**
	 * 解析资源数据
	 * */
	private void doRecourceSucces(String result) {
		/**
		 * [{"ar_id":"20",
		 * "ar_image":"http:\/\/192.168.7.53:81\/AuthResource\/transform\/image\/201307\/100\/51d955ca0ed92.png",
		 * "ar_title":"\u8bfe\u7a0b\u80cc\u666f",
		 * "m_id":"1",
		 * "ar_savename":"http:\/\/192.168.7.53:81\/AuthResource\/transform\/image\/201307\/600\/51d955ca0ed92.png"},
		 * 
		 * */
		
		try {
			 
			JSONArray jesonArry = new JSONArray(result);
			
			int count = jesonArry.length();
			
			if(0 == count) {
				ViewUtil.myToast(context, "无资源数据");
				return;
			}
			for (int i = 0; i < count; i++) {
				JSONObject object = jesonArry.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("ar_title", object.get("ar_title").toString());
				map.put("ar_id", object.get("ar_id").toString());
				map.put("ar_image", object.get("ar_image").toString());
				map.put("ar_savename", object.get("ar_savename").toString());
				map.put("m_id", object.get("m_id").toString());
				resourceList.add(map);  
			}
			MyApp app = (MyApp) context.getApplicationContext();
			app.setResourceList(resourceList);
			ResourceServer resourceServer = new ResourceServer();
			resourceNums = resourceList.size();
			for(int i = 0;i<resourceNums;i++) {
				Resource myResource = new Resource();
				myResource = resourceServer.getMyNetData(Integer.parseInt(resourceList.get(i).get("ar_id").toString()));
				
				String fileUrl = resourceList.get(i).get("ar_savename").toString();
				String fileType = fileUrl.substring(fileUrl.lastIndexOf(".") + 1);
				
				String fileName = resourceList.get(i).get("ar_title").toString()+"."+fileType;
				if(myResource == null) {
					System.out.println("======================"); 
					DownLoadFile(fileUrl, fileName, i);
				}else{
					changeNumText();
				}
	
			}
//			System.out.println(resourceList.get(3).get("ar_title").toString()+resourceList.size()+"==================="+count);
		} catch (Exception e) {
			// TODO: handle exception
		}
	}
	
	/**
	 * 解析环节数据
	 * */
	private void doTacheSucces(String result) {
		/**
		 * {"status":1,"info":{"list":[
		 * {"ta_id":"1","a_id":"2","co_id":"1","l_id":"2","cl_id":"1",
		 * "ta_title":"\u8bfe\u524d","act_id":"1,2,3,4,5,10,11,12,13,14","ta_sort":"0"},
		 * {"ta_id":"2","a_id":"2","co_id":"1","l_id":"2","cl_id":"1",
		 * "ta_title":"\u8bfe\u5802","act_id":"7,8,9,15,16","ta_sort":"0"},
		 * {"ta_id":"3","a_id":"2","co_id":"1","l_id":"2","cl_id":"1",
		 * "ta_title":"\u8bfe\u540e","act_id":null,"ta_sort":"0"},
		 * {"ta_id":"4","a_id":"2","co_id":"1","l_id":"2","cl_id":"1",
		 * "ta_title":"\u56de\u53bb\u9884\u4e60","act_id":null,"ta_sort":"0"},
		 * {"ta_id":"5","a_id":"2","co_id":"1","l_id":"2","cl_id":"1",
		 * "ta_title":"\u73af\u82825","act_id":null,"ta_sort":"0"},
		 * {"ta_id":"6","a_id":"2","co_id":"1","l_id":"2","cl_id":"1",
		 * "ta_title":"\u73af\u82826","act_id":null,"ta_sort":"0"},
		 * {"ta_id":"7","a_id":"2","co_id":"1","l_id":"2","cl_id":"1",
		 * "ta_title":"\u73af\u82827","act_id":null,"ta_sort":"0"},
		 * {"ta_id":"8","a_id":"2","co_id":"1","l_id":"2","cl_id":"1",
		 * "ta_title":"\u73af\u82828","act_id":null,"ta_sort":"0"}]}}
		 * 
		 * {"status":1,"info":{"list":[
		 * {"ta_id":"98","a_id":"2","co_id":"1","l_id":"157","cl_id":"40",
		 * "ta_title":"\u8bfe\u524d","act_id":"172,173,174,175,176,177","ta_sort":"0"},
		 * {"ta_id":"99","a_id":"2","co_id":"1","l_id":"157","cl_id":"40",
		 * "ta_title":"\u8bfe\u5802","act_id":"166,167,168,169,170","ta_sort":"0"},
		 * {"ta_id":"100","a_id":"2","co_id":"1","l_id":"157","cl_id":"40",
		 * "ta_title":"\u8bfe\u540e","act_id":"178","ta_sort":"0"},
		 * {"ta_id":"104","a_id":"2","co_id":"1","l_id":"157","cl_id":"40","ta_title":"\u73af\u8282\u73af\u8282\u73af\u8282\u73af\u8282","act_id":"179","ta_sort":"0"},{"ta_id":"105","a_id":"2","co_id":"1","l_id":"157","cl_id":"40","ta_title":"\u73af\u82825","act_id":"180","ta_sort":"0"},{"ta_id":"106","a_id":"2","co_id":"1","l_id":"157","cl_id":"40","ta_title":"\u73af\u82826","act_id":null,"ta_sort":"0"},{"ta_id":"107","a_id":"2","co_id":"1","l_id":"157","cl_id":"40","ta_title":"\u73af\u82827","act_id":null,"ta_sort":"0"},{"ta_id":"108","a_id":"2","co_id":"1","l_id":"157","cl_id":"40","ta_title":"\u73af\u82828","act_id":null,"ta_sort":"0"},{"ta_id":"109","a_id":"2","co_id":"1","l_id":"157","cl_id":"40","ta_title":"\u73af\u82829","act_id":null,"ta_sort":"0"},{"ta_id":"110","a_id":"2","co_id":"1","l_id":"157","cl_id":"40","ta_title":"\u73af\u828210","act_id":"181","ta_sort":"0"}]}}

		 * 
		 * */
		
		try {
			 
			JSONObject jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				ViewUtil.myToast(context, "无环节数据");
				return;
			}
			String courseListString = jsonObject.get("info").toString();
			JSONObject jsonObject1 = new JSONObject(courseListString);
			JSONArray jesonArry = new JSONArray(jsonObject1.get("list").toString());
			
			int count = jesonArry.length();
			System.out.println("==================="+count);
			
			// 全部环节内容
			HashMap<String, Object> map1 = new HashMap<String, Object>();
			map1.put("ta_title", "全部");
			map1.put("ta_id", "0");
			tacheList.add(map1);
			
			// 单独环节
			for (int i = 0; i < count; i++) {
				JSONObject object = jesonArry.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("ta_title", object.get("ta_title").toString());
				map.put("ta_id", object.get("ta_id").toString());
				map.put("act_id", object.get("act_id").toString());
				tacheList.add(map);
			}
			
		} catch (Exception e) {
			// TODO: handle exception
		}
	}
	
	/**
	 * 下载文件
	 * 
	 */
	private void DownLoadFile(String downUrl, String saveName, int num) {
		
		System.out.println(downUrl+"===="+saveName);
		new DownLoadFileModefy(downUrl, new DownHandler(context,
				"0"), saveName, num);
	}
	
	private void changeNumText(){
//		if(resourceNum == resourceNums){
//			return;
//		} 
		resourceNum = resourceNum + 1;
		TextView myResourceNumTextView = (TextView) findViewById(R.id.classhour_resource_num);
		myResourceNumTextView.setText("资源已下载\t"+resourceNum+"/"+resourceNums);
	}
	/**
	 * 下载文件的控制类
	 * 
	 * @author Administrator
	 * 
	 */
	class DownHandler extends DownLoadHandler {
		String tag;

		public DownHandler(Context context, String tag) {
			super(context, tag);
		}

		@Override
		public void handleMessage(Message msg) {
			// TODO Auto-generated method stub
			super.handleMessage(msg);

			if (msg.what == MyContants.HTTP_DOWNLOAD) {
				// 正在下载
			} else if (msg.what == MyContants.HTTP_DOWNLOAD_SUCESS) {
				// 下载成功
				System.out.println("下载成功ssssssssssssssssssss"+msg.obj);
				
				long currentTimestamp = System.currentTimeMillis();
            	int muNum = Integer.parseInt(""+msg.obj);
            	Resource resource = new Resource();
    			resource.setResourceName(resourceList.get(muNum).get("ar_title").toString());
    			
    			if(resourceList.get(muNum).get("m_id").toString().equals("1")){
    				resource.setResourceType(1);
    			}else if(resourceList.get(muNum).get("m_id").toString().equals("2")){
    				resource.setResourceType(2);
    			}else if(resourceList.get(muNum).get("m_id").toString().equals("3")){
    				resource.setResourceType(3);
    			}else if(resourceList.get(muNum).get("m_id").toString().equals("4")){
    				resource.setResourceType(0);
    			}
    			
    			String fileUrl = resourceList.get(muNum).get("ar_savename").toString();
				String fileType = fileUrl.substring(fileUrl.lastIndexOf(".") + 1);
    			String fileName = resourceList.get(muNum).get("ar_title").toString()+"."+fileType;
    			
    			resource.setResourcePath("/sdcard/Dkt/Resource/"+fileName);
    			resource.setResourceCreatTime(currentTimestamp);
    			resource.setNetId(Integer.parseInt(resourceList.get(muNum).get("ar_id").toString()));
    			ResourceServer resourceServer = new ResourceServer();
    			resourceServer.insertResource(resource);
    			changeNumText();
			} else {
				// 下载失败
				
			}

		}

	}
	
	class ClassHourHandler extends HttpHandler {
		String tag;

		public ClassHourHandler(Context context, String tag) {
			super(context, tag);
			// TODO Auto-generated constructor stub
			this.tag = tag;
		}

		@Override
		public void handleMessage(Message msg) {
			super.handleMessage(msg);

			if (msg.what == HttpStatus.SC_OK) {
				System.out.println("______返回值开始_____________");
				System.out.println(msg.obj);
				System.out.println("________返回值结束____________");
				if(tag == MyContants.DO_HTTP_TACHE_LIST) {
					doTacheSucces((String) msg.obj);
				}
				else if(tag == MyContants.DO_HTTP_ACTIVITY_LIST) {
					doActivitySucces((String) msg.obj);
				}
				else if(tag == MyContants.DO_HTTP_RESOUCE_LIST) {
					doRecourceSucces((String) msg.obj);
				}
				else if(tag == MyContants.DO_HTTP_DYNAMIC_LIST) {
					doDynamicSucces((String) msg.obj);
				}
			}

		}

	}
	
	/**
	 * 添加动态区域
	 * 
	 * */
	private void addDynamicLayout(List<HashMap<String, Object>> list) {
		
		classHourRelativeLayout.removeAllViews();
		RadioButton dynamicRadio = (RadioButton)findViewById(R.id.classhour_dynamic_btn);
		dynamicRadio.setChecked(true);
		
		actionType = 4;
		RelativeLayout.LayoutParams dynamicRela = new RelativeLayout.LayoutParams(
				RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
		View view = LayoutInflater.from(this).inflate(
				R.layout.classhour_dynamic, null);
		ListView dynamicListView = (ListView) view.findViewById(R.id.classhour_dynamic_listview);
//		dynamicListView.setDividerHeight(20);
		
		classhourAdapter adapter = new classhourAdapter(context, list, 0, 0);
		
		dynamicListView.setAdapter(adapter);
		classHourRelativeLayout.addView(view, dynamicRela);
		
	}
	
	/**
	 * 添加作业区域
	 * 
	 * */
	private void addWorkLayout() {
		
		classHourRelativeLayout.removeAllViews();
		
		actionType = 1;
		RelativeLayout.LayoutParams workLRela = new RelativeLayout.LayoutParams(
				RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
		View view = LayoutInflater.from(this).inflate(
				R.layout.classhour_ac, null);
		
		LinearLayout myLayout = (LinearLayout) view.findViewById(R.id.classhour_ac_linear);
		

		for(int i=0;i<tacheList.size();i++) {
		
			if(tacheList.get(i).get("act_id") != null && !tacheList.get(i).get("act_id").toString().equals("null")) {
				String ta_id = tacheList.get(i).get("ta_id").toString();
				String ta_title = tacheList.get(i).get("ta_title").toString();
				List<HashMap<String, Object>> list = 
					new ArrayList<HashMap<String, Object>>();
				for(int j=0;j<activityList.size();j++) {
					if(activityList.get(j).get("ta_id").equals(ta_id) &&
							activityList.get(j).get("act_type").equals("1")) {
						list.add(activityList.get(j));
					}
				}
				System.out.println("==========================="+activityList.size());
				
				if(list.size() > 0) {
					View view1 = LayoutInflater.from(this).inflate(
							R.layout.classhour_ac_item, null);
					TextView myTextView = (TextView) view1.findViewById(R.id.classhour_ac_text);
					myTextView.setText(ta_title);
					
					GridView myGridView = (GridView) view1.findViewById(R.id.ac_gridview);
					RelativeLayout.LayoutParams linearParams = (RelativeLayout.LayoutParams) myGridView.getLayoutParams(); 
					
					int count  = list.size();
					int a = count % 5;
					int b = 0;
					if(a > 0) {
						b = count / 5 + 1;
					}else{
						b = count / 5;
					}
					int hei = 170 * b;
					linearParams.height = hei;
					myGridView.setLayoutParams(linearParams);
					
//					final List<HashMap<String, Object>> list1 = list;
//					myGridView.setOnItemClickListener(new OnItemClickListener() {
//			
//						@Override
//						public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
//								long arg3) {
//							// TODO Auto-generated method stub
////							classhourActivityPopu myActivityPopu = new classhourActivityPopu(context, focusView,
////									list1.get(arg2).get("act_id").toString(),
////									list1.get(arg2).get("act_type").toString());
////							myActivityPopu.showPopup();
//							Intent intent  = new Intent(context, DoHomeWorkActivity.class);
//							intent.putExtra("act_id", list1.get(arg2).get("act_id").toString());//ap_id
//							intent.putExtra("ap_id", list1.get(arg2).get("ap_id").toString());
//							intent.putExtra("biaoti", list1.get(arg2).get("biaoti").toString());
//							startActivity(intent);
//						}
//					});
					classhourAdapter adapter = new classhourAdapter(context, list, 1, 0);
					myGridView.setAdapter(adapter);
					myLayout.addView(view1);
				}
				
			}
			
		}
		
//		GridView workListView = (GridView) view.findViewById(R.id.work_gridview);
//		List<HashMap<String, Object>> list = 
//			new ArrayList<HashMap<String, Object>>(); // 动态数据
//		
//		if(0 == tacheNum) {
//			
//			for(int i=0;i<activityList.size();i++) {
//				if(activityList.get(i).get("act_type").equals("1")) {
//					list.add(activityList.get(i));
//				}
//			}
//		}else{
//			for(int i=0;i<activityList.size();i++) {
//				if(activityList.get(i).get("act_type").equals("1")&&
//						activityList.get(i).get("ta_id").equals(tacheList.get(tacheNum).get("ta_id").toString())) {
//					list.add(activityList.get(i));
//				}
//			}
//		}
//		final List<HashMap<String, Object>> list1 = list;
//		workListView.setOnItemClickListener(new OnItemClickListener() {
//
//			@Override
//			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
//					long arg3) {
//				// TODO Auto-generated method stub
////				classhourActivityPopu myActivityPopu = new classhourActivityPopu(context, focusView,
////						list1.get(arg2).get("act_id").toString(),
////						list1.get(arg2).get("act_type").toString()); //biaoti
////				myActivityPopu.showPopup();
//				
//				Intent intent  = new Intent(context, DoHomeWorkActivity.class);
//				intent.putExtra("act_id", list1.get(arg2).get("act_id").toString());
//				intent.putExtra("biaoti", list1.get(arg2).get("biaoti").toString());
//				startActivity(intent);
//			}
//		});
//		classhourAdapter adapter = new classhourAdapter(context, list, 1, 0);
//		
//		workListView.setAdapter(adapter);
		classHourRelativeLayout.addView(view, workLRela);
	}
	
	/**
	 * 添加资源区域
	 * 
	 * */
	private void addResourceLayout() {
		
		classHourRelativeLayout.removeAllViews();
		
		actionType = 3;
		RelativeLayout.LayoutParams workLRela = new RelativeLayout.LayoutParams(
				RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
		View view = LayoutInflater.from(this).inflate(
				R.layout.classhour_work, null);
		
		GridView workListView = (GridView) view.findViewById(R.id.work_gridview);
		workListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				ResourceServer resourceServer = new ResourceServer();
				Resource myResource = new Resource();
				myResource = resourceServer.getMyNetData(Integer.parseInt(resourceList.get(arg2).get("ar_id").toString()));
				if(myResource == null) {
					System.out.println(resourceList.get(arg2).get("ar_savename").toString());
					ViewUtil.myToast(context, "该资源正在下载中，请稍候。。。");
				}else{
					String filePath = myResource.getResourcePath();
					String fileName = filePath.substring(filePath.lastIndexOf(".") + 1);
					File f = new File(filePath);
					if(!f.exists()) {
						Toast.makeText(context, getString(R.string.file_not_find), Toast.LENGTH_SHORT).show();
						resourceServer.delete(myResource.getResourceId());
					}else{
						ViewUtil.openResource(fileName, filePath, context);
					}
				}
			}
		});
		classhourAdapter adapter = new classhourAdapter(context, resourceList, 4, 0);
		
		workListView.setAdapter(adapter);
		classHourRelativeLayout.addView(view, workLRela);
	}
	
	/**
	 * 添加活动区域
	 * 
	 * */
	private void addActivityLayout() {
		
		classHourRelativeLayout.removeAllViews();
		
		actionType = 2;
		RelativeLayout.LayoutParams workLRela = new RelativeLayout.LayoutParams(
				RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
		View view = LayoutInflater.from(this).inflate(
				R.layout.classhour_ac, null);
		
		LinearLayout myLayout = (LinearLayout) view.findViewById(R.id.classhour_ac_linear);
		
		for(int i=0;i<tacheList.size();i++) {
		
			if(tacheList.get(i).get("act_id") != null && !tacheList.get(i).get("act_id").toString().equals("null")) {
				String ta_id = tacheList.get(i).get("ta_id").toString();
				String ta_title = tacheList.get(i).get("ta_title").toString();
				List<HashMap<String, Object>> list = 
					new ArrayList<HashMap<String, Object>>();
				for(int j=0;j<activityList.size();j++) {
					if(activityList.get(j).get("ta_id").equals(ta_id)) {
						list.add(activityList.get(j));
					}
				}
				if(list.size() > 0) {
					View view1 = LayoutInflater.from(this).inflate(
							R.layout.classhour_ac_item, null);
					TextView myTextView = (TextView) view1.findViewById(R.id.classhour_ac_text);
					myTextView.setText(ta_title);
					
					GridView myGridView = (GridView) view1.findViewById(R.id.ac_gridview);
					RelativeLayout.LayoutParams linearParams = (RelativeLayout.LayoutParams) myGridView.getLayoutParams(); 
					
					int count  = list.size();
					int a = count % 5;
					int b = 0;
					if(a > 0) {
						b = count / 5 + 1;
					}else{
						b = count / 5;
					}
					int hei = 170 * b;
					linearParams.height = hei;
					myGridView.setLayoutParams(linearParams);
					
					final List<HashMap<String, Object>> list1 = list;
					myGridView.setOnItemClickListener(new OnItemClickListener() {
			
						@Override
						public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
								long arg3) {
							// TODO Auto-generated method stub
							classhourActivityPopu myActivityPopu = new classhourActivityPopu(context, focusView,
									list1.get(arg2).get("act_id").toString(),
									list1.get(arg2).get("act_type").toString());
							myActivityPopu.showPopup();
						}
					});
					
					myGridView.setOnItemLongClickListener(new OnItemLongClickListener() {

						@Override
						public boolean onItemLongClick(AdapterView<?> arg0,
								View arg1, int arg2, long arg3) {
							// TODO Auto-generated method stub
							if(list1.get(arg2).get("act_type").toString().equals("2")) {
								
								
								Intent intent1 = new Intent("exerciseactivity");
				                intent1.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
				    			intent1.putExtra("ap_id", list1.get(arg2).get("ap_id").toString());
				    			startActivity(intent1);
								
							}
							return true;
						}
					});
					classhourAdapter adapter = new classhourAdapter(context, list, 3, 0);
					myGridView.setAdapter(adapter);
					myLayout.addView(view1);
				}
				
			}
			
		}
		
//		GridView workListView = (GridView) view.findViewById(R.id.work_gridview);
//		List<HashMap<String, Object>> list = 
//			new ArrayList<HashMap<String, Object>>(); // 动态数据
//		if(0 == tacheNum) {
//			
//			for(int i=0;i<activityList.size();i++) {
//				
//				list.add(activityList.get(i));
//				
//			}
//		}else{
//			for(int i=0;i<activityList.size();i++) {
//				if(activityList.get(i).get("ta_id").equals(tacheList.get(tacheNum).get("ta_id").toString())) {
//					list.add(activityList.get(i));
//				}
//			}
//		}
////		
//		final List<HashMap<String, Object>> list1 = list;
//		workListView.setOnItemClickListener(new OnItemClickListener() {
//
//			@Override
//			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
//					long arg3) {
//				// TODO Auto-generated method stub
//				classhourActivityPopu myActivityPopu = new classhourActivityPopu(context, focusView,
//						list1.get(arg2).get("act_id").toString(),
//						list1.get(arg2).get("act_type").toString());
//				myActivityPopu.showPopup();
//			}
//		});
//		classhourAdapter adapter = new classhourAdapter(context, list, 3, 0);		
//		workListView.setAdapter(adapter);
		
		classHourRelativeLayout.addView(view, workLRela);
	}
	
	private void showTachePopu(View view) {
		View view1 = LayoutInflater.from(context).inflate(
				R.layout.classhour_tache_popu, null);
		PopupWindow popup = new PopupWindow(view1, LayoutParams.WRAP_CONTENT,
				LayoutParams.WRAP_CONTENT);
		popup.setBackgroundDrawable(new BitmapDrawable());
		popup.setTouchable(true);
		popup.setFocusable(true);
		ListView tacheListView = (ListView) view1.
				findViewById(R.id.tache_listview);
		tacheListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				if(1 == actionType) {
					tacheNum = arg2;
					addWorkLayout();
				}else if(2 == actionType) {
					tacheNum = arg2;
					addActivityLayout();
				}
			}
			
		});
		classhourAdapter tacheAdapter = new classhourAdapter(context,
				tacheList, 2, 0);
		tacheListView.setAdapter(tacheAdapter);
		
		popup.showAsDropDown(view);
	}
	
	class classhourAdapter extends BaseAdapter {
		List<HashMap<String, Object>> list;
		Context context;
		int tag;
		Holder holder;
		int i;
		FinalBitmap fb;

		public classhourAdapter(Context context,
				List<HashMap<String, Object>> list, int tag, int i) {
			this.list = list;
			this.context = context;
			this.tag = tag;
			this.i = i;
			fb = new FinalBitmap(context).init();
		}

		@Override
		public int getCount() {
			// TODO Auto-generated method stub
			return list.size();
		}

		@Override
		public Object getItem(int position) {
			// TODO Auto-generated method stub
			return null;
		}

		@Override
		public long getItemId(int position) {
			// TODO Auto-generated method stub
			return 0;
		}

		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			// TODO Auto-generated method stub
			View view = null;
			if(0 == tag) {
				view = addClassDynamicView(position, convertView, parent);
			}else if(1 ==  tag) {
				view = addClassWorkView(position, convertView, parent);
			}else if(2 ==  tag) {
				view = addTacheView(position, convertView, parent);
			}else if(3 == tag) {
				view = addActivityView(position, convertView, parent);
			}else if(4 == tag) {
				view = addResourceView(position, convertView, parent);
			}

			return view;
		}
		
		// 刷新适配器
		public void refresh(List<HashMap<String, Object>> list, int i) {
			this.list = list;
			this.i = i;
			this.notifyDataSetChanged();
		}
		/**
		 * 
		 * map.put("a_nickname", object.get("a_nickname").toString());
				map.put("tr_created", object.get("tr_created").toString());
				map.put("a_avatar", object.get("a_avatar").toString());
				map.put("tr_title", object.get("tr_title").toString());
				map.put("tr_obj", object.get("tr_obj").toString());
				map.put("tr_action", object.get("tr_action").toString());*/
		// 生成动态列表
		private View addClassDynamicView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.classhour_dynamic_listview_item, null);
				holder.img = (ImageView) convertView
						.findViewById(R.id.classhour_dynamic_img);
				holder.name = (TextView) convertView
						.findViewById(R.id.classhour_dynamic_name);
				holder.msg = (TextView) convertView
						.findViewById(R.id.classhour_dynamic_msg);	
				holder.time = (TextView) convertView
						.findViewById(R.id.classhour_dynamic_time);	
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			String imageUrl = list.get(position).get("a_avatar").toString();
			fb.display(holder.img, imageUrl);
			
			holder.name.setText(list.get(position).get("a_nickname").toString());
			holder.msg.setText(list.get(position).get("tr_action").toString()+"了\t\t"
					+list.get(position).get("tr_obj").toString()+"-"
					+list.get(position).get("tr_title").toString());
			holder.time.setText(list.get(position).get("tr_created").toString());
			return convertView;

		}
		
		// 生成作业列表
		private View addClassWorkView(final int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.classhour_work_listview_item, null);
				holder.acRelativeLayout = (RelativeLayout) convertView
						.findViewById(R.id.work_re);
				holder.biaoti = (TextView) convertView
						.findViewById(R.id.work_title);
				holder.banji = (TextView) convertView
						.findViewById(R.id.classname);
				holder.workCheck = (RadioButton) convertView
						.findViewById(R.id.work_check_btn);
				holder.workCorrect = (RadioButton) convertView
						.findViewById(R.id.work_do_btn);
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			
			String biaotiString = list.get(position).get("biaoti").toString();
			if(biaotiString.length() > 9) {
				biaotiString = biaotiString.substring(0, 9) + "...";
			}
			holder.biaoti.setText(biaotiString);
			
			if(list.get(position).get("ad_status").toString().equals("0")) {
				holder.banji.setText("状态:\t未完成");
				holder.workCorrect.setText("做作业");
				holder.workCorrect.setCompoundDrawablesWithIntrinsicBounds(null, 
						getResources().getDrawable(R.drawable.student_do), null, null);

			}else if(list.get(position).get("ad_status").toString().equals("1")){
				
				holder.banji.setText("状态:\t已提交");
				
			}else if(list.get(position).get("ad_status").toString().equals("2")){
				
				holder.banji.setText("状态:\t重做");
				holder.workCorrect.setText("重做");
				holder.workCorrect.setCompoundDrawablesWithIntrinsicBounds(null, 
						getResources().getDrawable(R.drawable.student_doagin), null, null);

			}else if(list.get(position).get("ad_status").toString().equals("3")){
				
				holder.banji.setText("状态:\t已重做");
				
			}else if(list.get(position).get("ad_status").toString().equals("4")){
				
				holder.banji.setText("状态:\t"+list.get(position).get("ad_persent").toString()+"分");
				
			}
			MyApp app = (MyApp) getApplication();
			holder.workCheck.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					classhourActivityPopu myActivityPopu = new classhourActivityPopu(context, focusView,
							list.get(position).get("act_id").toString(),
							list.get(position).get("act_type").toString());
					myActivityPopu.showPopup();
				}
			});
			holder.workCorrect.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					Intent intent  = new Intent(context, DoHomeWorkActivity.class);
					intent.putExtra("act_id", list.get(position).get("act_id").toString());//ap_id
					intent.putExtra("ap_id", list.get(position).get("ap_id").toString());
					intent.putExtra("biaoti", list.get(position).get("biaoti").toString());
					startActivity(intent);
				}
			});
//			holder.banji.setText(app.getcName());
			
			return convertView;

		}
		
		// 生成资源列表
		private View addResourceView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.classhour_resource_listview_item, null);
				holder.acRelativeLayout = (RelativeLayout) convertView
						.findViewById(R.id.activity_re);
				holder.resourceRe = (RelativeLayout) convertView
						.findViewById(R.id.activity_img_re);
				holder.biaoti = (TextView) convertView
						.findViewById(R.id.activity_name);//activity_img_re
				holder.img = (ImageView) convertView
						.findViewById(R.id.activity_img);
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			holder.biaoti.setTextColor(context.getResources().getColor(R.color.green));
			holder.acRelativeLayout.setBackgroundResource(R.drawable.activity_nopub);
			
			String biaotiString = list.get(position).get("ar_title").toString();
			if(biaotiString.length() > 9) {
				biaotiString = biaotiString.substring(0, 9) + "...";
			}
			holder.biaoti.setText(biaotiString);
			
//			if(list.get(position).get("m_id").toString().equals("1")) {
//				holder.img.setBackgroundResource(R.drawable.resource_icon_image_bg);
//			}else if(list.get(position).get("m_id").toString().equals("2")){
//				holder.img.setBackgroundResource(R.drawable.resource_icon_shipin_bg);
//
//			}else if(list.get(position).get("m_id").toString().equals("3")){
//				holder.img.setBackgroundResource(R.drawable.rsource_icon_yinpin_bg);
//
//			}else if(list.get(position).get("m_id").toString().equals("4")){
//				holder.img.setBackgroundResource(R.drawable.resource_icon_doc_bg);
//
//			}
			fb.display(holder.img, list.get(position).get("ar_image").toString());

			return convertView;

		}
		
		// 生成活动列表
		private View addActivityView(final int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.classhour_activity_listview_item, null);
				holder.acRelativeLayout = (RelativeLayout) convertView
						.findViewById(R.id.activity_re);
				holder.biaoti = (TextView) convertView
						.findViewById(R.id.activity_name);
				holder.img = (ImageView) convertView
						.findViewById(R.id.activity_img);
				
				holder.workCheck = (RadioButton) convertView
						.findViewById(R.id.work_check_btn);
				holder.workCorrect = (RadioButton) convertView
						.findViewById(R.id.work_do_btn);
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			if(list.get(position).get("act_is_published").toString().equals("1")) {
				
			}else{
				
			}
			String biaotiString = list.get(position).get("biaoti").toString();
			if(biaotiString.length() > 9) {
				biaotiString = biaotiString.substring(0, 9) + "...";
			}
			holder.biaoti.setText(biaotiString);
			if(list.get(position).get("act_type").equals("1")) {
				holder.img.setBackgroundResource(R.drawable.classhour_homework);

			}
			else if(list.get(position).get("act_type").equals("2")) {
				holder.img.setBackgroundResource(R.drawable.classhour_practice);
				
			}else if(list.get(position).get("act_type").equals("3")){
				holder.img.setBackgroundResource(R.drawable.classhour_text);

			}else if(list.get(position).get("act_type").equals("4")){
				holder.img.setBackgroundResource(R.drawable.classhour_link);

			}else if(list.get(position).get("act_type").equals("5")){
				holder.img.setBackgroundResource(R.drawable.classhour_read);
			}else if(list.get(position).get("act_type").equals("6")){
				holder.img.setBackgroundResource(R.drawable.classhour_discuss_bg1);
			}
			
			if(list.get(position).get("act_type").equals("2")) {
				holder.workCorrect.setVisibility(View.VISIBLE);
			}else{
				holder.workCorrect.setVisibility(View.GONE);
			}
			
			holder.workCorrect.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					Intent intent  = new Intent(context, DoExerciseActivity.class);
					intent.putExtra("ap_id", list.get(position).get("ap_id").toString());
					startActivity(intent);
				}
			});
			holder.workCheck.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					if(list.get(position).get("act_type").equals("6")) {
						//ap_id
						DiscussPopu myDiscussPopu = new DiscussPopu(context, focusView,
								list.get(position).get("act_id").toString(),
								list.get(position).get("ap_id").toString());
						myDiscussPopu.showPopup();
					}else{
						classhourActivityPopu myActivityPopu = new classhourActivityPopu(context, focusView,
								list.get(position).get("act_id").toString(),
								list.get(position).get("act_type").toString());
						myActivityPopu.showPopup();
					}
				}
			});
			return convertView;

		}
		// 生成环节列表
		private View addTacheView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.tache_listview_item, null);
				
				holder.biaoti = (TextView) convertView
						.findViewById(R.id.tache_name);
				
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			
			holder.biaoti.setText(list.get(position).get("ta_title").toString());
						return convertView;

		}
		class Holder {
			TextView workNum, name, msg, time, xuhao , biaoti, shijian, banji, zhuangtai;
			ImageView img;
			RelativeLayout acRelativeLayout, resourceRe;
			RadioButton workPub, workCheck, workCorrect;
		}
	}
	
	/**
	 * 等待框
	 */
	protected Dialog onCreateDialog(int id) {
		ProgressDialog dialog = null;
		switch (id) {
		case MyContants.HTTP_WAITING:
			dialog = new ProgressDialog(this);
			dialog.setMessage("系统正在加载，请稍等....");
			break;
		}

		return dialog;
	}
}
