package dkt.teacher.activity;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.LinkedList;
import java.util.List;
import org.apache.http.HttpStatus;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import dkt.teacher.MyApp;
import dkt.teacher.MyContants;
import dkt.teacher.R;
import dkt.teacher.database.ResourceServer;
import dkt.teacher.model.Resource;
import dkt.teacher.net.HttpApacheMapPostThread;
import dkt.teacher.net.HttpHandler;
import dkt.teacher.util.Md5Util;
import dkt.teacher.util.ViewUtil;
import dkt.teacher.util.bitmap.FinalBitmap;
import dkt.teacher.view.HorizontalListView;
import dkt.teacher.view.popu.ClassHourPopu;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.os.Message;
import android.os.Parcelable;
import android.provider.MediaStore;
import android.support.v4.view.PagerAdapter;
import android.support.v4.view.ViewPager;
import android.support.v4.view.ViewPager.OnPageChangeListener;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.RadioButton;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.AdapterView.OnItemClickListener;

public class CourseActivity extends Activity{

	private HorizontalListView coureListView;
	private CourseAdapter listViewAdapter;
	private CourseAdapter gridViewAdapter;
	private ViewPager viewPager;
	private ClassHourPopu popClassHour;
	private View focusView;
	private FinalBitmap fb;
	
	List<HashMap<String, Object>> courseList = 
		new ArrayList<HashMap<String, Object>>(); // 课程数据列表
	List<HashMap<String, Object>> lessonList = 
		new ArrayList<HashMap<String, Object>>(); // 课文数据列表
	List<HashMap<String, Object>> classhourList = 
		new ArrayList<HashMap<String, Object>>(); // 课时数据列表

	private LinkedList<View> views = new LinkedList<View>(); // viewpage的view集合
	
	private int isTheSam = 0;  // 同步课程list和课文list的下标
	private GridView classHourGridView;
	private Context context;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		// 设置无标题
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// 设置全屏
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.course);

		initView(); // 初始化页面全局控件
        addFun();  // 组装页面点击事件
	}
	
	/**
     *  初始化页面全局控件
     * 
     * */
	private void initView() {

		context = CourseActivity.this;
		coureListView = (HorizontalListView) findViewById(R.id.course_listview);
		viewPager = (ViewPager) findViewById(R.id.view_pager);
		focusView = findViewById(R.id.main_focus);
		classHourGridView = (GridView) findViewById(R.id.course_class_hour_gridview);
		
		MyApp app = (MyApp) this.getApplication();
		TextView userNameTextView = (TextView) findViewById(R.id.home_username_text);
		userNameTextView.setText(app.getUserName());
		ImageView userImg = (ImageView) findViewById(R.id.home_title_head);
		fb = new FinalBitmap(context).init();
		fb.display(userImg, app.getUserImgUrl());
		
//		classHourGridView.setOnItemClickListener(new OnItemClickListener() {
//
//			@Override
//			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
//					long arg3) {
//				// TODO Auto-generated method stub
//				MyApp app = (MyApp) getApplication();
//				app.setClId(classhourList.get(arg2).get("cl_id").toString());
//				app.setClName(classhourList.get(arg2).get("cl_title").toString());
//				
//				Intent intent  = new Intent(context, ClassHourActivity.class);
//				startActivity(intent);	
//			}
//		});
		doCourseListHttp();   
		
	}
	
	 /**
     *  页面点击事件
     * 
     * */
	private void addFun() {
		
		// 返回
		findViewById(R.id.course_back_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
//				
//				Intent intent  = new Intent(CourseActivity.this, HomeActivity.class);
//				intent.setFlags(intent.FLAG_ACTIVITY_CLEAR_TOP);
//				startActivity(intent);
				finish();
			}
		});	
		
//		// 首页
//		findViewById(R.id.course_home_btn).setOnClickListener(new OnClickListener() {
//			
//			@Override
//			public void onClick(View v) {
//				// TODO Auto-generated method stub
//				Intent intent  = new Intent(CourseActivity.this, HomeActivity.class);
//				intent.setFlags(intent.FLAG_ACTIVITY_CLEAR_TOP);
//				startActivity(intent);
//			}
//		});	
//		
//		// 课程中心
//		findViewById(R.id.course_class_btn).setOnClickListener(new OnClickListener() {
//			
//			@Override
//			public void onClick(View v) {
//				// TODO Auto-generated method stub
//				
//			}
//		});	
//		
//		// 小工具
//		findViewById(R.id.course_tool_btn).setOnClickListener(new OnClickListener() {
//			
//			@Override
//			public void onClick(View v) {
//				// TODO Auto-generated method stub
//				ToolPopu myToolPopu = new ToolPopu(context, v);
//				myToolPopu.showPopup();
//			}
//		});
	}
	
	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		// TODO Auto-generated method stub
		return false;
	}
	
	@Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
    	// TODO Auto-generated method stub
    	super.onActivityResult(requestCode, resultCode, data);
    
		if (resultCode == RESULT_OK) {
						
			// 拍照保存完成后
			if(requestCode == MyContants.RESOURCE_IMG) {
				
				saveResource(MyContants.RESOURCE_IMG, getAbsoluteImagePath(data.getData()),
						getString(R.string.default_img_name), getString(R.string.photograph_name));
	
			}
			// 录音保存完成后
			else if(requestCode == MyContants.RESOURCE_AUDIO) {
				
				saveResource(MyContants.RESOURCE_AUDIO, getAbsoluteImagePath(data.getData()),
						getString(R.string.default_recording_name), getString(R.string.recording_name));
			
			}

		}
    	
    }
	
	/**
	 * 得到用户的课程
	 */
	private void doCourseListHttp() {
		showDialog(MyContants.HTTP_WAITING);  
		MyApp app = (MyApp) getApplication();
		int userId = app.getUserId();
		String skey = app.getSkey();
		String sId = app.getsId();
		
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Course.lists");
		map.put("args[a_id]", userId);
		map.put("args[s_id]", sId);
		map.put("skey", skey);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);

		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new CourseHandler(CourseActivity.this,
				MyContants.DO_HTTP_COURSE_LIST), map);
	}
	
	/**
	 * 根据课程得到课文
	 */
	private void doLessonHttp(String courseId, String cId, String cName) {
		showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) getApplication();
		String skey = app.getSkey();
		int userId = app.getUserId();
		app.setcId(cId);
		app.setCoId(courseId);
		app.setcName(cName);
		
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Lesson.lists");
		map.put("args[co_id]", courseId);
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("args[c_id]", cId);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new CourseHandler(CourseActivity.this,
				MyContants.DO_HTTP_COURSE_LESSON), map);
	}
	
	/**
	 * 得到该课文下的课时
	 */
	private void doClasshourListHttp(String lId) {
		MyApp app = (MyApp) context.getApplicationContext();
		int userId = app.getUserId();
		String skey = app.getSkey();
		String coId = app.getCoId();
		String cId = app.getcId();
		
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Classhour.lists");
		map.put("args[a_id]", userId);
		map.put("args[l_id]", lId);
		map.put("args[co_id]", coId);
		map.put("args[c_id]", cId);
		map.put("skey", skey);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);

		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new CourseHandler(context,
				MyContants.DO_HTTP_CLASSHOUR_LIST), map);
	}
	
	/**
	 * 发布课时
	 */
	private void doPubClasshourHttp(String cLId) {
		showDialog(MyContants.HTTP_WAITING);  
		MyApp app = (MyApp) getApplication();
		int userId = app.getUserId();
		String skey = app.getSkey();	
		String cId = app.getcId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Classhour.publish");
		map.put("args[a_id]", userId);
		map.put("args[cl_id]", cLId);
		map.put("args[c_id]", cId);
		map.put("skey", skey);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);

		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new CourseHandler(CourseActivity.this,
				MyContants.DO_HTTP_CLASSHOUR_PUB), map);
	}
	
	/**
	 * 解析课程数据
	 * */
	private void doCourseSucce(String result) {
		/**
		 * {"status":1,"info":{
		 * "list":[{"co_id":"25","a_id":"158","s_id":"5",
		 * "co_title":"\u521d\u4e8c(2)\u73ed \u6570\u5b66\u6559\u6848",
		 * "co_subject":"1",
		 * "co_cover":"http:\/\/pic.dkt.com\/CourseCover\/default.png",
		 * "cro_id":0,"c_id":"15"},
		 * 
		 * 
		 * {"co_id":"17","a_id":"158","s_id":"5",
		 * "co_title":"\u5c0f\u4e00(1)\u73ed copy\u5c0f\u5b66\u8bed\u6587",
		 * "co_subject":"1",
		 * "co_cover":"http:\/\/pic.dkt.com\/CourseCover\/default.png",
		 * "cro_id":0,"c_id":"14"}]}}

		 * */
		
		try {
			JSONObject jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				ViewUtil.myToast(context, "无课程数据");
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
				map.put("co_id", object.get("co_id").toString());
				map.put("co_title", object.get("co_title").toString());
				map.put("c_id", object.get("c_id").toString());
				map.put("co_cover", object.get("co_cover").toString());
				courseList.add(map);
			}
			addCourseListView();
		} catch (Exception e) {
			// TODO: handle exception
		}
	}
	
	// 重置课文列表
	private void doLessonSucess(String result) {
		
		/**
		 * {"status":1,"info":{"list":[
		 * {"l_id":"160","co_id":"25","a_id":"158","l_pid":"159",
		 * "l_sort":"255","l_title":"\u8bfe\u65871"},
		 * {"l_id":"166","co_id":"25","a_id":"158","l_pid":"159",
		 * "l_sort":"255","l_title":"\u8bfe\u65872"},
		 * {"l_id":"167","co_id":"25","a_id":"158","l_pid":"159",
		 * "l_sort":"255","l_title":"\u8bfe\u65873"},
		 * {"l_id":"168","co_id":"25","a_id":"158","l_pid":"159",
		 * "l_sort":"255","l_title":"\u8bfe\u65874"},
		 * {"l_id":"169","co_id":"25","a_id":"158","l_pid":"159",
		 * "l_sort":"255","l_title":"\u8bfe\u65875"},
		 * {"l_id":"170","co_id":"25","a_id":"158","l_pid":"159",
		 * "l_sort":"255","l_title":"\u8bfe\u65876"},
		 * {"l_id":"171","co_id":"25","a_id":"158","l_pid":"159",
		 * "l_sort":"255","l_title":"\u8bfe\u65877"}]}}

		 * */
		lessonList.clear();
		
		
		
		try {
			JSONObject jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				ViewUtil.myToast(context, "无课文数据");
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
				map.put("l_title", object.get("l_title").toString());
				map.put("l_id", object.get("l_id").toString());
				lessonList.add(map);
			}
		} catch (Exception e) {
			// TODO: handle exception
		}
		
		if(null != gridViewAdapter) {
			gridViewAdapter.refresh(lessonList, isTheSam);
			GridView myGridView = (GridView) views.get(isTheSam).findViewById(R.id.course_gridview);
			myGridView.setAdapter(gridViewAdapter);
		}else{
			addClassHourGridView();
		}
		
	}
	
	private void doClassHourSucess(String result) {
		/**
	     * {"status":1,"info":{"list":[
	     * {"cl_id":"1","co_id":"1","l_id":"2","a_id":"2",
	     * "s_id":"1","cl_title":"\u7b2c\u4e00\u8bfe\u65f6",
	     * "cl_sort":"1","c_id":"","cro_id":"","cl_is_published":"0",
	     * "cl_status":"1"},
	     * {"cl_id":"2","co_id":"1","l_id":"2","a_id":"2",
	     * "s_id":"1","cl_title":"\u6d4b\u8bd5\u8bfe\u65f6",
	     * "cl_sort":"2","c_id":",1,","cro_id":"","cl_is_published":"1",
	     * "cl_status":"1"},
	     * {"cl_id":"3","co_id":"1","l_id":"2","a_id":"2",
	     * "s_id":"1","cl_title":"\u6d4b\u8bd5\u8bfe\u65f6",
	     * "cl_sort":"3","c_id":",1,","cro_id":"","cl_is_published":"1",
	     * "cl_status":"1"},
	     * {"cl_id":"4","co_id":"1","l_id":"2","a_id":"2",
	     * "s_id":"1","cl_title":"\u6d4b\u8bd5\u8bfe\u65f6",
	     * "cl_sort":"4","c_id":",1,","cro_id":"","cl_is_published":"1",
	     * "cl_status":"1"},
	     * {"cl_id":"5","co_id":"1","l_id":"2","a_id":"2",
	     * "s_id":"1","cl_title":"\u6d4b\u8bd5\u8bfe\u65f6",
	     * "cl_sort":"5","c_id":",1,","cro_id":"","cl_is_published":"1",
	     * "cl_status":"1"}]}}

	     * */
		
		classhourList.clear();
		try {
			 
			JSONObject jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				ViewUtil.myToast(context, "无课时数据");
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
				map.put("cl_title", object.get("cl_title").toString());
				map.put("cl_id", object.get("cl_id").toString());
				map.put("cl_is_published", object.get("cl_is_published").toString());
				classhourList.add(map);
			}
			CourseAdapter classhourAdapter = new CourseAdapter(context,
					classhourList, 2, 0);
			classHourGridView.setAdapter(classhourAdapter);
		} catch (Exception e) {
			// TODO: handle exception
		}
	}
	
	/**
	 * 解析课时发布返回
	 * */
	private void doPubClasshourSucess(String result) {
		/**
		 * {"status":0,"info":"\u73ed\u7ea7\u5df2\u7ed1\u5b9a"}
		 * */
		JSONObject jsonObject;
		try {
			jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				ViewUtil.myToast(context, jsonObject.get("info").toString());
				return;
			}else if(status.equals("1")){
				ViewUtil.myToast(context, "课时发布成功");
			}
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}
	
	class CourseHandler extends HttpHandler {
		String tag;

		public CourseHandler(Context context, String tag) {
			super(context, tag);
			// TODO Auto-generated constructor stub
			this.tag = tag;
		}

		@Override
		public void handleMessage(Message msg) {
			super.handleMessage(msg);
			dismissDialog(MyContants.HTTP_WAITING);

			if (msg.what == HttpStatus.SC_OK) {
				System.out.println("______返回值开始_____________");
				System.out.println(msg.obj);
				System.out.println("________返回值结束____________");
				if(tag == MyContants.DO_HTTP_COURSE_LIST) {
					doCourseSucce((String) msg.obj);
				}
				else if(tag == MyContants.DO_HTTP_COURSE_LESSON) {
					doLessonSucess((String) msg.obj);
				}else if(tag == MyContants.DO_HTTP_CLASSHOUR_LIST) {
					doClassHourSucess((String) msg.obj);
				}else if(tag == MyContants.DO_HTTP_CLASSHOUR_PUB) {
					doPubClasshourSucess((String) msg.obj);
				}
				
			}

		}

	}
	
	/**
     *  获取uri的绝对路径
     *  @param uri
	 *  @return String
     * */
    protected String getAbsoluteImagePath(Uri uri) 
    {
        
         String [] proj={MediaStore.Images.Media.DATA};
         Cursor cursor = managedQuery( uri, proj, null, null, null);                
         int column_index = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
         cursor.moveToFirst();
         return cursor.getString(column_index);
         
     }
    
    /**
     * 文件保存完成后将资源信息新增到资源数据库
     * @param resourceType  资源类型
     * @param resourcePath  资源路径
     * @param defaultName   资源默认名称
     * @param title         弹出框title
     * */
    private void saveResource(final int resourceType, final String resourcePath,
    		final String defaultName, String title) {
    	
    	final EditText resourceNameEdit = new EditText(this);
    	resourceNameEdit.setTextColor(R.color.black);
    	resourceNameEdit.setBackgroundResource(R.drawable.system_set_edit_bg);
		AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(title).setIcon(android.R.drawable.ic_dialog_info).setView(resourceNameEdit);
        builder.setNegativeButton(R.string.cancel, new DialogInterface.OnClickListener() {
					
			public void onClick(DialogInterface dialog, int which) {
				
				// 删除当前完成保存的文件
				File delFile=new File(resourcePath);
				delFile.delete();
				
			}
			
		});
        
        builder.setPositiveButton(R.string.ok, new DialogInterface.OnClickListener() {

            public void onClick(DialogInterface dialog, int which) {
            	String resourceName = "";
            	
            	// 如果输入框为空则使用默认名称
            	if("".equals(resourceNameEdit.getText().toString())) {
					resourceName = defaultName;
            	}else{
					resourceName = resourceNameEdit.getText().toString();
            	}
            	long currentTimestamp = System.currentTimeMillis();
            	
            	Resource resource = new Resource();
    			resource.setResourceName(resourceName);
    			resource.setResourceType(resourceType);
    			resource.setResourcePath(resourcePath);
    			resource.setResourceCreatTime(currentTimestamp);
    			
    			ResourceServer resourceServer = new ResourceServer();
    			resourceServer.insertResource(resource);
    			
				Toast.makeText(context, resourceName+getString(R.string.save_ok), Toast.LENGTH_SHORT).show();

             }
        });
        builder.show();
    }
    
	
	/**
     *  初始化课程列表和viewPage
     * 
     * */
	private void addCourseListView() {
		listViewAdapter = new CourseAdapter(CourseActivity.this,
				courseList, 0, 0);
		coureListView.setAdapter(listViewAdapter);
		coureListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				
				// 如果点击的是已经选中的则不操作
				if(isTheSam == arg2) {
					
				}else {
					viewPager.setCurrentItem(arg2);
					listViewAdapter.refresh(courseList, arg2);
					isTheSam = arg2;
					doLessonHttp(courseList.get(arg2).get("co_id").toString(),
							courseList.get(arg2).get("c_id").toString(),
							courseList.get(arg2).get("co_title").toString());
				}
				
			}
		});
		if(courseList.size() > 0) {
//			final GridView classHourGridView = (GridView) findViewById(R.id.course_class_hour_gridview);
//			classHourGridView.setOnItemClickListener(new OnItemClickListener() {
//
//				@Override
//				public void onItemClick(AdapterView<?> arg0, View arg1,
//						int arg2, long arg3) {
//					// TODO Auto-generated method stub
//					Intent intent  = new Intent(context, ClassHourActivity.class);
////					intent.setFlags(intent.FLAG_ACTIVITY_NO_HISTORY);
//					startActivity(intent);				}
//			});
			for(int i=0;i<courseList.size();i++) {
				View view = getLayoutInflater().inflate(R.layout.mypageview, null);
				GridView myGridView = (GridView) view.findViewById(R.id.course_gridview);
				myGridView.setOnItemClickListener(new OnItemClickListener() {

					@Override
					public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
							long arg3) {
						// TODO Auto-generated method stub
						
						MyApp app = (MyApp) getApplication();
						app.setlId(lessonList.get(arg2).get("l_id").toString());
						app.setlName(lessonList.get(arg2).get("l_title").toString());
//						showClasshourPop();
						doClasshourListHttp(lessonList.get(arg2).get("l_id").toString());
					}
				});
				views.add(view);
			}
			doLessonHttp(courseList.get(0).get("co_id").toString(),
					courseList.get(0).get("c_id").toString(),
					courseList.get(0).get("co_title").toString());
		}
		viewPager.setAdapter(new MyAdapter(courseList));
		viewPager.setOnPageChangeListener(new MyPageChangeListener());
	}
	
	private void addClassHourGridView() {
		
		gridViewAdapter = new CourseAdapter(CourseActivity.this,
				lessonList, 1, 0);
		GridView myGridView = (GridView) views.get(0).findViewById(R.id.course_gridview);
		myGridView.setAdapter(gridViewAdapter);
		
	}
	
	class CourseAdapter extends BaseAdapter {
		List<HashMap<String, Object>> list;
		Context context;
		int tag;
		Holder holder;
		int i;
		
		public CourseAdapter(Context context,
				List<HashMap<String, Object>> list, int tag, int i) {
			this.list = list;
			this.context = context;
			this.tag = tag;
			this.i = i;
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
				view = addCourseView(position, convertView, parent);
			}else if(1 == tag) {
				view = addLessonView(position, convertView, parent);
			}else if(2 == tag) {
				view = addClassHourView(position, convertView, parent);
			}
			
			
			return view;
		}
		
		// 刷新适配器
		public void refresh(List<HashMap<String, Object>> list, int i) {
			this.list = list;
			this.i = i;
			this.notifyDataSetChanged();
		}
		
		// 生成课程列表视图
		private View addCourseView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {//     android:background="@drawable/yuwen"
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.course_listview_item, null);
				holder.courseImg = (ImageView) convertView
						.findViewById(R.id.course_img);
				holder.courseName = (TextView) convertView
						.findViewById(R.id.course_name);
				holder.courseRela = (RelativeLayout) convertView
						.findViewById(R.id.course_listview_rela);	
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			
			String imageUrl = list.get(position).get("co_cover").toString();
			System.out.println("===="+imageUrl);
			fb.display(holder.courseImg, imageUrl);
			
			holder.courseName.setText(list.get(position).get("co_title").toString());
			if(position == i) {
				holder.courseRela.setBackgroundResource(R.drawable.course_listview_item_bg_click);
				holder.courseName.setTextColor(context.getResources().getColor(R.color.white));

			}else {
				holder.courseRela.setBackgroundResource(R.drawable.course_listview_item_bg);

			}
			
			return convertView;

		}
		// 生成课文列表视图
		private View addLessonView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.course_lesson_gridview_item, null);
				holder.lessonName = (TextView) convertView
						.findViewById(R.id.course_lesson_name);
					
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			String classHourNameString = "";
			if(position >= 9) {
				classHourNameString = (position+1)+"、"+list.get(position).get("l_title").toString();

			}else{
				classHourNameString = (position+1)+"、"+list.get(position).get("l_title").toString();

			}
			if(classHourNameString.length() > 8) {
				classHourNameString = classHourNameString.substring(0, 8) + "...";
			}
			holder.lessonName.setText(classHourNameString);
			
			return convertView;

		}
		// 生成课时列表视图
		private View addClassHourView(final int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.course_class_hour_gridview_item, null);
				holder.classHourName = (TextView) convertView
						.findViewById(R.id.course_class_hour_name);
				holder.classRela = (RelativeLayout) convertView
						.findViewById(R.id.course_class_re);	
				holder.classPub = (Button)convertView
						.findViewById(R.id.classhour_pub);	
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			String classHourNameString = list.get(position).get("cl_title").toString();
//			holder.classHourName.setBackgroundResource(R.drawable.course_class_hour_name_bg);
			holder.classPub.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					MyApp app = (MyApp) getApplication();
					app.setClId(classhourList.get(position).get("cl_id").toString());
					app.setClName(classhourList.get(position).get("cl_title").toString());
					doPubClasshourHttp(classhourList.get(position).get("cl_id").toString());
				}
			});
			holder.classRela.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					MyApp app = (MyApp) getApplication();
					app.setClId(classhourList.get(position).get("cl_id").toString());
					app.setClName(classhourList.get(position).get("cl_title").toString());
					
					Intent intent  = new Intent(context, ClassHourActivity.class);
					startActivity(intent);
				}
			});
			holder.classHourName.setText(classHourNameString);
			
			return convertView;

		}
		class Holder {
			TextView courseName, classHourName, lessonName;
			ImageView courseImg;
			RelativeLayout courseRela, classRela;
			Button classPub;
		}

	}
	
	private class MyAdapter extends PagerAdapter {
		
		List<HashMap<String, Object>> list;
		public MyAdapter(List<HashMap<String, Object>> list) {
			this.list = list;
		}
		
		@Override
		public int getCount() {
			return list.size();
		}

		@Override
		public Object instantiateItem(View arg0, int arg1) {
			((ViewPager) arg0).addView(views.get(arg1),0);
			return views.get(arg1);
		}

		@Override
		public void destroyItem(View arg0, int arg1, Object arg2) {
			View view = (View)arg2;
		     ((ViewPager) arg0).removeView(view);
		     view =null;
		}

		@Override
		public boolean isViewFromObject(View arg0, Object arg1) {
			return arg0 == arg1;
		}

		@Override
		public void restoreState(Parcelable arg0, ClassLoader arg1) {

		}

		@Override
		public Parcelable saveState() {
			return null;
		}

		@Override
		public void startUpdate(View arg0) {

		}

		@Override
		public void finishUpdate(View arg0) {

		}
	}
	
	private class MyPageChangeListener implements OnPageChangeListener {

		public void onPageSelected(int position) {
//			System.out.println("=======position========="+position);
			listViewAdapter.refresh(courseList, position);
			isTheSam = position;
			doLessonHttp(courseList.get(position).get("co_id").toString(),
					courseList.get(position).get("c_id").toString(),
					courseList.get(position).get("co_title").toString());
			
		}

		public void onPageScrollStateChanged(int arg0) {
			
		}

		public void onPageScrolled(int arg0, float arg1, int arg2) {
			
		}
	}
	
	/**
	 *  弹出系统版本框
	 */
	private void showClasshourPop(){
		if(popClassHour == null){
			popClassHour= new ClassHourPopu(context, focusView);
		}else{
			popClassHour.clearPopup();
		}
		
		popClassHour.showPopup();
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